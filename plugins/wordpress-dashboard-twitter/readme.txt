=== WordPress Dashboard Twitter ===
Contributors: Alphawolf, ratterobert
Donate link:
Tags: twitter, tweet, wordpress, dashboard, widget, bitly, wpgd, shortener, oauth, retweet, timeline
Requires at least: 2.7
Tested up to: 3.0.1
Stable tag: trunk

The most creative name **WordPress Dashboard Twitter** in fact represents a Dashboard Widget for WordPress, that turns your Dashboard into a Twitter Client.

== Description ==

Twitter is everywhere. So why not in your WordPress Dashboard? WordPress Dashboard Twitter is a **Dashboard Widget** that displays Twitter @replies, direct messages, sent messages, Retweets, Friends Timeline and favorites the convenient way within your WordPress Dashboard. WordPress Dashboard Twitter turns your Dashboard into a **Twitter client**.

The Dashboard widget lets you update your status, send direct messages, follow your mentions and retweets, list direct messages, sent messages, your friends timeline and your favorites in a simple tab interface. All in a single widget. No seperate admin page needed. All the Twitter stuff you need right *where* you need it.

[vimeo http://vimeo.com/5734274]

**Note:** The plugin requires at least WordPress 2.7 and PHP 5 in order to run.

**At a glance:**

* Twitter OAuth authentication
* Adds a Twitter Client to your WordPress Dashboard only
* Display Mentions, Direct Messages, Sent messages, Retweets, Timeline and Favorites in a tabbed interface
* Reply to a Twitter status or Direct Message from within the Dashboard Widget
* No dedicated page in your WordPress admin panel
* All customization can be done through the Widget’s Configuration
* No impact on your blog’s frontend or other backend pages
* Shortening URLs with **wp.gd (new!)** or bit.ly
* One-Click-Image-Upload for TwitPic (currently disabled since version 1.0)
* Whenever you check your incoming links or WordPress News in the Dashboard, you can check your Twitter status as well

**Included languages:**

* English
* German (de_DE) (Thanks to Robert Pfotenhauer ;-))
* Italian (it_IT) (Thanks for contributing italian language goes to [Gianni Diurno](http://gidibao.net))
* Danish (da_DK) (Thanks for contributing danish language goes to [Georg S. Adamsen](http://wordpress.blogos.dk/))
* French (fr_FR) (Thanks for contributing french language goes to [Didier](http://www.wptrads.fr))
* Dutch (nl_NL) (Thanks for contributing dutch language goes to [Rene](http://wpwebshop.com/premium-wordpress-plugins/))

== Frequently Asked Questions ==

= Why isn't this or that implemented to improve the plugin interface? =

If you have suggestions please let us know by dropping us a line via e-mail or the wp.org forums.

= Where can I get more information? =

Please visit [the official website](http://wpdashboardtwitter.com/ "WordPress Dashboard Twitter") for the latest information on this plugin.

== Installation ==

1. Download the plugin and unzip it.
2. Upload the folder wp-dashboard-twitter/ to your /wp-content/plugins/ folder.
3. Activate the plugin from your WordPress admin panel.
4. Installation finished.

== Changelog ==

= 1.0.2 =
* NEW: Dutch localization added

= 1.0.1 =
* FIXED: Danish localization updated
* FIXED: Redeclaration error in OAuthException class

= 1.0 =
* ADDED: Switched from Basic Auth to Twitter OAuth authentication (doesn't require to save your credentials in your WP install)
* ADDED: Retweets Tab, Timeline Tab
* ADDED: French localization
* FIXED: New Twitter+OAuth lib + Code rewrite
* FIXED: Dashboard Widget can now be accessible by everyone (via settings)

= 0.8.8 =
* FIXED: Fixed a minor issue that made it so that you only had 139 characters to post (Thanks Marius for letting us know!)

= 0.8.7 =
* FIXED: Twitter avatars larger than 48x48 forced back to the regular format so they don't break the layout (Thanks smaakmakend for reporting!)

= 0.8.6 =
* FIXED: JS and CSS files won't be included in index.php pages other than dashboard only anymore
* FIXED: Removed references to images that don't exist (anymore) in tabs.style.css
* FIXED: Added a check if Twitter is available
* FIXED: Added request and response timeouts to all CURL operations

= 0.8.5 =
* FIXED: Damn you guys at tr.im! - re-integrated tr.im URL shortener

= 0.8.4 =
* FIXED: tr.im discontinued service thus it has been removed
* NEW: Added bit.ly URL shortener

= 0.8.3 =
* FIXED: passwords are now stored encrypted
* FIXED: incorrect link in the sent panel

= 0.8.2 =
* NEW: Verifying credentials on options panel
* FIXED: issue with localization on AJAX loading (thanks for testing, [Gianni Diurno](http://gidibao.net)!)
* FIXED: CSS, JS, PHP code & security improvements

= 0.8 =
* initial version

== Other Notes ==

= Video Demo =

[vimeo http://vimeo.com/5734274]

= Licence =

This plugins is released under the GPL, you can use it free of charge on your personal or commercial blog.

= Introducing a new URL shortening service - wp.gd =

WordPress Dashboard Twitter comes with its own URL Shortener Service **wp.gd**! It's not public, but you can use it in WordPress Dashboard Twitter as much as you like. If - for whatever reason - you don't like wp.gd, just switch to tr.im in the options.

= Acknowledgements =

* Thanks to [Justin Poliey](http://justinpoliey.com/ "Justin Poliey") for a nice Twitter Class
* Thanks to [Timothy Groves](http://www.brandspankingnew.net/ "Timothy Groves") for the the nice Mini Icons 2 series
* Thanks to all the beta testers ;-)

== Screenshots ==

1. OAuth authentication
1. The tabbed interface
1. The status update form
1. The status update form, changed submit button label when sending a DM
1. The options panel