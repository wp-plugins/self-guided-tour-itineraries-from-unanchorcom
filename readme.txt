=== Self Guided Tour Itineraries from Unanchor.com (Widget) ===

Contributors: Mohammad
Donate link: http://www.unanchor.com/
Tags: widget, travel, self guided tour itineraries, travel planning, sidebar, cache, travel guide, travel itinerary, trip planning
Requires at least: 2.0.0
Tested up to: 3.2.1
Stable tag: trunk

Customizable sidebar widget: displays user's self-guided tour itineraries from Unanchor.com or just the latest self-guided tour itineraries.


== Description ==

= Tour Itineraries with Thumbnails! =

This Wordpress plugin installs a **new sidebar widget** that can display [self-guided tour itineraries](http://www.unanchor.com/ "Self-Guided Tour Itineraries from Unanchor.com") from the [Unanchor.com](http://www.unanchor.com/ "Self-Guided Tour Itineraries from Unanchor.com") website. 

The plugin also provides a widget control panel to control the number of itineraries displayed, the username of the unanchor writer, as well as the title of the widget itself.

Itinerary details are retreived from the Unanchor API and cached in wordpress to maximize speed - the cache is renewed every 24 hours.

You can use the provided additional stylesheet, or customize your own.

If you dont use widgets or sidebars, you can also include the itineraries in the content of any page or post of your blog, by simply using the `<?php display_SGTI() ?>` function.


= Active Support =

This is an official **Unanchor** Wordpress plugin - this means you can contact the team at wp@unanchor.com and we will try to help you resolve any issues with the plugin.


== Installation ==

1. Unzip self-guided-tour-itineraries-from-unanchorcom.zip
1. Upload the `self-guided-tour-itineraries-from-unanchorcom` directory and all its contents into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the widgets admin page to add the widget to one of your sidebars and configure it

If you don't like the widget or don't use sidebars, you can also include the itineraries in the content of any page or post of your blog, by simply using the `<?php display_SGTI() ?>` function. The same options can be given to this function to override the defaults, for example:

`<?php

$options = array('title' => 'My Itineraries', 'num_display' => 3, 'unanchor_username' => 'jonny');

display_SGTI($options);

?>`


== Frequently Asked Questions ==

= Where should I ask questions? =

Any questions and comments regarding this plugin can be sent to wp@unanchor.com


= I made some changes but they do not show up on the site... =

Itineraries are cached for 24 hours to increase performance. If you want to make changes appear right away, go into the widget control panel and click **Save Changes** - this will clear the cache.



== Screenshots ==

1. An example of the sidebar widget in action

2. The widget control pannel



== Widget control pannel ==

The widget has its own control pannel for setting up your unanchor username, the number of itineraries to display, and what the title of your widget should be. You can administer it from the widgets admin page.



== Upgrade Notice ==

= 3.0.1 =

* To upgrade, completely removing the files of the previous version before installing the new version.



== Changelog ==

= 1.1 =
* Initial release

= 1.2 =
* Added ability to show itineraries in a template without using a widget
