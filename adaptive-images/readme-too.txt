


                            ==========================================
                            #                                        #
                            # Message from the plugin author (Nevma) #
                            #                                        #
                            ==========================================



1. This directory holds the files of the Adaptive Images library http://adaptive-images.com/.

2. The original php file of the Adaptive Images library was named adaptive-images.php. Here it is named ai-main.php 
   in order to avoid confusion. We already had too many files, directories and names of this kind.

   So:

       adaptive-images.php. => ai-main.php

3. Inside ai-main.php we made these very simple alterations:
   
       i)   made the settings section a little clearer to read
       ii)  included the ai-user-settings.php file when it is available
       iii) added a a piece of code which sends the orginal image to the browser when the orginal image width and 
            device screen width are both bigger than the biggest available breakpoint

4. The file ai-user-settings.php is a file that overrides the Adaptive Images library settings. It is controlled in 
   the settings of the WordPress plugin admin area. If it is not available then the usual settings inside the library 
   will be used.


=======================================================================================================================


 * Apart from the above, the Adaptive Images library logic is actually unchanged.
 * One could simply ignore all these and override them with the original file and it should all still be fine.
