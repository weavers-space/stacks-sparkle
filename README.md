# Stacks Sparkle Appcast Tool


This utility is a great way to manage your Sparkle Updates for the Stacks Plugin for Rapidweaver. This script will take into effect the Stacks API Version and can serve up different appcast URLs based on that version. This script also verifies that the connection is coming from the Sparkle framework. This is so that no one can just open a browser and get to your updates.

### appcast.php
Now if you are experienced with PHP, you can modify this to fit into your workflow and how you want the appcasts to be structured. This really supposed to just be a template to get you started with a decent workflow.

This php integrates all of the required data to find the appcast file into the URL. In the example code, the URL is structured like the following. 

	http://domain.com/appcasts{{stacks_api}}/{{product_name}}/appcast.xml

#### Product Name
Obviously we have the product name in the URL. Not much to explain here... The product name will be the base name of the stack as you see it in Finder. 

#### Stacks API Version
The reason that the Stacks API is embedded in the URL is so that you can ensure that people running older versions of stacks do not get an update that will not work on their system. For example, if someone is running Stacks 2.5 with API v5, then you do not want them to get an update that would require API v6. 

On the flip side, you will also want to make sure that users running API v6 get all of the updates that run for all previous API versions. I accomplish this by copying the same exact appcast.xml file into all of the pertinent directories. For example, if I have an update that only requires API v4, I will also copy that same exact XML file into the API v5 and v6 directories. 

Using the Stacks API in the appcast URL definitely adds a level of complexity. If you feel that you want to keep things simple, you could just remove it from the base URL all together. Its all up to you!

###appcast-secret.php
You will notice that this files adds one more level of complexity to the URL scheme. You define a secret key that will be used to add an md5 hash string to the appcast URL. This makes it pretty impossible for someone to guess your URL scheme to updates. 

### Install on your server
Install this file on a web server and configure the **SUFeedURL** in your stack plist to point to this file.

	<key>SUFeedURL</key>
	<string>http://domain.com/appcast.php</string>


### Logging
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


### appcast.xml
The appcast.xml file in this project is a template that you can use. This is the XML file that the PHP script will ultimately be pointing to. I have my own shell scripts that dynamically generate these files for me. However, I know that a lot of developers out there use [Feeder for Mac](https://itunes.apple.com/us/app/feeder/id405949153?mt=12&at=11l8IQ) to manage their Sparkle feeds. I use it for my podcast feeds. 

### Storage
I store all of my appcast files on Amazon S3. Then I simply point the appcast.php to those publicly accessible files. I do this to reduce the load on my web server. You will need to make sure that all of your files are set to public read. 

There are a lot of easy scripting APIs to S3 if that is your thing. If you had some time and the PHP skills, you could pretty easily add the ability to authenticate with S3 and provide downloads to private files on S3. This would make it so that none of the updates were publicly accessible at all. 

[Transmit](https://itunes.apple.com/us/app/transmit/id403388562?mt=12&at=11l8IQ) is a great app that can mount your S3 buckets as local directories as well. This way you can interact with all of your files directly in Finder. 
