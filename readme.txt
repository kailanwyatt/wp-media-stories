=== WP Media Stories ===
Contributors: suiteplugins, wpmediastories
Donate link: https://wpmediastories.com/
Tags: gallery, wordpress gallery, image gallery, wordpress gallery plugin, wp media stories
Requires at least: 3.0.1
Tested up to: 5.9.2
Stable tag: 0.1.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy to use WordPress Media Photo Gallery. Don't just add photos! Make Stories!

== Description ==

WP Media Stories is a little different to other galleries. Yes, you can add photos but the goal is to turn your photos to stories. Add photos, title and desription and let your viewers, read as the watch. Build an SEO rich photo gallery or use WP Media Stories for your media site.

= Features =

* Sortable Images
* Standalone Gallery View
* Embeddable Galleries
* Custom Lightbox Slideshow
* HTML image captions
* Language Localization ready

= Features being worked on =

* Copyright/disclaimer field
* Additional caption field
* Widgets and Shortcodes
* Different layouts

= Shortcodes =
* Galleries - Displays a list of media galleries
* Inline    - Embed a gallery inside of a post and open the gallery in a popup or on its own page. 


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-media-stories` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= What plugins do I need? =

None! The plugin is totally independent.


= Where can I place the translation file? =

The files can be placed in **wp-content/languages/plugins/** with the file name **wp-media-stories-en_US.mo**.

== Screenshots ==

1. Embedded Gallery

2. Embedded Gallery Modal

3. Gallery edit form

4. Gallery Single View with titles and caption

== Documentation ==

= Shortcodes =

**Inline Embed**

`[wp_media_story_inline id="123"]`

You can embed a preview of a gallery into posts, pages, widgets or any where that shortcodes are accepted. The shortcode uses the following attributes:

1. id - The ID of the gallery

**Galleries List**

`[wp_media_story_galleries]`

You can embed a preview of a gallery into posts, pages, widgets or any where that shortcodes are accepted. The shortcode uses the following attributes:

1. category
2. exclude_category
3. tags
4. exclude_tags
5. relation
6. number
7. show_image
8. show_title
9. layout
10. orderby
11. order
12. ids


Both the  category and tags parameters accept a comma separated list IDs. For example:

`[wp_media_story_galleries category="8,15"]`

The exclude_category and exclude_tags parameters are used to prevent galleries with specific categories or tags from being displayed. Use a comma separated list of IDs for each.

The order parameter accepts either "DESC" or "ASC".

The orderby parameter accepts the following options:

1. id
2. random
3. post_date (default)
4. title

The  number parameter accept a numerical value. Specify the maximum number of categories you want to outputted by the shortcode. For example:

`[wp_media_story_galleries number="25"]`

The  ids parameter accepts specific gallery IDs. You can specify multiple gallery IDs using comma separated values. For example:

`[wp_media_story_galleries ids="1,9,15,20,90"]`

The layout parameter accepts either grid or list. It will display the galleries in a grid or list. For example:

`[wp_media_story_galleries layout="grid"]`


`[wp_media_story_galleries ids="list"]`

The show_image and show_title parameters are used to Show/Hide the image and title. The parameter accepts true or false. For example

`[wp_media_story_galleries show_title="false" show_image="true" ]`

== Changelog ==

= 0.1.1 =
* Revived plugin.
* Maintenance: Made compatible with WordPress 5.9.2
* New: Started work on Gutenberg integration

= 0.1 =
* Released


== Upgrade Notice ==
= 0.1 =
* Plugin released into the wild
