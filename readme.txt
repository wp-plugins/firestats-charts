=== FireStats Charts ===
Contributors: mArm
Tags: firestats, statistics, charts, graph, stats, widget, admin
Requires at least: 2.9.0
Tested up to: 3.0.3
Stable tag: 1.1.3


This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard

== Description ==
<p>This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard.<br />
It supports i18n</p>
<p><strong>Require FireStats >= 1.6.3</strong><br />
WPMU <strong>not supported</strong><br />
WordPress 3.0 Netword <strong>not supporter for the moment</strong> but will be planned.</p>

== Installation ==
1. Upload the folder `fscharts` to the `/wp-content/plugins/` directory
2. Set the rights on the folder `/fscharts/out` to 777 (output folder for charts images)
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to your dashboard to see the chart

== Frequently Asked Questions ==


== Screenshots ==

1. The widget on the dashboard (1.1.0)
2. The setting page (1.1.0)

== Changelog ==

= 1.1.3 =
* Added index.html file in directories
* Removed be-override.css
* Updated version number in class. Not showing right version on chart title
* Added cache expire setting (default: 3600)
* Fixed height not saved in settings page
* Completly 3.0.3 compatible

= 1.1.2 =
* Fixed dashboard graph size bug

= 1.1.1 =
* Added internationalisation (i18n) support via gettext
* Fixed some errors

= 1.1.0 =
* Added a plugin helper (inc/class.helper.php)
* Added a plugin renderer (inc/class.renderer.php)
* Added setting page
* Added settings : chart width, chart height
* Added file res/styles/be-override.css
* Fixed some bugs
* Renamed plugin's main file to firestats-charts.php
* Added absUrl
* Removed flags *.dat files from jpGraph
* Updated screenshots

= 1.0.4 =
* Fixed absolute hardcoded image link always pointing on ansermot.ch (thanks Alexander)
* Fixed chart image title
* Cleaned some code
* Fixed plugin's folder name in image path

= 1.0.3 =
* Updated version number in class
* Added temp file cleaning (should be in 1.0.2 but forgot to add the call to it)
* Moved rendering function inside the class
* Removed template tag

= 1.0.2 =
* Removed static graph image
* Added temp graph png generation
* Added temp directory file cleaning (older than 1 hour)

= 1.0.1 =
* Fixed missing FireStats
* Tested on 2.9.2 WP

= 1.0.0 =
* First version
* Simply display a chart on the dashboard with the hits of the last month
More feature in the next, comming soon, versions

