=== Plugin Name ===
Contributors: mArm
Tags: firestats, statistics, charts, graph, stats, admin, widget
Requires at least: 2.9.0
Tested up to: 2.9.2
Stable tag: 1.0.1


This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard

== Description ==
This plugin adds a graphical chart of the FireStats statistics plugin on the admin dashboard

Take a look on the changelog for the incomming features

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
* Moved the widget install method inside the class plugin [FsCharts->installWidget()]
* Fixed plugin install method [FsCharts->install()]
* Added widget configuration panel
* Added fscharts/res/css/be.css for styling the BE widget
* Config panel AJAX animation (No-JS compliant)
* Updated css/be.css
* Updated scripts/beeffect.js
* Added check of FireStats Installation

= 1.0.0 =
* First version
* Simply display a chart on the dashboard with the hits of the last month
More feature in the next, comming soon, versions

= Roadmap 1.1.0 =
* l18n
* l10n
* Widget configuration

= Roadmap 1.2.0 =
* Bar chart
* Pie chart

= Roadmap 1.3.0 =
* 24h hits
* 24h unique hits
* 24h visits
* 24h unique visits

= Roadmap 1.4.0 =
* Wigdet tabs layout
* Browsers & OS tab

