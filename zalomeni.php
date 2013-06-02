<?php
/*
Plugin Name: Zalomení
Plugin URI: http://www.honza.info/category/wordpress/
Description: Puts non-breakable space after one-letter Czech prepositions like 'k', 's', 'v' or 'z'.
Version: 1.2.4
Author: Honza Skypala
Author URI: http://www.honza.info/
*/

define('ZALOMENI_VERSION', '1.2.4');

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

function zalomeni_add_options() {
	add_settings_section('zalomeni_section', zalomeni_texturize(__('Nevhodná slova a zalomení na konci řádku', 'zalomeni')), 'zalomeni_settings_section_callback_function', 'reading');
	add_settings_field('zalomeni_prepositions_check', __('Předložky', 'zalomeni'), create_function('', 'zalomeni_option_check_callback_function("prepositions", "Vkládat pevnou mezeru za následující předložky.");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_prepositions_check', create_function('$input', 'zalomeni_option_sanitize("prepositions", $input);'));
	add_settings_field('zalomeni_prepositions_list', '', create_function('', 'zalomeni_option_list_callback_function("prepositions", "(oddělte jednotlivé předložky čárkou)");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_prepositions_list', create_function('$input', 'zalomeni_option_sanitize("prepositions_list", $input);'));
	add_settings_field('zalomeni_conjunctions_check', __('Spojky', 'zalomeni'), create_function('', 'zalomeni_option_check_callback_function("conjunctions", "Vkládat pevnou mezeru za následující spojky.");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_conjunctions_check', create_function('$input', 'zalomeni_option_sanitize("conjunctions", $input);'));
	add_settings_field('zalomeni_conjunctions_list', '', create_function('', 'zalomeni_option_list_callback_function("conjunctions", "(oddělte jednotlivé spojky čárkou)");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_conjunctions_list', create_function('$input', 'zalomeni_option_sanitize("conjunctions_list", $input);'));
	add_settings_field('zalomeni_abbreviations_check', __('Zkratky', 'zalomeni'), create_function('', 'zalomeni_option_check_callback_function("abbreviations", "Vkládat pevnou mezeru za následující zkratky.");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_abbreviations_check', create_function('$input', 'zalomeni_option_sanitize("abbreviations", $input);'));
	add_settings_field('zalomeni_abbreviations_list', '', create_function('', 'zalomeni_option_list_callback_function("abbreviations", "(oddělte jednotlivé zkratky čárkou)");'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_abbreviations_list', create_function('$input', 'zalomeni_option_sanitize("abbreviations_list", $input);'));
	add_settings_field('zalomeni_numbers_check', __('Čísla', 'zalomeni'), create_function('', 'zalomeni_option_check_callback_function("numbers", "Pokud jsou dvě čísla oddělena mezerou, předpokládat, že se jedná o formátování čísla pomocí mezery (např. telefonní číslo 800 123 456) a nahrazovat mezeru pevnou mezerou, aby nedošlo k zalomení řádku uprostřed čísla.", false);'), 'reading', 'zalomeni_section');
	register_setting('reading', 'zalomeni_numbers_check', create_function('$input', 'zalomeni_option_sanitize("numbers", $input);'));
}

function zalomeni_settings_section_callback_function() {
	echo(
	  '<div id="zalomeni_options_desc" style="margin:0 0 15px 10px;-webkit-border-radius:3px;border-radius:3px;border-width:1px;border-color:#e6db55;border-style:solid;float:right;background:#FFFBCC;text-align:center;width:200px">'
	  . '<p style="line-height:1.5em;">Plugin <strong>Zalomení</strong><br />Autor: <a href="http://www.honza.info/" class="external" target="_blank" title="http://www.honza.info/">Honza Skýpala</a></p>'
	  . '</div>'
	  . '<p>' . zalomeni_texturize(__('Upravujeme-li písemný dokument, radí nám <strong>Pravidla českého pravopisu</strong> nepsat neslabičné předložky <em>v, s, z, k</em> na konec řádku, ale psát je na stejný řádek se slovem, které nese přízvuk (např. ve spojení <em>k mostu</em>, <em>s bratrem</em>, <em>v Plzni</em>, <em>z nádraží</em>). Typografické normy jsou ještě přísnější: podle některých je nepatřičné ponechat na konci řádku jakékoli jednopísmenné slovo, tedy také předložky a spojky <em>a, i, o, u</em>;. Někteří pisatelé dokonce nechtějí z estetických důvodů ponechávat na konci řádků jakékoli jednoslabičné výrazy (např. <em>ve, ke, ku, že, na, do, od, pod</em>).', 'zalomeni')) . '</p>'
	  . '<p>' . zalomeni_texturize(__('<a href="http://prirucka.ujc.cas.cz/?id=880" class="external" target="_blank">Více informací</a> na webu Ústavu pro jazyk český, Akademie věd ČR.', 'zalomeni')) . '</p>'
	  . '<p>' . zalomeni_texturize(__('Tento plugin řeší některé z uvedených příkladů: v textu nahrazuje běžné mezery za pevné tak, aby nedošlo k zalomení řádku v nevhodném místě.', 'zalomeni')) . '</p>'
	); 
}

function zalomeni_option_check_callback_function($option, $description, $listReadOnlyCode=true) {
	$zalomeni_options = get_option('zalomeni_options');
  echo(
	  '<input name="zalomeni_' . $option . '_check" type="checkbox" id="zalomeni_' . $option . '_check" value="on" '
	  . checked('on', $zalomeni_options["zalomeni_$option"], false)
	  . ($listReadOnlyCode ? ' onchange="document.getElementById(\'zalomeni_' . $option . '_list\').readOnly = this.checked?\'\':\'1\';"' : '')
	  . ' /> '
	  . zalomeni_texturize(__($description, 'zalomeni'))
	 );
}

function zalomeni_option_list_callback_function($option, $description) {
	$zalomeni_options = get_option('zalomeni_options');
	echo(
	  '<input name="zalomeni_' . $option . '_list" type="text" id="zalomeni_' . $option . '_list" class="regular-text" value="'
	  . $zalomeni_options["zalomeni_" . $option . "_list"]
	  . '"'
	  . (($zalomeni_options["zalomeni_$option"] != 'on') ? ' readonly="1"' : '')
	  . ' /> '
	  . zalomeni_texturize(__($description, 'zalomeni'))
	 );
}

function zalomeni_option_sanitize($option, $input) {
	$zalomeni_options = get_option('zalomeni_options');
	$zalomeni_options["zalomeni_$option"]      = $input;
	$zalomeni_options['zalomeni_matches']      = zalomeni_prepare_matches($zalomeni_options);
	$zalomeni_options['zalomeni_replacements'] = zalomeni_prepare_replacements($zalomeni_options);
	update_option('zalomeni_options', $zalomeni_options);
}

// Add settings link to plugin list for this plugin
function zalomeni_filter_plugin_actions($links, $file) {
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
	if ($file == $this_plugin) {
		$settings_link = '<a href="options-reading.php#zalomeni_options_desc">' . __('Settings') . '</a>';
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
if ( is_admin() ){ // admin actions
	add_action('admin_init', 'zalomeni_add_options');        // register options
} else {
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
}
?>