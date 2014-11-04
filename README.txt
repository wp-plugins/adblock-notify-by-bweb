=== Adblock Notify by b*web ===
Contributors: brikou
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8
Tags:  adblock, page redirect, cookies, notify, modal box, dashboard widget, ads, notification, adBlocker, Responsive
Requires at least: 3.7
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Detect Adblock and nofity users. Simple plugin with get around options and a lot of settings. Dashboard widget with counter & statistics included!

== Description ==

Detect Adblock and nofity users. Help to block adblock (eg: Adblock Plus) and increase your ad revenue.
Adblock Notify is a very easy to use plugin with get around options and a lot of settings. A smart dashboard widget with counter & statistics is included!

= Plugin Capabilities =

* Detect adBlocker (eg Adblock Plus) by three check methods
* Custom notification message with jQuery Popup ([Reveal by ZURB](http://zurb.com/playground/reveal-modal-plugin)) or Javascript redirect
* Replace blocked ads by custom message
* Three available options to notify your users
* Help you increase your ads income with a passive approach
* Responsive design friendly
* Enqueue scripts & CSS files only when necessary
* Fully integrated in your theme design
* User Friendly
* Many design options & custom CSS available
* Smooth admin panel for an easy and fast setup (thanks to [Titan Framework](http://www.titanframework.net/))
* Statistics on you WordPress Dashboard
* Follow WordPress best practices
* Support for all kind of ads, included asynchronous
* Support Images and shortcodes (eg: [PayPal button](https://www.paypal.com/us/cgi-bin/?cmd=_donate-intro-outside/))
* Use cookie for a better user UI
* Cross browser detection
* Remove settings from database on plugin uninstall
* Multilanguage support on the admin pages (EN & FR are currently available)

[CHECK OUT THE DEMO](http://b-website.com/adblock-notify-plugin-for-WordPress "Try It!")


Please ask for help or report bugs if something goes wrong. It is the best way to make the community go ahead !



= WordPress requirement =

* WordPress 3.7+ (not tested on above versions, but may works)


= Notice =

* May not work properly with cache system (depend on parameters)
* Need your user to have Javascript activated (no js option included)

= How to use it (full details) =
You can notify users with an activated Adblocker software by one of THREE ways !
* A pretty cool and lightweight Modal Box with a custom content : **the COMPLIANT solution**
* A simple redirection to the page of your choice : **the AGRESSIVE solution**
* A custom alternative message where your hidden ads would normally appear : **the TRANSPARENT solution**
	
Only one of the two first options can be activated at the same time. The third one is standalone and can be setting up independently. 
You can easily switch between them without losing your options.

Adblock Notify nativally uses cookies for a better user experience and a less intrusive browsing of your site. It means visitors will see the Modal Box only once or be redirected to your custom page once. 
You can deactivate them, however if your visitor has an activated adblocker software they will see a modal box or get a redirection on every visited page.

Adblock Notify Stats widget is available on your admin dashboard (if not visible, go to the top menu and visit "Screen Options").


Alternative Message:
You can insert a custom message where your hidden ads would normally appear by clonning orignal ads div containers.
Note: Some minimal HTML knowledge is required to set up this functionality.

What does "Clone ad container" mean? 
It means you can ask Adblock Notify Plugin to copy the CSS properties of the element that contains your ad to a new element which will not be hidden by an adblocker software. With this process, your design should not break. 
The new element will be the same type (DIV,SPAN,etc.) as its source, and will have the .an-alternative class.
	
Available options are:
Custom Mode: Will try to catch all the CSS rules defined in your theme files, and let you choose which ones to keep (see Custom Mode CSS properties).
Soft Mode (Recommended): Will try to catch all the CSS rules defined in your theme files, and add them to the new created element. If the browser does not support this feature, it will try Hard Mode fetching.
Hard Mode: Will try to fetch all the elements CSS rules based on browser CSS compilation (not reading directly in your CSS files). This option may add a lot of inline CSS rules to your newly created element.
	
This feature is performed through Javascript (+jQuery) and is 95% functional on all modern browser even on IE8+. For the 5% left, the plugin will drop potential JS errors and insert .an-alternative div. 
Tested and works great on Chrome, Firefox, Safari, Opera, IE8+

What's appended if I don't turn on this option? 
The plugin will append a new "clean" DIV element with .an-alternative class just before the advert container. You can add your own custom rules with the Custom CSS field.


Adblock Notify Stats Widget (WordPress Dashboard Widget) :
This widget display total pages views and total pages views with an adblocker.
It display the data within cool charts and gives you the past week history.


Available options:
- Main options

* Cookies Options
	* Cookies activation
	* Cookies Lifetime (Days)
	
* Modal Box Options
	* Modal Title
	* Modal Text
		
* Redirection Options
	* Target Page
	
* No JS Redirection
	* Redirect if no JS detected?
	* Target Page


- Modal Visual Options	
	
* Modal Box Settings
	* Modal Box effect
	* Animation Speed (Milliseconds)
	* Modal Close on background click
	
* Modal Box Style
	* Overlay Color (Background)
	* Overlay Opacity (%)
	* Modal Box Background Color
	* Modal Box Title Color
	* Modal Box Text Color
	* Custom CSS (Advance users)


- Alternative message

* Required Settings
	* Advert containers (Comma separated)
	* Alternative Text
	
* Optional Settings	
	* Clone ad container?
	* Custom Mode CSS properties (Comma separated)
	* Custom CSS (Advance users)

== Installation ==
1. Upload and activate the plugin (or install it through the WP admin console)
2. Click on the "Adblock Notify" menu
3. Follow instructions, every option is documented ;)	

== Frequently Asked Questions ==

= Is it working with Google Adsense Ads? =
Yes, and probably with all kinf of content hidden by an adblocker software.

= Is it compatible with caching systeme =
Yes it is, but you have to exclude "advertisement.js" from the cache files list.

= The plugin is activated and setting up, but nothing append. =
Please, inspect your page and search for an_admin_scripts.js or advertisement.js.
If they are not visible on your page DOM, there is probably a problem with your caching/minify plugin.
Purge all cache and rebuild your minify, then check again.
You can also try to open a new private tab to have a new "clean" test environment.


== Screenshots ==
1. Modal box notification
2. Plugin admin page
3. Statistics on the WordPress Dashboard

== Changelog ==
= 1.0 =
* First release
* Minor PHP improvements
* Update readme.txt

= 0.2 =
* Admin page style enhancement.
* Change the way Titan Framework is embeded
* Translatable ready (add French translation)
* Improve widget counter function
* Some minore php fixing.

= 0.1 =
* First stable version.


== Upgrade Notice ==
= 0.2 =
* Please deactivate then reactivate if admin title is missing.

= 0.1 =
* First stable version.

