(function ($) {

  Drupal.behaviors.smallworld = {
    attach: function (context, settings) {
      var el = document.querySelector('.map');
      var map = new Smallworld(el, options);
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
