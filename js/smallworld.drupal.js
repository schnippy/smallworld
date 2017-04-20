(function ($) {

  Drupal.behaviors.smallworld = {
    attach: function (context, settings) {
      (function (factory) {
        'use strict';

          if (typeof define === 'function' && define.amd) {
            define(['jquery'], factory);
          } else {
            factory(window.jQuery || window.Zepto, window.Smallworld);
          }

      }(function ($, Smallworld) {

      'use strict';

      // --------------------------------------------------------------------------
      // Register plugin with jQuery
      // --------------------------------------------------------------------------

      $.extend($.fn, {
        smallworld: function (options) {
          var opts = $.extend({}, $.fn.smallworld.defaults, options);
          return this.each(function () {

          $(this).data('smallworld', new Smallworld(this, opts));
          return this;
        });

      }

      });

      // --------------------------------------------------------------------------
      // Define default plugin options.
      // --------------------------------------------------------------------------

      $.fn.smallworld.defaults = {};

      }));

      var smallworld_geojson = {};
      $.ajax({
    	url: "/sites/all/libraries/smallworld.js/dist/world.json",
    	async: false,
    	dataType: 'json',
    	success: function(data) {
    	  smallworld_geojson = data;
    	}
      });

      $('.smallworld').each(function() {
        var latitude = $(this).attr("data-lat");
        var longitude = $(this).attr("data-long");
        $(this).smallworld({
          geojson: smallworld_geojson,
          marker: [latitude, longitude],
          waterColor: Drupal.settings.smallworld_drupal.smallworld_water,
          landColor: Drupal.settings.smallworld_drupal.smallworld_land,
	  markerSize: Drupal.settings.smallworld_drupal.smallworld_marker_size,
          markerColor: Drupal.settings.smallworld_drupal.smallworld_marker_color,
          center: [Drupal.settings.smallworld_drupal.smallworld_center_lat, Drupal.settings.smallworld_drupal.smallworld_center_long],
        });
      });
 

    }
  };
}(jQuery));
