(function ($) {

  Drupal.behaviors.smallworld = {
    attach: function (context, settings) {
      console.log(Smallworld);
      $.getJSON('/sites/all/libraries/smallworld.js/dist/world.json', function (data) {
        $('.map').smallworld({
          geojson: data,
        });
      });
      $('div.map').css("border","2px solid green");
      alert(Drupal.settings.smallworld_drupal.smallworld_water);
    }
  };
}(jQuery));
