<?php

    /*
        Adaptive Images for WordPress 
        Plugin URI: http://www.nevma.gr
        Description: Resizes your images, according to each user's screen size, to reduce total download time of web pages in mobile devices.
        Version: 0.2.02
        Author: Nevma - Creative Know-How
        Author URI: http://www.nevma.gr
        License: GPL2
        License URI: https://www.gnu.org/licenses/gpl-2.0.html
    */


$wprxr_pluginname = "WP Resolutions";
$wprxr_shortname = "wprxr";
$wprxr_ai_path = plugin_dir_path(__FILE__) . "adaptive-images/ai-main.php";
$wprxr_options = array (

array(  "type" => "open"),

/**
 * This option provides the possibility to edit adaptive-images.php config section directly from plugin settings page.
 * Make sure adaptive-images.php has write permissions
 * This option is not saved in WordPress database.
 **/
array(	"name" => "Adaptive-images config",
		"desc" => "Edit config section of adaptive-images.php",
		"id" => $shortname . "_ai_config",
		"type" => "textarea",
		"std" => wprxr_get_ai_config(),
		"handler" => "wprxr_set_ai_config" ),

array(	"name" => "Watch paths",
		"desc" => "Enter the paths that you want Resolutions to watch. Put each path on a new line (separate paths with a return.)",
		"id" => $shortname . "_include_paths",
		"type" => "textarea",
		"std" => "/wp-content/uploads/"),

array(  "type" => "close")

);



// Javascript cookie needs to be created before any image requests (including css)
function wprxr_js()
{
	echo "<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>";
}

/**
 * This function returns config section of adaptive images php file
 *
 * @return string Config Section of adaptive-images.php
 * @param string $context If set, uses this parameter instead of file contents
 * @author Karlis Bikis 
 **/
function wprxr_get_ai_config( $context = "" )
{
	if( !$context )
	{
		global $wprxr_ai_path;
		$context = file_get_contents( $wprxr_ai_path );
	}
	preg_match("%/\* ?CONFIG -+ ?\*/\r?\n?(.*)\r?\n?/\* ?END CONFIG%ms", $context, $matches );
	return $matches[1];
}

/**
 * This function saves new config of adaptive images php file
 *
 * @return void
 * @param string $val New Config
 * @author Karlis Bikis 
 **/
function wprxr_set_ai_config( $val )
{
	// Get current config
	global $wprxr_ai_path;
	$everything = file_get_contents( $wprxr_ai_path );
	$currentConfig = wprxr_get_ai_config( $everything );

	// Get file with new config
	$newFile = str_replace( $currentConfig, "\n" . stripslashes($val), $everything );

	// Save new file
	file_put_contents( $wprxr_ai_path, $newFile ) or die("Cannot write to adaptive-images.php. Check file permissions.");
}


function wprxr_add_page()
{

    global $wprxr_options, $wprxr_pluginname, $wprxr_shortname;
	$options = $wprxr_options;
	$pluginname = $wprxr_pluginname;
	$shortname = $wprxr_shortname;

    if ( $_GET['page'] == 'wprxr' )
    {
        if ( 'save' == $_REQUEST['action'] )
        {
		    foreach ($options as $value)
		    {
		        if( isset( $_REQUEST[ $value['id'] ] ) )
		        {
					// Check if option has custom data handler
					if( isset( $value['handler'] ) && is_callable( $value['handler'] ) )
					{
						$handler = $value['handler'];
						$handler( $_REQUEST[ $value['id'] ] );
						continue;
					}

			    	// Use update_option if no option handler specified
		        	update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
		        }
		        
		        else
		        {
		        	delete_option( $value['id'] );
		        }
			}

            header("Location: options-general.php?page=wprxr&saved=true");
            die;
        }
    }

    add_options_page("WP Resolutions", "WP Resolutions", 'edit_themes', 'wprxr', 'wprxr_page');
}



