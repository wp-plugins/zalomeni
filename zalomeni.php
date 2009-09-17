<?php
/*
Plugin Name: Zalomení
Plugin URI: http://www.honza.info/category/wordpress/
Description: Puts non-breakable space after one-letter Czech prepositions like 'k', 's', 'v' or 'z'.
Version: 1.0
Author: Honza Skypala
Author URI: http://www.honza.info/
*/

define('ZALOMENI_VERSION', '1.0');

function zalomeni_activate() {
	// default settings
	$zalomeni_options = array();
	$zalomeni_options['zalomeni_prepositions']       = 'on';
	$zalomeni_options['zalomeni_prepositions_list']  = 'k, s, v, z';
	$zalomeni_options['zalomeni_conjunctions']       = '';
	$zalomeni_options['zalomeni_conjunctions_list']  = 'a, i, o, u';
	$zalomeni_options['zalomeni_abbreviations']      = '';
	$zalomeni_options['zalomeni_abbreviations_list'] = 'např., tj., tzv., tzn.';
	add_option('zalomeni_options', $zalomeni_options);
	// update_option('zalomeni_options', $zalomeni_options);  // reset settings to default
	
	// rebuild the replacement array in every case
	$zalomeni_options = get_option('zalomeni_options');
	$zalomeni_options['zalomeni_replacement_array'] = zalomeni_prepare_array($zalomeni_options);
	update_option('zalomeni_options', $zalomeni_options);

	// remember version
	add_option('zalomeni_version', ZALOMENI_VERSION);
	update_option('zalomeni_version', ZALOMENI_VERSION);
}

function zalomeni_prepare_single_array($array_string) {
	$temp_array = explode(',', $array_string);
	$return_array = array();
	foreach ($temp_array as $i) {
		$i = strtoupper(trim($i));
		if ($i != '') {
			$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';        // precedes with space, ASCII 32
			$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';        // precedes with hard space, ASCII 160
			$return_array['&nbsp;'.$i.' '] = '&nbsp;'.$i.'&nbsp;';   // precedes with hard space, string '&nbsp;'
			$i = strtolower($i);
			$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';
			$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';
			$return_array['&nbsp;'.$i.' '] = '&nbsp;'.$i.'&nbsp;';
			if (strlen($i) > 1) {
				$i = ucfirst($i);
				$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';
				$return_array[' '.$i.' ']      = ' '.$i.'&nbsp;';
				$return_array['&nbsp;'.$i.' '] = '&nbsp;'.$i.'&nbsp;';
			}
		}
	}
	return $return_array;
}

function zalomeni_prepare_array($zalomeni_options) {
	$return_array = array();
	if ($zalomeni_options['zalomeni_prepositions'] == 'on')
		$return_array = array_merge($return_array, zalomeni_prepare_single_array($zalomeni_options['zalomeni_prepositions_list']));
	if ($zalomeni_options['zalomeni_conjunctions'] == 'on')
		$return_array = array_merge($return_array, zalomeni_prepare_single_array($zalomeni_options['zalomeni_conjunctions_list']));
	if ($zalomeni_options['zalomeni_abbreviations'] == 'on')
		$return_array = array_merge($return_array, zalomeni_prepare_single_array($zalomeni_options['zalomeni_abbreviations_list']));
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
add_filter('plugin_action_links', 'zalomeni_filter_plugin_actions', 10, 2);

register_activation_hook(__FILE__, 'zalomeni_activate');
add_action('admin_menu', 'zalomeni_add_options_page');

global $wp_cockneyreplace;
$zalomeni_options = get_option('zalomeni_options');
$wp_cockneyreplace = array_merge(isset($wp_cockneyreplace) ? $wp_cockneyreplace : array(), $zalomeni_options['zalomeni_replacement_array']);
?>
