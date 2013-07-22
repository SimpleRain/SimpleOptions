Simple Options Framework
=============

Simple Options Framework (SOF) was created to combine the best features of some of the most effective features on the market. Also motivated by the changes to the marketplace put forth by ThemeForest.

SOF is a back-end framework for creating and managing options inside WordPress themes. It cuts off the process of developing your own admin framework and give you more time to actually focus on building your theme. SOF comes bundled with plentiful of options that should serve most of the needs of any modern theme authors.

The main feature of SMOF is its intuitive, user-friendly interface that aims to look as native as possible to WordPress. Additionally, it comes loaded with some awesome features that makes it even more usable to you as a theme author. 

## Usage Examples
Simple Options can be run in two configuration. The preferrable way is to run it as a plugin. We're baking automated updates into Simple Options so users will get all the benefits of improvements without admins. If this is your preferred method, do the following:
* Install the downloaded zip as a plugin
* Copy options-init.php inside your theme.
* Include the ```options-init.php``` file in your theme's ```functions.php``` file, like so:

```php
get_template_part('options', 'init');
```

The other option is to embed the entire framework inside your theme. In which case you:
* Place everything in your root theme directory
* Delete the simpleoptions.php file (that's plugin specific)
* Again, initialize the framework in the same method as above by including the ```options-init.php``` file in your themes ```functions.php``` file, like so:

```php
get_template_part('options', 'init');
```

Then change the settings as written in the ```options-init.php``` file.

## Features 
### Working on outlining this proper. Please be patient -dovy
* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Multple layout field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes (more info in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Built in Validation Classes: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki title="View WIKI")
* Easily extendable by creating Validation Classes (more in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Native Media Library Uploader
* Native WP Color Picker
* Drag and Drop Unlimited Slider Options
* Layout Manager
* Tiles
* Backup and Restore
* Keeps the site URL out of the database
* Google fonts with live preview
* Jquery UI slider
* ...and much more(including base elements inputs, textarea, etc.)

## Credits
SOF is a fork of the following option frameworks. Each have their own credits outlined on their project pages.

* [Slightly Modded Options Framework v1.5.2](https://github.com/sy4mil/Options-Framework)
	* Used because it has a very complete UI and widgets.
* [NHP Theme Options v1.0.6](https://github.com/leemason/NHP-Theme-Options-Framework)
	* Used because of excellent PHP development and hook usage.

### License

SOF is released under GPLv3 - [http://www.gnu.org/copyleft/gpl.html](http://www.gnu.org/copyleft/gpl.html). You are free to redistribute & modify copies of the plugin under the following conditions:

* All links & credits must be kept intact
* For commercial usage (e.g in themes you're selling on any marketplace, or a commercial website), you are **strongly recommended** to link back to my [Themeforest Profile Page](http://themeforest.net/user/SimpleRain) using the following text: [Simple Options Framework](https://github.com/SimpleRain/SimpleOptions) by [SimpleRain](http://themeforest.net/user/SimpleRain)

### Contacts

Twitter: http://twitter.com/simplerain

Website: http://simplerain.com