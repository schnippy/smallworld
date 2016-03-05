<?php

/**
  * Form definition for the open debate engine admin configuration form.
  *
*/

function smallworld_admin_form($form, &$form_state) {

  $form = array();

  $smallworld_water = variable_get("smallworld_water", "#b3d1ff");
  $smallworld_land = variable_get("smallworld_land", "#fff");
  $smallworld_marker_color = variable_get("smallworld_marker_color", "#333");
  $smallworld_marker_size = variable_get("smallworld_marker_size", 5);


  $('.map').smallworld({
	marker: true,
	markerColor: '#fa29df'
});

  $form['smallworld_config_demo'] = array(
    '#type'=> 'item',
    '#title'=> t('From'),
    '#markup'=> "
	<script >
	  $('.map').smallworld({
            marker: true,
            markerColor: '#fa29df'
          });
    "
  );

  $form['smallworld_water'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter URL'),
    '#default_value' => $opendebate_twitter,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 6,
    '#required' => FALSE,
  );

  $form['smallworld_land'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter URL'),
    '#default_value' => $opendebate_twitter,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 6,
    '#required' => FALSE,
  );

  $form['smallworld_center'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter URL'),
    '#default_value' => $opendebate_twitter,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 6,
    '#required' => FALSE,
  );

  $form['smallworld_land'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter URL'),
    '#default_value' => $opendebate_twitter,
    '#description'=> t('Color to use for the land in Smallworld.'),
    '#size' => 60,
    '#maxlength' => 6,
    '#required' => FALSE,
  );

  $form['#submit'][] = 'open_debate_engine_admin_form_submit';

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
