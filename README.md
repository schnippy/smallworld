#Smallworld.js Integration Module for Drupal 7#

The Smallworld module is a Drupal 7 module that integrates the handy [Smallworld.js](http://mikefowler.me/smallworld.js/) jquery library, written by [Mike Fowler](http://mikefowler.me/) to enable editors to quickly place small world maps inline in their text. The Smallworld.js library makes it easy to render a small world map on a page with only a few lines of Javascript:

`  $('.map').smallworld({
  	geojson: data
  });``

Where the Drupal 7 module comes in is by offering a handy wrapper for the library and simplifying the process of adding a map for non-technical editors. Instead of having to enable inline jquery or lookup

##Installation##

1. Download and install the Libraries API 2.x module if you have not already
2. Download and install the Smallword module here
3. Download and install the Smallword.js library from here
4. Go to admin/configure/media/smallworld to set the colors for your smallworld maps and define your map class.

**IMPORTANT** - You must define the size of your map div for it to display, otherwise the library won't know what size canvas to render. On the settings page, the first and only required option is 'Map Class' (which defaults to just 'map'), you need to define in your site CSS the height and width of this div. For example, if I define the "Map Class" as 'world_map' than I would need to have something like the following CSS:

`.world_map {
  height: 150px;
  width: 300px;
}`

The above dimensions are the default dimensions used for the Smallworld.js library but you can adjust these somewhat to get different sizes though the map will start to break or repeat if you go any higher than these (its called smallworld for a reason!).

##How to Get Maps to Display##

There are three ways to get the maps to display:

**Input Filter**

This module comes with an input filter that you can enable that will let content editors add a smallworld map to any page by just using the shortcode:

  `[smallworld=<two letter country code>], ex: [smallworld=US]`

which will output a smallworld map with a marker on the capital of the U.S. The included data file, smallworld.data.inc, includes a lookup file with lat / long information for every country to make this possible.