// Set up the plugin when it is activated
function wprxr_activate()
{
	add_option('wprxr_include_paths', '/wp-content/uploads/');
	
	$new_htaccess = wprxr_htaccess();

	if ( !file_exists(get_home_path() . '.htaccess') ) 
	{
		@fopen(get_home_path() . '.htaccess', 'w') or die("<div id=\"message\" class=\"error\"><strong>No .htaccess file exists, and one could not be created.</strong> To fix this you can: <br> 1. Update permissions for the WordPress root directory to allow write access, or <br> 2. Manually create your .htaccess file with this rewrite block:<br><br> <pre>$new_htaccess</pre></div>");
	}
	
	if ( !is_writable(get_home_path() . '.htaccess') )
	{
		die("<div id=\"message\" class=\"error\"><strong>The permissions on your .htaccess file restrict automatic setup.</strong> To fix this you can: <br> 1. Change permissions on the .htaccess file in your WordPress root directory to allow write access, or <br> 2. Manually add this rewrite block to your .htaccess file: <br><br> <pre>$new_htaccess</pre></div>");
	}
	
	$old_htaccess = file_get_contents(get_home_path() . '.htaccess');
		
	if ( preg_match('/# WP Resolutions.*# END WP Resolutions\n/s', $old_htaccess) )
	{
		$new_htaccess = preg_replace('/# WP Resolutions.*# END WP Resolutions\n/s', $new_htaccess, $old_htaccess);
	}
	
	else
	{
		$new_htaccess .= $old_htaccess;
	}
	
	file_put_contents(get_home_path() . '.htaccess', $new_htaccess) or die("<div id=\"message\" class=\"error\"><strong>Could not write to .htaccess.</div>");
}



// Remove the plugin when it is deactivated
function wprxr_deactivate()
{
	$old_htaccess = file_get_contents(get_home_path() . '.htaccess');
	$new_htaccess = preg_replace('/# WP Resolutions.*# END WP Resolutions\n/s', '', $old_htaccess);
	file_put_contents(get_home_path() . '.htaccess', $new_htaccess);
}



// This function returns the .htaccess rewrite block
function wprxr_htaccess ()
{
	global $wprxr_ai_path;

	$theme_directory = "/".trim(str_replace(str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]), '', str_replace("\\", "/", dirname(__FILE__))), "/");

	$wprxr_include_paths = get_settings('wprxr_include_paths');
	
	$includes = explode("\n", $wprxr_include_paths);
    
	$new_htaccess = "# WP Resolutions\n<IfModule mod_rewrite.c>\nRewriteEngine On\n\n# Watch directories:";
	
	$i = 0;
	$length = count($includes) - 1;

	foreach ( $includes as $include )
	{
		if ( $i != $length ) $new_htaccess .= "\nRewriteCond %{REQUEST_URI} " . trim($include) . " [OR]";
		else if ( $i == $length ) $new_htaccess .= "\nRewriteCond %{REQUEST_URI} $include";
		$i++;
	}

	$new_htaccess .= "\n\nRewriteRule \.(?:jpe?g|gif|png)$ " . $wprxr_ai_path . "\n</IfModule>\n# END WP Resolutions\n";
	
	return $new_htaccess;
}



