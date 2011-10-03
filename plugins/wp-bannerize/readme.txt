 === WP Bannerize ===
Contributors: Giovambattista Fazioli (see Thanks for contributors)
Donate link: http://www.saidmade.com
Tags: Banner, Manage, Image, ADV, Random, Adobe Flash
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 2.6.11

WP Bannerize, banner-image manager.

== Description ==

WP Bannerize is an Amazing Banner Image Manager. In your template insert: `<?php wp_bannerize(); ?>`, use new shortcode featured or set it like Widget

**FEATURES**

* Localized for Italian, English, Spanish, Portuguese and Belorussian
* Create your list (group/key) Banners image/Adobe Flash movie
* Show your banners list by php code, **shortcode** or Widget
* Set random, limit and catories filters
* Standard Wordpress interface improvement
* "nofollow" support
* Click Counter engine for stats
* Wordpress Admin Contextual HELP
* Wordpress MU compatible

**LAST IMPROVE**

* Add Wordpress shortcode [wp-bannerize]
* Add Spanish localization
* Change access level to 'Editor'

**HOW TO**

When you insert a banner you can give a group (key) code. In this way you can "group" a block of banners. For examples if your theme is a 3 columns, you can put in left sidebar:

`<?php wp_bannerize('group=left_sidebar'); ?>`

and put in right sidebar:

`<?php wp_bannerize('group=right_sidebar'); ?>`

However wp-bannerize provides a filter by category, for example:

`<?php wp_bannerize('group=right_sidebar&categories=13,14'); ?>`

The code above shows only banners in the categories 13 or 14, for the "right_sidebar" group key.

or (new from 2.6.0) in your post:

`[wp-bannerize group="adv" random="1" limit="3"]`


**params:**

`
* group               If '' show all groups, else show the selected group code (default '')
* container_before    Main tag container open (default <ul>)
* container_after     Main tag container close (default </ul>)
* before              Before tag banner open (default <li %alt%>) see alt_class below
* after               After tag banner close (default </li>) 
* random              Show random banner sequence (default '')
* categories          Category ID separated by commas. (default '')
* alt_class           class alternate for "before" TAG (use before param)
* link_class          Additional class for link TAG A
* limit               Limit rows number (default '' - show all rows) 
`

= Related Links =

