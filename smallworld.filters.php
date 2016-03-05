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

  return t('[embed=<OBJECT ID>] - Embed a fully rendered object. Valid types are news, citations, arguments, and quotes.');

} // node_embed_filter_node_embed_tips

/**
 * Tips callback for hook_filter
 */
function smallworld_filter_footnote_tips($filter, $format, $long) {

  return t('[footnote=<OBJECT ID>]your text here[/footnote] - link text as a hover footnote. Valid types are citations and news.');

} // node_embed_filter_node_embed_tips

/**
 * Tips callback for hook_filter
 */
function smallworld_filter_link_tips($filter, $format, $long) {

  return t('[link=<OBJECT ID>]your text here[/link] - provide hover link text to an object. Valid types are news, citations, arguments, and quotes. ');

} // node_embed_filter_node_embed_tips

/**
 * Tips callback for hook_filter
 */
function smallworld_filter_definition_tips($filter, $format, $long) {

  return t('[definition=<KEYWORD>]your text here[/footnote] - link to an existing taxonomy term to provide an inline hover definition and a link to the keywords page.');

} // node_embed_filter_node_embed_tips


/**
 * Implements hook_entity_info_alter().
 */
function smallworld_entity_info_alter(&$entity_info) {

  if (isset($entity_info['node'])) {

    $entity_info['node']['view modes'] += array(
      'opendebate_embed' => array(
        'label' => 'Open Debate Engine embed',
        'custom settings' => FALSE,
      ),
    );

  } // if

} // node_embed_entity_info_alter

/**
 * Process callback for hook_filter
 */
function smallworld_embed_process($text, $filter, $format, $langcode, $cache, $cache_id) {

  return preg_replace_callback('/\[embed=(\\d+)\]/si','_smallworld_embed_replacements', $text);

} // smallworld_embed_process

/**
 * Process callback for hook_filter
 */
function smallworld_footnote_process($text, $filter, $format, $langcode, $cache, $cache_id) {
  return preg_replace_callback('/\[footnote=(\\d+)\](.*?)\[\/footnote\]/si','_smallworld_footnote_replacements', $text);

} // smallworld_embed_process

/**
 * Process callback for hook_filter
 */
function smallworld_link_process($text, $filter, $format, $langcode, $cache, $cache_id) {
  return preg_replace_callback('/\[link=(\\d+)\](.*?)\[\/link\]/si','_smallworld_link_replacements', $text);

} // smallworld_link_process

/**
 * Process callback for hook_filter
 */
function smallworld_definition_process($text, $filter, $format, $langcode, $cache, $cache_id) {
  return preg_replace_callback('/\[definition=([^\]]+)\](.*?)\[\/definition\]/si','_smallworld_definition_replacements', $text);

} // smallworld_definition_process



/**
 * Process callback for hook_filter
 */
function smallworld_embed_prepare($text, $filter, $format, $langcode, $cache, $cache_id) {

  return $text;

} // smallworld_embed_prepare

/**
 * Process callback for hook_filter
 */
function smallworld_footnote_prepare($text, $filter, $format, $langcode, $cache, $cache_id) {

  return $text;

} // smallworld_footnote_prepare

/**
 * Process callback for hook_filter
 */
function smallworld_link_prepare($text, $filter, $format, $langcode, $cache, $cache_id) {

  return $text;

} // smallworld_link_prepare

/**
 * Process callback for hook_filter
 */
function smallworld_definition_prepare($text, $filter, $format, $langcode, $cache, $cache_id) {

  return $text;

} // smallworld_definition_prepare


/**
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

/**
 * Provides the replacement text for footnote URLs which are only for citations and news.
 *
 * @param $matches
 *    numeric node id that has been captured by preg_replace_callback
 * @return
 *    The rendered HTML replacing the embed code
 */

function _smallworld_footnote_replacements($matches) {

  $node = node_load($matches[1]);

  if ($node == FALSE || !node_access('view', $node) || !$node->status
         || !(in_array($node->type, array("citation", "news")))) {

    return "<p class='error'>ERROR: Invalid footnote shortcode [FOOTNOTE={$matches[2]}]</p>";

  } // if
  else {

    switch($node->type) {

      case "citation":
        $payload = opendebate_output_citation($node);
        break;

      case "news":
        $payload = smallworld_output_news_hover($node);
	    break;

	  default:
	    break;

    }

    $output = "<a id='ode-hover-footnote-link-".$node->nid."' data-nid='".$node->nid."'>".trim($matches[2])."</a>";
    $output .= "<span class='ode-hover-footnote-text-".$node->nid."''>".$payload."</span>";

    return $output;

  } // else

} // _node_footnote_replacements

/**
 * Provides the replacement text for footnote URLs which are only for citations and news.
 *
 * @param $matches
 *    numeric node id that has been captured by preg_replace_callback
 * @return
 *    The rendered HTML replacing the embed code
 */

function _smallworld_link_replacements($matches) {

  $node = node_load($matches[1]);

  if ($node == FALSE || !node_access('view', $node) || !$node->status || !(in_array($node->type, array("argument","evidence","news","citation","author","organization")))) {

    return "<p class='error'>ERROR: Invalid link shortcode [LINK={$matches[2]}]</p>";

  } // if
  else {

    switch($node->type) {

      case "citation":
        $payload = opendebate_output_citation($node);
        break;

      case "news":
        $payload = smallworld_output_news_hover($node);
	break;

      case "evidence":
        $payload = smallworld_output_evidence_hover($node);
        break;

      case "argument":
	$payload = smallworld_output_node_hover($node);
	break;

      case "author":
        $payload = smallworld_output_node_hover($node);
        break;

      case "organization":
        $payload = smallworld_output_node_hover($node);
        break;

      default:
        break;

    }

    $output = "<a id='ode-hover-link-link-".$node->nid."' data-nid='".$node->nid."'>".trim($matches[2])."</a>";
    $output .= "<span class='ode-hover-link-text-".$node->nid."''>".$payload."</span>";

    return $output;

  } // else

} // _link_replacements

/**
 * Provides the replacement text for footnote URLs which are only for citations and news.
 *
 * @param $matches
 *    numeric node id that has been captured by preg_replace_callback
 * @return
 *    The rendered HTML replacing the embed code
 */

function _smallworld_definition_replacements($matches) {

  $tmp = taxonomy_get_term_by_name(check_plain($matches[1]));
  $term = array_shift($tmp);

  /* If there is no keyword match, then return out with definition code. */
  if (!$term) { return "[DEFINITION=Keyword]YOUR TEXT HERE[/DEFINITION]"; }

  $path = drupal_lookup_path("alias","taxonomy/term/".$term->tid);
  $payload  = "<b><i>".ucfirst($term->name)."</b></i><br /><br />".$term->description."<br /><br />[ <a href='".$path."'> More </a>]";
  $output = "<a id='ode-hover-link-link-".$term->tid."' data-nid='".$term->tid."'>".trim($matches[1])."</a>";
  $output .= "<span class='ode-hover-link-text-".$term->tid."''>".$payload."</span>";

  return $output;

} // _definition_replacements
