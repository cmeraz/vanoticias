=== myEASYhider ===
Contributors: camaleo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CVTUG224XFA7U
Tags: myeasy, hide, dashboard, update, admin, administration, ajax, comments, google, facebook, image, images, links, jquery, plugin, plugins, post, posts, rss, seo, sidebar, social, twitter, video, widget, wordpress, youtube
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.0.6

Easily hide parts of your administration page.

== Description ==
myEASYhider let's you remove almost any item from the Administration pages.

Check out the [myEASYbackup for WordPress video](http://www.youtube.com/watch?v=raXH2QiVv60):

http://www.youtube.com/watch?v=raXH2QiVv60&hd=1

This plugin relies only on core WordPress capabilities. It was created to hide items to users independently from their assigned role as it let's you decide who will or will not see the items based on his user name.

You can choose the items to remove, for example the Tools and the Plugins menu, from a list of predefined items.
Additionally you can add your customized items.

Initially created for the marketing agency I am actually working, this plugin was needed to fix a bug we found were WordPress under unknown circustances keep asking to update its version to 2.9.2 while reporting that version 2.9.2 is installed.
Moreover the agency needed to give "limited capabilities" to customers having administration privileges; Customers in fact, needs to be independent when its time to add a new user.
At the same time, the agency wanted to avoid their customers "playing around" with the installed plugins, themes, tools and settings to reduce as much as they can urgent (and un-needed!) support requests.

Now available to everyone willing to keep the Administration pages for their customers as "simple" as possible.

Related Links:

* <a href="http://myeasywp.com/" title="myEASYwp: WordPress plugins created to make your life easier">myEASYwp plugin series homepage</a>
* myEASYhider is the perfect companion to <a href="http://myeasywp.com/plugins/myeasywebally/" target="_blank">myEASYwebally</a> and <a href="http://myeasywp.com/plugins/myeasybackup/" target="_blank">myEASYbackup</a>, two other plugins in the myEASY serie.
* Buy <a href="http://wpplugins.com/plugin/124/myeasyhider-pro/" title="myEASYhider PRO: get the Professional version">myEASYhider PRO</a> to fully customize your admin header and footer!
* For the latest news about the myEASY plugin serie follow me on <a href="http://twitter.com/camaleo" target="_blank" title="myEASY plugins news">Twitter</a>

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the full directory into your `wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in the WordPress Administration page
1. Open the plugin tools page, which is located under `Settings -> myEASYhider` and let the plugin work for you.

== Frequently Asked Questions ==

= How can I customize the administration page look? =

<a href="http://myeasywp.com/plugins/myeasyhider/myeasyhider-pro/" title="Visit the myEASYhider PRO page">myEASYhider PRO</a> let you customize the administration page header and footer.


== Screenshots ==

1. The myEASYhider interface allows you to easily define which parts of the Administration page hide
2. Example of an administrator seeing a partially hidden page

== Changelog ==

= 1.0.6 (13 November 2010) =
Changed:

* Changed the <a href="http://eepurl.com/bt8f1" target="_blank">newsletter provider</a> as the previous one is going to close his service by the end of 2010.

= 1.0.5 (2 September 2010) =
Fixed:

* Changed the option name used to show/hide the plugin credits to avoid duplicates when using more than one plugin in the myEASY series.

Added:

* Tool to remove ALL the plugin settings as it happened one user hided himself and was not able to administer his blog anymore. For usage instructions please see the /wp-content/plugins/myeasyhider/myeasyhider-reset file.

= 1.0.4 (23 August 2010) =
Added:

* Ability to customize what each user will be able to see.

Fixed:

* WordPress 3 compatibility: header and footer background color is now working.

= 1.0.3 (unpublished) =
Fixed:

* The entire code is executed only when its called from the administration pages.

= 1.0.2 (15 May 2010) =
Fixed:

* Automatically sanitize the custom items; now it is not possible to enter spaces and carriage returns anymore.

= 1.0.1 (12 May 2010) =
Fixed:

* Refreshing the settings page by clicking on the url field and then on the go button does not loose the "What will be hidden" values anymore.

= 1.0.0 (9 May 2010) =
The first release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release.
