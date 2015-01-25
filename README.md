Hobbes Syncs
===========
This plugin syncs post content between in custom post types between two sites. It uses the WordPress REST API, and the Pods JSON API. In the future I'd like to make using Pods optional, but for now, it fits its intended purpose.

<p style="display:inline-block;text-align:center;"><img src="https://raw.githubusercontent.com/Shelob9/hobbes_syncs/hobbes.png"  /></p>


### Requirements
* [WP-API](https://wordpress.org/plugins/json-rest-api/) 1.1.1 or newer
* [PHP](http://php.net/) 5.3 or newer, though I wouldn't, in general recommending running WordPress on anything older than PHP 5.4.
* [WordPress](http://wordpress.org/) 4.1 or newer
* [Pods Framework](http://Pods.io) 2.5.1 or newer
* [Pods JSON API](https://github.com/pods-framework/pods-json-api) 0.2.3 or newer


### Set Up
* Make sure that all sites meet the above requirements.
* On each site, go to the "Hobbes Syncs" admin page, which is under the settings menu.
* Click the "Post Types" tab and select the post types you wish to sync and then save changes.
* Go to the "Remote Sites" tab and click the "Generate Keys" button.
* Now for each site, you must <em>on all other sites you wish to sync to</em>:
    * Under the "Remote Sites" tab, click the "Add New Site Button"
    * Enter the information from the other site(s) from keys and JSON URL.
    * Also give the remote site a name
    * Click save.
    
### License, Copyright etc.
Copyright 2014-2015 [Josh Pollock](http://JoshPress.net) & [Hobbes Jayourba-Pollock](https://raw.githubusercontent.com/Shelob9/hobbes_syncs/hobbes.png).

Licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/gpl-2.0.html) or later. Please share with your neighbor.
    
   
