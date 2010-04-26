<?php
/*
Plugin Name: Zalomení
Plugin URI: http://www.honza.info/category/wordpress/
Description: Puts non-breakable space after one-letter Czech prepositions like 'k', 's', 'v' or 'z'.
Version: 1.2.1
Author: Honza Skypala
Author URI: http://www.honza.info/
*/

define('ZALOMENI_VERSION', '1.1');

function zalomeni_activate() {
	// default settings
	define('ZKRATKY', 'cca., č., čís., čj., čp., fa, fě, fy, kupř., mj., např., p., pí, popř., př., přib., přibl., sl., str., sv., tj., tzn., tzv., zvl.');
	$zalomeni_options = array();
	$zalomeni_options['zalomeni_prepositions']       = 'on';
	$zalomeni_options['zalomeni_prepositions_list']  = 'k, s, v, z';
	$zalomeni_options['zalomeni_conjunctions']       = '';
	$zalomeni_options['zalomeni_conjunctions_list']  = 'a, i, o, u';
	$zalomeni_options['zalomeni_abbreviations']      = '';
	$zalomeni_options['zalomeni_abbreviations_list'] = ZKRATKY;
	$zalomeni_options['zalomeni_numbers']            = 'on';
	add_option('zalomeni_options', $zalomeni_options);
	// update_option('zalomeni_options', $zalomeni_options);  // reset settings to default
	
	$zalomeni_options = get_option('zalomeni_options');
	$zalomeni_version = get_option('zalomeni_version');

	if ($zalomeni_version == '1.0') {
		// adjust changes version 1.0 -> version 1.1
		$zalomeni_options['zalomeni_abbreviations_list'] = ZKRATKY;  // new list of abbreviations
		$zalomeni_options['zalomeni_numbers']            = 'on';     // set numbers on if version upgrade
		unset($zalomeni_options['zalomeni_replacement_array']);      // remove this setting
	}

	// rebuild the replacement array in every case
	$zalomeni_options['zalomeni_matches']      = zalomeni_prepare_matches($zalomeni_options);
	$zalomeni_options['zalomeni_replacements'] = zalomeni_prepare_replacements($zalomeni_options);

	update_option('zalomeni_options', $zalomeni_options);

	// remember version
	add_option('zalomeni_version', ZALOMENI_VERSION);
	update_option('zalomeni_version', ZALOMENI_VERSION);
}

function zalomeni_prepare_matches($zalomeni_options) {
	$return_array = array();
	
	$word_matches = '';
	foreach (array('prepositions', 'conjunctions', 'abbreviations') as $i) {
		if ($zalomeni_options['zalomeni_'.$i] == 'on') {
			$temp_array = explode(',', $zalomeni_options['zalomeni_'.$i.'_list']);
			foreach ($temp_array as $j) {
				$j = strtolower(trim($j));
				$word_matches .= ($word_matches == '' ? '' : '|') . $j;
			}
		}
	}
	if ($word_matches != '') {
		$return_array['words'] = '@(^|;| |&nbsp;|\(|\n)('.$word_matches.') @i';
	}
	
	if ($zalomeni_options['zalomeni_numbers'] == 'on') {
		$return_array['numbers'] = '@(\d) (\d)@i';
	}

	return $return_array;
}

function zalomeni_prepare_replacements($zalomeni_options) {
	$return_array = array();
	
	foreach (array('prepositions', 'conjunctions', 'abbreviations') as $i) {
		if ($zalomeni_options['zalomeni_'.$i] == 'on') {
			$return_array['words'] = '$1$2&nbsp;';
			break;
		}
	}
	
	if ($zalomeni_options['zalomeni_numbers'] == 'on') {
		$return_array['numbers'] = '$1&nbsp;$2';
	}

	return $return_array;
}

function zalomeni_add_options_page() {
  add_options_page('Zalomení', 'Zalomení', 8, 'zalomeni/options.php');
}

// Add settings link to plugin list for this plugin
function zalomeni_filter_plugin_actions($links, $file) {
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
	if ($file == $this_plugin) {
		$settings_link = '<a href="options-general.php?page=zalomeni/options.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

function zalomeni_texturize($text) {
	global $wp_version;
	$zalomeni_options = get_option('zalomeni_options');
	$output = '';
	$curl = '';
	$textarr = preg_split('/(<.*>|\[.*\])/Us', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
	$stop = count($textarr);
	
	$no_texturize_tags = apply_filters('no_texturize_tags', array('pre', 'code', 'kbd', 'style', 'script', 'tt'));
	$no_texturize_shortcodes = apply_filters('no_texturize_shortcodes', array('code'));
	$no_texturize_tags_stack = array();
	$no_texturize_shortcodes_stack = array();

	for ($i = 0; $i < $stop; $i++) {
		$curl = $textarr[$i];

		if (!empty($curl) && '<' != $curl{0} && '[' != $curl{0}
				&& empty($no_texturize_shortcodes_stack) && empty($no_texturize_tags_stack)) { // If it's not a tag
			$curl = preg_replace($zalomeni_options['zalomeni_matches'], $zalomeni_options['zalomeni_replacements'], $curl);
		} else if (version_compare($wp_version, '2.9', '<')) {
			wptexturize_pushpop_element($curl, $no_texturize_tags_stack, $no_texturize_tags, '<', '>');
			wptexturize_pushpop_element($curl, $no_texturize_shortcodes_stack, $no_texturize_shortcodes, '[', ']');
    } else {
			_wptexturize_pushpop_element($curl, $no_texturize_tags_stack, $no_texturize_tags, '<', '>');
			_wptexturize_pushpop_element($curl, $no_texturize_shortcodes_stack, $no_texturize_shortcodes, '[', ']');
		}

		$output .= $curl;
	}

	return $output;
}

add_filter('plugin_action_links', 'zalomeni_filter_plugin_actions', 10, 2);  // link from Plugins list admin page to settings of this plugin

register_activation_hook(__FILE__, 'zalomeni_activate');  // activation of plugin
add_action('admin_menu', 'zalomeni_add_options_page');    // options page

$zalomeni_options = get_option('zalomeni_options');
if (!empty($zalomeni_options['zalomeni_matches'])) {
	$filters = array('comment_author', 'term_name', 'link_name', 'link_description',
		'link_notes', 'bloginfo', 'wp_title', 'widget_title', 'term_description',
		'the_title', 'the_content', 'the_excerpt', 'comment_text', 'single_post_title',
		'list_cats');
	foreach ($filters as $filter) {
		add_filter($filter, 'zalomeni_texturize');  // content filter
	}
}
?>