// The WP Resolutions settings page
function wprxr_page()
{

	global $wprxr_options, $wprxr_pluginname, $wprxr_shortname;
	$options = $wprxr_options;
	$pluginname = $wprxr_pluginname;
	$shortname = $wprxr_shortname;
	
	$new_htaccess = wprxr_htaccess();

?>
<div class="wrap">
<h2><?php echo $pluginname; ?> settings</h2>

<?php
	
    if ( $_REQUEST['saved'] )
    {
		
		$old_htaccess = file_get_contents(get_home_path() . '.htaccess');
		
		if ( preg_match('/# WP Resolutions.*# END WP Resolutions\n/s', $old_htaccess) )
		{
			$new_htaccess = preg_replace('/# WP Resolutions.*# END WP Resolutions\n/s', $new_htaccess, $old_htaccess);
		}
		
		else
		{
			$new_htaccess .= $old_htaccess;
		}
		
		file_put_contents(get_home_path() . '.htaccess', $new_htaccess);
		
		echo '<div id="message" class="updated fade"><p><strong>WP Resolutions updated successfully.</strong></p></div>';
    }
?>

<form method="post">

<?php

foreach ($options as $value)
{

	switch ( $value['type'] )
	{

		case "open":
			echo '<table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">';
		break;

		case "close":
			echo '</table><br />';
		break;

		case "title":
			echo '<table width="100%" border="0" style="background-color:#dceefc; padding:5px 10px;"><tr>';
    		echo '<td colspan="2"><h3 style="font-family:Georgia,\'Times New Roman\',Times,serif;">' .  $value['name'] . '</h3></td>';
			echo '</tr>';
		break;

		case 'text':
			$value_or_std = ( get_settings( $value['id'] ) != "" ) ? get_settings( $value['id'] ) : $value['std'];
			echo '<tr>';
			echo '<td width="20%" rowspan="2" valign="middle"><strong>' . $value['name'] . '</strong></td>';
			echo '<td width="80%"><input style="width:400px;" name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '" value="' . $value_or_std . '" /></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><small>' . $value['desc'] . '</small></td>';
			echo '</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>';
		break;

		case 'textarea':
			$value_or_std = ( get_settings( $value['id'] ) != "" ) ? get_settings( $value['id'] ) : $value['std'];
			echo '<tr>';
			echo '<td width="20%" rowspan="2" valign="middle"><strong>' . $value['name'] . '</strong></td>';
			echo '<td width="80%"><textarea name="' . $value['id'] . '" style="width:400px; height:200px;" type="' . $value['type'] . '" cols="" rows="">' . $value_or_std . '</textarea></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><small>' . $value['desc'] . '</small></td>';
			echo '</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>';
		break;

		case 'select':
			echo '<tr>';
			echo '<td width="20%" rowspan="2" valign="middle"><strong>' . $value['name'] . '</strong></td>';
			echo '<td width="80%"><select style="width:240px;" name="' . $value['id'] . '" id="' . $value['id'] . '">';
			foreach ($value['options'] as $option)
			{
				$selected = ( get_settings( $value['id'] ) == $option || $option == $value['std']) ? ' selected="selected"' : '';
				echo '<option' . $selected . '>' . $option . '</option>';
			}
			echo '</select></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><small>' . $value['desc'] . '</small></td>';
			echo '</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>';
		break;

		case "checkbox":
			$cheked = (get_settings($value['id'])) ? ' checked="checked"' : '';
			echo '<tr>';
			echo '<td width="20%" rowspan="2" valign="middle"><strong>' . $value['name'] . '</strong></td>';
			echo '<td width="80%">';
			echo '<input type="checkbox" name="' . $value['id'] . '" id="' .  $value['id'] . '" value="true"' . $checked . '/>';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><small>' . $value['desc'] . '</small></td>';
			echo '</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>';
		break;

	}
}

?>

<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>

<?php
}



// Add settings link on the WordPress plugins page.
function wprxr_add_settings_link($links, $file)
{
	static $this_plugin;
	
	if ( !$this_plugin )
		$this_plugin = plugin_basename(__FILE__);
	 
	if ( $file == $this_plugin )
	{
		$settings_link = '<a href="options-general.php?page=wprxr">'.__("Settings", "WP Resolutions").'</a>';
		array_unshift($links, $settings_link);
	}
	
	return $links;
}

add_action(wp_head, wprxr_js, 0);
add_action('admin_menu', 'wprxr_add_page');
register_activation_hook(__FILE__, 'wprxr_activate');
register_deactivation_hook(__FILE__, 'wprxr_deactivate');
add_filter('plugin_action_links', 'wprxr_add_settings_link', 10, 2 );

?>