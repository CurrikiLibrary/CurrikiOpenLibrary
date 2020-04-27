=== Better RSS Widget ===
Contributors: grandslambert
Donate link: http://grandslambert.net/plugin/better-rss-widget/
Tags: rss, link, list, target, feed, syndicate, shortcode, posts, page
Requires at least: 2.5
Tested up to: 4.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replacement for the built in RSS widget that adds an optional link target, shortcode, and page conditionals.

== Description ==

Replacement for the built in RSS widget that adds an optional link target, shortcode, and page conditionals.

= Usage Notice =

This plugin does not, and cannot replace the existing RSS Widget. Once you install, activate, and configure this plugin you must go to your Widgets page and either replace existing RSS widgets with the new widget, or add new Better RSS Widgets.

= Features =

* Add title or post tags to the feed URL in the shortcode.
* Excerpt length can be set in both the widget and the shortcode.
* Limit the number of characters to display for the title of the post.
* Add text or html before the list of posts on widgets.
* Shortcode to allow embedding RSS feeds into posts and pages.
* Choose whether to cache the RSS feed and set the cache duration per feed, defaults to 3600 seconds (1 hour).
* Option to display the post time.
* Allows multiple widgets each with different settings.
* Adds the link target to both the RSS Title and all articles.
* Default settings screen allows you to decide what defaults will appear when adding a new widget.

= Languages =

This plugin includes the following translations. Translations listed with no translator were created by the plugin developer using Google Translate. If you can improve these, you can get your name listed here!

* English
* Spanish
* Italian
* German
* French

== Installation ==

1. Upload `better-rss-widget` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the defaults on the options menu screen.
4. Add new "Better RSS Widgets" or replace existing "RSS" widgets to use the new settings.

== Changelog ==

= 2.7.1 - January 11th, 2015 =

* Emergency release to fix missing widgets.

= 2.7.0 - January 10th, 2016 =

* Tested and made enhancements to work with the latest version of WordPress (4.4.1)
* Added option to change the HTML tags used for the list.
* Fixed link to support to use the WordPress support area.
* Removed the "Display this widget on" feature. Please use JetPack's Widget Visibility feature for this.

= 2.6.1 - December 28th, 2012 =

* Quick fix to properly display the list of contributors.

= 2.6 - December 28th, 2012 =

* Updated links in preparation for new features.
* Fixed errors in language file so all text can be translated.
* Added the ability to include a short description before the list.

= 2.5 - March 1st, 2011 =

* Changed the language on the widget form to make the page selection more clear.
* Defaulted all display settings to checked for easier installs.
* Fixed a bug that did not keep all settings set when saving widget settings.

= 2.4 - February 27th, 2011 =

* Changed the plugin options to use an object for cleaner code.
* Turned on the RSS Cache by default. Can be turned off in settings.
* Added default options in settings to the shortcode display - now follows your settings.
* Fixed an error where the link target setting was not used in the shortcode.
* Shortcode now uses the cache if it is enable in the plugin settings.
* Added an option on the administration tab to search for instances of shortcode in all post types.
* Added language translations for Spanish, French, German, and Italian.

= 2.3 - February 24th, 2011 =

* Changed some code to work with the newly released WordPress 3.1 version.
* Removed some error code during the plugin activation sequence.
* Fixed a couple errors in the langauge settings.
* Fixed the reset and save settings tabs so they do not conflict with other plugins.

= 2.2 - February 15th, 2011 =

* Fixed an issue where the link target setting was not working.
* Fixed an issue on the widget form that prevented some settings from being unset.
* Fixed an issue where the feed title was not used with the form title was left blank.

= 2.1 - February 11th, 2011 =

* Cleaned up code to remove extra stuff and reduce load.
* Updated the settings screen to use my standard tabs.
* Fixed a bug that would not allow you to set the target of links to none.
* Fixed a bug that would force default settings over widget settings.
* Added an option to link the RSS Icon to either the RSS URL or the URL entered for the title of the widget.
* Added an option to not link the title of the widget.
* Adjusted the widget form to reduce the height.

= 2.0 - July 22nd, 2010 =

* Fixed a bug that caused IE8 to display all text after a feed as a link.
* Fixed broken links on the settings page.
* Fixed a few text entries to allow full language translation.

