=== NGG Sidebar Widget ===
Contributors: ppfeufer
Donate link: 
Tags: image, picture, photo, widgets, gallery, images, nextgen-gallery
Requires at least: 2.8
Tested up to: 3.1-alpha
Stable tag: 1.0.1

A widget to show NextGEN galleries listed in your sidebar.

== Description ==

The NextGEN widgets only allow showing of single images, I needed a solution to show links to galleries, so I wrote this widget. You can specify the following parameters:

- Maximum Galleries: the number of galleries you want to show
- Gallery Order: you can select random, date added ascending or date added descending
- Gallery Thumbnail: which image should be taken as thumbail in the sidebar (preview set in NGG, first or random image)
- AutoThumb parameters: if you got [AutoThumb](http://wordpress.org/extend/plugins/autothumb/) installed, the widget will use its functions to resize the image to your needs. Use a string like `w=80&h=80&zc=1` here to show 80x80 square thumbnails.
- Output width/height: if you don't use AutoThumb, the plugin will set the HTML attributes width & height.
- Default Link Id: the widget assumes that you set up pages for each gallery and link the gallery to that page (you can use the NGG Gallery Editor to do this). If a gallery has no link set, it will use the default link (id of a page or post).
- Exclude galleries: exclude galleries by specifying their ID as comma separated list

== Installation ==

1. Upload folder `ngg-sidebar-widget` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the widget in the widget editor.

== Changelog ==

= 1.0.1 =
* Added Screenshot

= 1.0.0 =
* Editied NextGEN Gallery Sidebar Widget
* Added option to show no images, ohnly Links to galleries
* Added german translation
* Upload to WP Directory

== Screenshots ==

1. Widget Admin Panel

== Upgrade Notice ==

Just upgrade

== Frequently Asked Questions ==

None