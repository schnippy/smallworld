<?php

/*
 * Implementation of hook_filter_info()
 */
function smallworld_filter_info() {

  $filters['smallworld_embed'] = array(
    'title' => t('Embed Smallworld map'),
    'description' => t('Enable users to embed smallworld maps into their content'),
    'prepare callback' => 'smallworld_embed_prepare',
    'process callback' => 'smallworld_embed_process',
    'tips callback'  => 'smallworld_embed_tips',
    'cache' => FALSE,
  );

  return $filters;

} // node_embed_filter_info

/**
 * Tips callback for hook_filter
 */
function smallworld_filter_embed_tips($filter, $format, $long) {

  return t('[smallworld=<COUNTRY CODE>] - Embed a 300x150 map into the page with a marker for a given country.');

} // node_embed_filter_node_embed_tips

/**
 * Process callback for hook_filter
 */
function smallworld_embed_process($text, $filter, $format, $langcode, $cache, $cache_id) {

  return preg_replace_callback('/\[smallworld=([a-zA-Z]+)\]/si','_smallworld_embed_replacements', $text);

} // smallworld_embed_process

/**
 * Process callback for hook_filter
 */
function smallworld_embed_prepare($text, $filter, $format, $langcode, $cache, $cache_id) {

  return $text;

} // smallworld_embed_prepare

/*
 * Provides the replacement html to be rendered in place of the embed code.
 * Does not handle nested embeds.
 *
 * @param $matches
 *    numeric node id that has been captured by preg_replace_callback
 * @return
 *    The rendered HTML replacing the embed code
 */

function _smallworld_embed_replacements($matches) {

  include_once(drupal_get_path("module","smallworld")."/smallworld.data.inc");

  global $smallworld_lookup; 

  return _smallworld_output_div($smallworld_lookup[$matches[1]]["lat"], $smallworld_lookup[$matches[1]]["long"]);
  
} // _embed_replacements