= 1.9 - June 3rd, 2010 =

* Added the ability to set the number of words for item descriptions.
* Added option to set the link URL for the feed title.
* Added option in sidebar widget to hide the feed icon.

= 1.8.1 - May 11th, 2010 =

* Fixing a bug created by adding the features in 1.8.

= 1.8 - May 11th, 2010 =

* Added option for shortcode to use title or tags in the feed URL.

= 1.7 - May 11th, 2010 =

* Fixed some code issues that were causing problems in 2.9.2.
* Added fields to make it easier to translate the plugin.

= 1.6 - December 18th, 2009 =

* Fixed a bug where the widget defaults were overridding the instance settings.

= 1.5 - December 18th, 2009 =

* Added two new global options, excerpt length and exerpt prefix.
* I really SHOULD look at all the suggestions when doing upgrades. :)

= 1.4 - December 17th, 2009 =

* Added option to add a nofollow tag to all links.
* Converted the options to save in one record instead of multiple records.
* Code cleanup and optimization.

= 1.3 - December 17th, 2009 =

* Removed a line of debug code that displayed with the shortcode.

= 1.2 - December 17th, 2009 =

* Removed code that caused the plugin to cause fatal errors on PHP4 servers.

= 1.1 - December 17th, 2009 =

* Fixed a bug so the plugin works in Wordpress MU.
* Added an option to the shortcode to display only the content, or the title if the content is blank.

= 1.0 - December 11th, 2009 =

* Added conditionals to the widget to limit display to certain page types.

= 0.9 - November 21st, 2009 =

* Added a shortcode to allow embedding RSS feeds into posts and pages.

= 0.8.5 - October 27th, 2009 =

* Removed some debugging code left during the 0.8 upgrade.

= 0.8 - October 26th, 2009 =

* Added option to disable the feed cache (enabled by default).
* Change the default cache duration from 12 hours, set by Wordpress, to 1 hour.
* Added option to set the cache duration globally and per widget.
* Added option to display the time for items.
* Fixed a bug where the default settings were not being used in the widget form.

= 0.7 - October 24th, 2009 =

* Fixed an error that prevent the widget form appearing on servers running PHP4.
* Cleaned up the code and fixed a security issue.
* Added a screenshot of the new screen.

= 0.6 - October 16th, 2009 =

* Fixed a bug that caused a problem on older versions of RSS feeds.

= 0.5 - October 14th, 2009 =

* First release

== Upgrade Notice ==

= 2.7.1 =
Emergency maintenance release to fix missing widgets. No changes needed.

= 2.7.0 =
If you are using the option to display a widget on specific pages, you must update your widgets as this feature has been removed.

= 2.6.1 =
Maintenance release.

= 2.6 =
Fixes a language issue and adds the ability to include text before links.

= 2.5 =
Fixes issues with the form and checkbox defaults.

= 2.4 =
Fixes the shortcode to use target and cache.

= 2.3 =
Fixes issues in the settings page so reset and save work properly.

= 2.1 =
Code cleanup - nothing new so not required.

= 2.0 =
Fixes bugs that cause issues in Internet Explorer 8.

= 1.9 =
Great new features to customize your feed.

= 1.6 =
Upgrade IMMEDIATELY - version 1.5 is broken.

= 1.4 =
Not required, just adds new features.

= 1.3 =
Required update to remove bug testing output on your site.

= 1.2 =
Required update if running Wordpress on a PHP4 server.

= 1.1 =
This version fixes a bug that prevents saving plugin settings on Wordpress MU.

== Frequently Asked Questions ==

= Why this plugin when there is already an RSS Widget? =

At the time this plugin was created (Wordpress version 2.8.4), the built in widget did not have an option to force RSS links to open in a new window. The only way to do this was to hack into the core code of Wordpress, which is not recommended. It is hoped that eventually Wordpress will simply add this option to their widget, but until then, this becomes a quick and easy solution.

= Where can I get support? =

http://wordpress.org/support/plugin/better-rss-widget

== Screenshots ==

1. Sample output for the sidebar.
2. The form for setting up the widget.
3. Cache Settings page.
4. Page Settings where you can select the default pages to show the widget on.
5. Plugin settings page.
6. Sample output from the shortcode with date and summary.