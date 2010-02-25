=== FireStats Charts ===
Contributors: mArm
Tags: firestats, statistics, charts, graph, stats, widget, admin
Requires at least: 2.9.0
Tested up to: 2.9.2
Stable tag: 1.0.4


This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard

== Description ==
<p>This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard</p>
<p><em>Sorry for my forgots the between updates 1.0.2 => 1.0.4</em></p>
<p><strong>Require FireStats >= 1.6.3</strong><br />
<strong>Not tested on WPMU</strong></p>

== Installation ==
1. Upload the folder `fscharts` to the `/wp-content/plugins/` directory
2. Set the rights on the folder `/fscharts/out` to 777 (output folder for charts images)
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to your dashboard to see the chart

== Frequently Asked Questions ==


== Screenshots ==

1. The widget on the dashboard (version 1.0.0)

== Changelog ==

= 1.1.0 =
* Added a plugin helper (inc/class.helper.php)
* Added setting page
* Added settings : chart width, chart height
* Added file res/styles/be-override.css
* Fixed some bugs
* Renamed plugin's main file to firestats-charts.php
* Added absUrl
* Removed flags *.dat files from jpGraph

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

