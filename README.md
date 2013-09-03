Simple Options Framework
=============

Simple Options Framework (SOF) was created to combine the best features of the most effective option frameworks available. The creation was fueled by the new requirements of the ThemeForest marketplace.

SOF is a back-end framework for creating and managing options inside WordPress themes. With SOF, you can focus on developing your theme, not a control panel. SOF comes bundled with plentiful of options that should serve most of the needs of any modern theme authors.

## Donate to the Framework
Should you feel pleased with Simple Options, consider donating to the developer. The time that has been put into this framework has been intense and I've given it away. Every contribution helps.

[![Donate to the framework](https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif "Donate to the framework")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3WQGEY4NSYE38)

## Downloading
### If you download the zip you get the development version. To get a stable version, please visit here and find the latest release: 
### [https://github.com/SimpleRain/SimpleOptions/releases](https://github.com/SimpleRain/SimpleOptions/releases)

## Try it out
You have two ways in which you can try the framework. First, you can try our demo site complete with an open admin login found at:
[http://simpleoptions.simplerain.com](http://simpleoptions.simplerain.com)

OR

Download the test theme and start tinkering locally. The test theme can be found here.
[https://github.com/SimpleRain/SimpleOptions-Test-Theme](https://github.com/SimpleRain/SimpleOptions-Test-Theme)

## Usage Examples
### Simple Options can be run in two configuration. The preferrable way is to run it as a plugin. We've baked automated updates into Simple Options so users will get all the benefits of improvements without theme admin's involvement. To use SOF as a plugin, do the following:

* Grab the latest release from here: [https://github.com/SimpleRain/SimpleOptions/releases](https://github.com/SimpleRain/SimpleOptions/releases)
* Install SOF as a plugin
* Copy options-init.php to your theme's root directory.
* Include the ```options-init.php``` file in your theme's ```functions.php``` file, like so:

```php
get_template_part('options', 'init');
```

### You can also run SOF outside of a plugin, embedded in a theme. By so doing users lose the automatic updates to the core files (and compliancy with marketplaces like ThemeForest). To do this:

* Grab the latest release from here: [https://github.com/SimpleRain/SimpleOptions/releases](https://github.com/SimpleRain/SimpleOptions/releases)
* Copy the `options` folder to the root directory of your theme.
* Copy options-init.php to your theme's root directory.
* Include the ```options-init.php``` file in your theme's ```functions.php``` file, like so:

```php
get_template_part('options', 'init');
```

You can place the `options` directory anywhere you want, but you will need to modify the include path within `options-init.php`.

**Please note if you embed the framework into your theme (not as a plugin) your users will depend on you to update the framework to recieve core updates. It is advisable to use SOF as a plugin.**


## Features <small>([view wiki](https://github.com/SimpleRain/SimpleOptions/wiki "View WIKI"))</small>
* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types
* Multple layout field types
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes
* Built in Validation Classes
* Easily extendable by creating Validation Classes
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Native Media Library Uploader
* Native WP Color Picker
* Drag and Drop Unlimited Slider Options
* Layout Manager
* Image radios w/ title & preset overrides
* Backup and Restore
* Advanced Google fonts with live preview
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
* <b>For commercial usage</b> (e.g in themes you're selling on any marketplace, or a commercial website), you are **strongly recommended** to link back to my [Themeforest Profile Page](http://themeforest.net/user/SimpleRain) using the following text: 

[Simple Options Framework](https://github.com/SimpleRain/SimpleOptions) by [SimpleRain](http://themeforest.net/user/SimpleRain)

### Contact the author

Twitter: [http://twitter.com/simplerain](http://twitter.com/simplerain)

Website: [http://simplerain.com](http://simplerain.com)


[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/48f6d17a19d5be95f6f825fc52ab0ddd "githalytics.com")](http://githalytics.com/SimpleRain/SimpleOptions)
