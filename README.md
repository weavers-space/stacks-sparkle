# Stacks Sparkle Appcast Tool


This utility is a great way to manage your Sparkle Updates for the Stacks Plugin for Rapidweaver. This script will take into effect the Stacks API Version and can serve up different appcast URLs based on that version. This script also verifies that the connection is coming from the Sparkle framework. This is so that no one can just open a browser and get to your updates. 

## Usage

You will want to customize this script to your environment. Minimally, you will want to configure the variables defined in the two switch statements found in the script. The first switch statement defines the base url for the appcast. The base url can change based on the Stacks API verison. As the Stacks API version increases, you can easily add more cases to this switch statement.  

	switch ($stacks_api) {
		case 3:
			$base_url = 'http://domain.com/appcasts-3';
			break;
		default:
			$base_url = 'http://domain.com/appcasts';
			break;	
	}


The next switch statement takes into effect the name of your stack and build upon the base url defined above to determine the final appcast url that will be served up. You can add cases based on the name of your stack. However, the default case should serve you well a majority of the time. 

	switch ($product_name) {
	    case "0.Styled-Stack":
	        $appcast_url = $base_url .'/StyledStack/appcast.xml';
	        break;
	    default:
			$appcast_url = $base_url .'/'.$product_name.'/appcast.xml';
			break;	
	}
	

Install this file on a web server and configure the **SUFeedURL** in your stack plist to point to this file.

	<key>SUFeedURL</key>
	<string>http://domain.com/appcast.php</string>


Now if you are experienced with PHP, you can modify this to fit into your workflow and how you want the appcasts to be structured. This really supposed to just be a template to get you started with a decent workflow. 


## Logging


The PHP script will keep a logfile for you with all of the updates for you. The script will create a new logfile each month in order to keep the file size from growing too large. You can control the level of logging using the variables defined at the top of the script. 

	// Set these lines to FALSE if you do not want to log update attempts. 
	// log_updates => logs all good update attempts from Sparkle.
	// log_hacks => logs all attempts that are made outside of Sparkle (potential pirates)
	$log_updates = TRUE;
	$log_hacks = TRUE;
	$log_debug = FALSE;

Since ip address of your customers will be logged into these log files, I recommend you put the following into your **.htaccess** file so that the log file cannot be viewed from a web browser. 

	<Files ~ "appcast-.+\.(log)$">
	  order allow,deny
	  deny from all
	</Files>


If you wanted to get fancy you could create htaccess rules so that you could view the logfile from a browser but have it password protected.


## Sample appcast.xml


The appcast.xml file in this project is a template that you can use. This is the XML file that the PHP script will ultimately be pointing to. 


## Tip

I store all of my appcast files in my [Dropbox](http://db.tt/QWweJFv) public directory. Then I simply point the appcast.php to those publicly accessible files. This is very convenient since all I have to do is update files in my local machine and Dropbox takes care of uploading them all for me. 