* [Saidmade](http://www.saidmade.com/ "More Wordpress Plugins")
* [Undolog](http://www.undolog.com/ "Author's Web")
* [Saidmade Blog](http://www.saidmade.com/category/blog "Saidmade Blog")
* [Tutorial Video](http://www.youtube.com/watch?v=sAZOyAwXu-U "Tutorial Video")

For more information on the roadmap for future improvements please e-mail: g.fazioli@saidmade.com

== Screenshots ==

1. New Logo
2. New Administrator Menu
3. Add New Banner Pannel with Adobe FLash support and size autodetect
4. New List View Banner list with Wordress standard tools: pagination, filters, drag & drop features
5. Contextual Help
6. Widget support

* [Tutorial Video](http://www.youtube.com/watch?v=sAZOyAwXu-U "Tutorial Video")

== Changelog ==

= 2.6.11 =
* Fix bugs on combo menu in admin
* Fix "Parse error: syntax error, unexpected T_OBJECT_OPERATOR"
* Update Portuguese localization

= 2.6.9 =
* Fix wrong use `alt class` in Widget setting [Detail](http://wordpress.org/support/topic/plugin-wp-bannerize-before-and-after-containers-not-working-properly?replies=6#post-1718323 "Detail")

= 2.6.8 =
* Fix class attribute with `group` replace space with underscore

= 2.6.7 =
* Improve HTML output for W3C validation
* Fix documentation

= 2.6.6 =
* Fix online edit form for `clickcount`, `width` and `height` parameters
* Improve online edit form size for `clickcount`, `width` and `height` parameters

= 2.6.5 =
* Fix wrong "height" value in edit online form
* Add `width`and `height` attributes in `img` tag
* Set `nofollow` and `_blank` properties like default

= 2.6.0 =
* Add shortcode [wp-bannerize]
* Add Spanish localization: thanks to [David Pérez](http://www.closemarketing.net/ "Closemarketing")
* Change access level to 'Editor'
* Fix default value in database table

= 2.5.5 =
* Fix "echo" on Widget output

= 2.5.4 =
* Fix getimagesize() functions on url file

= 2.5.3 =
* Fix getimagesize() functions

= 2.5.2 =
* Fix italian localization

= 2.5.1 =
* Rev some bugs

= 2.5.0 =
* Improve Database table
* Add convertion tools for pre-2.5.0 release
* Improve User Interface
* Add Adobe Flash support
* Add footer text description
* Add "nofollow" rel attribute support
* Add "Click Counter" (only images)
* Rev code
* Fix minor bugs

= 2.4.11 =
* Fix Link on Plugins list page

= 2.4.9 =
* Change Menu Item position in Backend
* Improve styles and script loading
* Improve "edit" online styles and table views


= 2.4.7 =
* Fix warning while check previous version
* Rev code/comment

= 2.4.6 =
* Add Belorussian Localization; thanks to [Marcis G.](http://pc.de/ "Marcis G.")

= 2.4.5 =
* Add Secure Layer on Ajax Gateway

= 2.4.4 =
* Minor revision on localization

= 2.4.3 =
* Fix Widget Title Output
* Change Adv Engine

= 2.4.1 =
* Fix localization
* Fix minor bugs

= 2.4.0 =
* Add localization
* Improve code restyle
* Minor rev code

= 2.3.9 =
* Fix Widgets args

= 2.3.8 =
* Rev include script and style
* Minor rev path and code clean

= 2.3.5 =
* Rev Widget Class implement

= 2.3.4 =
* Revision readme.txt

= 2.3.3 =
* Split Widget code
* Fix "random" select on Widget

= 2.3.2 =
* Add "alt" class in HTML output
* Add additional class for link TAG A
* Fix Widget output

= 2.3.0 =
* Add Wordpress Categories Filter - Show Banner Group for Categories ID
* Improve admin

= 2.2.2 =
* Fix minor bugs + prepare major release

= 2.2.1 =
* Fix to Wordpress MU compatibilities
* Fix minor bugs

= 2.2.0 =
* Add Widget support
* Fix compatibility with Wordpress 2.8.6

= 2.1.0 =
* Add thickbox support for preview thumbnail
* Resize key field to 128 chars
* Minor Fix

= 2.0.8 =
* Minor Fix, improve admin

= 2.0.3 =
* Minor Fix

= 2.0.2 =
* Add random option
* Fix minor bugs

= 2.0.1 =
* Fix bugs on varchar size in create table

= 2.0.0 =
* Add edit banner
* Combo menu for group/key and target
* Contextual HELP
* Icon
* Limit option
* rev list and rev code!

= 1.4.3 =
* Fix patch on old version database table

= 1.4.2 =
* Add screenshot

= 1.4.1 =
* Clean code

= 1.4.0 =
* Rev UI
* Change database
* Fix upload path server bug

= 1.3.2 =
* Fix bug to sort order with Ajax call

= 1.3.1 =
* Update jQuery to last version

= 1.3.0 =
* New improve class object structure

= 1.2.5 =
* Remove a conflict with a new class object structure

= 1.2 =
* Done doc :)

= 1.1 =
* Rev, Fix and stable release

= 1.0 =
* First release


== Upgrade Notice ==

= 2.6.6 =
Fix bugs. Upgrade immediately

= 2.4.1 =
Fix localization. Upgrade immediately

= 2.4.0 =
Add localization/multilanguage support and improve code restyle. Upgrade immediately

= 2.2.0 =
Add Widget support and fix for Wordpress 2.8.6. Upgrade immediately

= 2.0.0 =
Major release improve. Upgrade immediately.

= 1.4.0 =
Major release improve. Upgrade immediately.

= 1.3.1 =
Upgrade to last jQuery release. Upgrade immediately.

= 1.0 =
Please download :)


== Installation ==

1. Upload the entire content of plugin archive to your `/wp-content/plugins/` directory, 
   so that everything will remain in a `/wp-content/plugins/wp-bannerize/` folder
2. Open the plugin configuration page, which is located under `Options -> wp-bannerize`
3. Activate the plugin through the 'Plugins' menu in WordPress (deactivate and reactivate if you're upgrading).
4. Insert in you template php `<?php wp_bannerize(); ?>` function
5. Done. Enjoy.

See [Tutorial Video](http://www.youtube.com/watch?v=sAZOyAwXu-U "Tutorial Video")

== Thanks ==

**Bugs report and beta testing**

* [Ivan](http://www.bobmarleymagazine.com/)
* [rotunda](http://wordpress.org/support/profile/2123029)
* [marsev](http://wordpress.org/support/profile/5368431 "marsev")
* [benstewart](http://wordpress.org/support/profile/5722257 "benstewart")
* [FTLSlacker](http://wordpress.org/support/profile/ftlslacker "FTLSlacker")
* [kwoodall](http://wordpress.org/support/profile/kwoodall "kwoodall")

**Suggestions and ideas**

* [Wasim Asif](http://www.infotales.com/ "wasimasif")

**Tutorial**

* [Troypoint](http://www.youtube.com/watch?v=sAZOyAwXu-U "Tutorial Video")

**Localization**

* [Fernando Lopes](http://www.fernandolopes.com.br/ "Fernando Lopes") (Portuguese localization)
* [Marcis G.](http://pc.de/ "Marcis G.") (Belorussian localization)
* [David Pérez](http://www.closemarketing.net/ "Closemarketing") (Spanish localization)

== Frequently Asked Questions == 

= Can I customize the HTML output? =

Yes, use the `args` for set "container" and "before" and "after" tagging.
For example the default output is:
`
<ul>
<li><a href=".."><img src="..." /></a></li>
<li><a href=".."><img src="..." /></a></li>
...
</ul>`  
You can change `<ul>` (container) and `<li>` (before) 

`<?php wp_bannerize('container_before=<div>&container_after=</div>&before=<span>&after=</span>'); ?>`

`
<div>
<span><a href=".."><img src="..." /></a></span>
<span><a href=".."><img src="..." /></a></span>
...
</div>`


= Can I customize the arguments TAG? =

Yes, you can customize alternate class on "before" TAG and class on link A:

`<?php wp_bannerize('container_before=<div>&container_after=</div>&before=<span %alt%>&after=</span>&link_class=myclass'); ?>`

`
<div>
<span><a href=".."><img src="..." /></a></span>
<span class="alt"><a class="myclass" href=".."><img src="..." /></a></span>
...
</div>`

OR

`<?php wp_bannerize('alt_class=pair&link_class=myclass'); ?>`

`
<ul>
<li><a href=".."><img src="..." /></a></li>
<li class="pair"><a class="myclass" href=".."><img src="..." /></a></li>
...
</ul>`