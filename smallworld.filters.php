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

  return t('[smallworld=<COUNTRY CODE] - Embed a 300x150 map into the page with a marker for a given country.');

} // node_embed_filter_node_embed_tips

/**
 * Process callback for hook_filter
 */
function smallworld_embed_process($text, $filter, $format, $langcode, $cache, $cache_id) {

  return preg_replace_callback('/\[embed=(\\d+)\]/si','_smallworld_embed_replacements', $text);

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

  $node = node_load($matches[1]);

  if ($node == FALSE || !node_access('view', $node) || !$node->status
  	 || !(in_array($node->type, array("argument","evidence","news","citation","position")))) {

    return "<p class='error'>ERROR: Invalid embed shortcode [{$matches[1]}] -- ".$node->type."</p>";

  } // if
  else {

    $node->node_embed_parameters = array();

    if (!isset($node->node_embed_parameters['view_mode'])) {
      $node->node_embed_parameters['view_mode'] = 'node_embed';
    } // if
    $view = node_view($node, 'opendebate_embed', NULL);
    $render = drupal_render($view);
    $render = "<div class='opendebate-embed-container'>".$render."</div>";
    return $render;

  } // else

} // _embed_replacements

