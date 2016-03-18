<?php

/**
  * Form definition for the smallworld admin configuration form.
  *
*/

function smallworld_admin_form($form, &$form_state) {

  $form = array();

  $smallworld_water = variable_get("smallworld_water", "#b3d1ff");
  $smallworld_land = variable_get("smallworld_land", "#fff");
  $smallworld_marker_color = variable_get("smallworld_marker_color", "#333");
  $smallworld_marker_size = variable_get("smallworld_marker_size", 5);
  $smallworld_center_lat = variable_get("smallworld_center_lat", 0);
  $smallworld_center_long = variable_get("smallworld_center_long", 0);

  if (($library = libraries_load("smallworld.js")) && !empty($library['loaded'])) {
    $form['smallworld_config_demo'] = array(
        '#markup'=> "
  <div class='smallworld map' style='width: 300px; height: 150px' data-lat='37.757719928168605' data-long='-122.43760000000003'></div>
  ",
    );
  }
  else {
    $error = $library['error'];

    // This contains a detailed (localized) error message.
    $error_message = $library['error message'];
    drupal_set_message(t("smallworld.js library is not loaded: @error.", array("@error" => $error_message)), "error");
  }

  $form['smallworld_water'] = array(
    '#type' => 'textfield',
    '#title' => t('Water Color'),
    '#default_value' => $smallworld_water,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 15,
    '#required' => FALSE,
  );

  $form['smallworld_land'] = array(
    '#type' => 'textfield',
    '#title' => t('Land Color'),
    '#default_value' => $smallworld_land,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 15,
    '#required' => FALSE,
  );

  $form['smallworld_marker_color'] = array(
    '#type' => 'textfield',
    '#title' => t('Marker Color'),
    '#default_value' => $smallworld_marker_color,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 15,
    '#required' => FALSE,
  );

  $form['smallworld_marker_size'] = array(
    '#type' => 'textfield',
    '#title' => t('Size of Marker'),
    '#default_value' => $smallworld_marker_size,
    '#description'=> t('Size of the marker (1-10).'),
    '#size' => 60,
    '#maxlength' => 6,
    '#required' => FALSE,
  );

  $form['smallworld_center_long'] = array(
    '#type' => 'textfield',
    '#title' => t('Map Centering: Longitude'),
    '#default_value' => $smallworld_center_long,
    '#description'=> t('Where to center the map, Longitude.'),
    '#size' => 60,
    '#maxlength' => 4,
    '#required' => FALSE,
  );

  $form['smallworld_center_lat'] = array(
    '#type' => 'textfield',
    '#title' => t('Map Centering: Latitude'),
    '#default_value' => $smallworld_center_lat,
    '#description'=> t('Where to center the map, Latitude.'),
    '#size' => 60,
    '#maxlength' => 4,
    '#required' => FALSE,
  );

  return system_settings_form($form);

}

/**
 * Submit handler for open debate engine admin form
 */

function smallworld_admin_form_submit($form, &$form_state) {

  if ($form_state['values']['opendebate_news_default_image_fid'] != 0) {

    // Make the file permanent.
    $file = file_load($form_state['values']['opendebate_news_default_image_fid']);
    $file->status = FILE_STATUS_PERMANENT;
    file_save($file);

    // Need to add an entry in the file_usage table.
    file_usage_add($file, 'open_debate_engine', 'image', 1);

    // Save the fid of the file so that the module can reference it later.
    variable_set('opendebate_news_default_image_fid', $file->fid);

    drupal_set_message(t('The image @image_name was uploaded and saved with an ID of @fid.', array('@image_name' => $file->filename, '@fid' => $file->fid)));
  }
}
