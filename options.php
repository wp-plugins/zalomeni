<?php
if(!empty($_POST['Submit'])) {
	$zalomeni_options = get_option('zalomeni_options');
	$zalomeni_options['zalomeni_prepositions']        = $_POST['zalomeni_prepositions_check'];
	$zalomeni_options['zalomeni_prepositions_list']   = $_POST['zalomeni_prepositions_list'];
	$zalomeni_options['zalomeni_conjunctions']        = $_POST['zalomeni_conjunctions_check'];
	$zalomeni_options['zalomeni_conjunctions_list']   = $_POST['zalomeni_conjunctions_list'];
	$zalomeni_options['zalomeni_abbreviations']       = $_POST['zalomeni_abbreviations_check'];
	$zalomeni_options['zalomeni_abbreviations_list']  = $_POST['zalomeni_abbreviations_list'];
	$zalomeni_options['zalomeni_numbers']             = $_POST['zalomeni_numbers_check'];
	$zalomeni_options['zalomeni_matches']             = zalomeni_prepare_matches($zalomeni_options);
	$zalomeni_options['zalomeni_replacements']        = zalomeni_prepare_replacements($zalomeni_options);
	update_option('zalomeni_options', $zalomeni_options);
?>
<div class="updated"><p><strong><?php echo(zalomeni_texturize(__('Options saved.'))); ?></strong></p></div>
<?php
}
$zalomeni_options = get_option('zalomeni_options');
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__); ?>">
<div class="wrap"> 
	<?php screen_icon(); ?>
	<h2><?php echo(zalomeni_texturize(__('Nevhodná slova a zalomení na konci řádku', 'zalomeni'))); ?></h2> 
	<div style="float:right;margin-left:10px;background:#FFFBCC;text-align:center;width:200px;" class="updated"> 
    <p style="line-height:1.5em;">Plugin <strong>Zalomení</strong><br />Autor: <a href="http://www.honza.info/" class="external" target="_blank" title="http://www.honza.info/">Honza Skýpala</a></p>
  </div>
	<p><?php echo(zalomeni_texturize(__('Upravujeme-li písemný dokument, radí nám <strong>Pravidla českého pravopisu</strong> nepsat neslabičné předložky <em>v, s, z, k</em> na konec řádku, ale psát je na stejný řádek se slovem, které nese přízvuk (např. ve spojení <em>k mostu</em>, <em>s bratrem</em>, <em>v Plzni</em>, <em>z nádraží</em>). Typografické normy jsou ještě přísnější: podle některých je nepatřičné ponechat na konci řádku jakékoli jednopísmenné slovo, tedy také předložky a spojky <em>a, i, o, u</em>;. Někteří pisatelé dokonce nechtějí z estetických důvodů ponechávat na konci řádků jakékoli jednoslabičné výrazy (např. <em>ve, ke, ku, že, na, do, od, pod</em>).', 'zalomeni'))); ?></p>
	<p><?php echo(zalomeni_texturize(__('<a href="http://prirucka.ujc.cas.cz/?id=880" class="external" target="_blank">Více informací</a> na webu Ústavu pro jazyk český, Akademie věd ČR.', 'zalomeni'))); ?></p>
	<p><?php echo(zalomeni_texturize(__('Tento plugin řeší některé z uvedených příkladů: v textu nahrazuje běžné mezery za pevné tak, aby nedošlo k zalomení řádku v nevhodném místě.', 'zalomeni'))); ?></p>

	<h3><?php echo(zalomeni_texturize(__('Předložky', 'zalomeni'))); ?></h3>
	<p><input name="zalomeni_prepositions_check" type="checkbox" id="zalomeni_prepositions_check" value="on" <?php echo ($zalomeni_options['zalomeni_prepositions'] == 'on') ? 'checked="checked"' : ''; ?> onchange="document.getElementById('zalomeni_prepositions_list').readOnly = this.checked?'':'1';" /> 
	   <label for="zalomeni_prepositions_check"><?php echo(zalomeni_texturize(__('Vkládat pevnou mezeru za následující předložky.', 'zalomeni'))); ?></label></p>
	<p><label for="zalomeni_prepositions_list"><?php echo(zalomeni_texturize(__('Výčet předložek (oddělte jednotlivé předložky čárkou):', 'zalomeni'))); ?></label>
		 <input name="zalomeni_prepositions_list" type="text" id="zalomeni_prepositions_list" value="<?php echo $zalomeni_options['zalomeni_prepositions_list']; ?>" class="regular-text" <?php echo ($zalomeni_options['zalomeni_prepositions'] != 'on') ? 'readonly="1"' : ''; ?>/></p>
	<h3><?php echo(zalomeni_texturize(__('Spojky', 'zalomeni'))); ?></h3>
	<p><input name="zalomeni_conjunctions_check" type="checkbox" id="zalomeni_conjunctions_check" value="on" <?php echo ($zalomeni_options['zalomeni_conjunctions'] == 'on') ? 'checked="checked" ' : ''; ?> onchange="document.getElementById('zalomeni_conjunctions_list').readOnly = this.checked?'':'1';"/> 
	   <label for="zalomeni_conjunctions_check"><?php echo(zalomeni_texturize(__('Vkládat pevnou mezeru za následující spojky.', 'zalomeni'))); ?></label></p>
	<p><label for="zalomeni_conjunctions_list"><?php echo(zalomeni_texturize(__('Výčet spojek (oddělte jednotlivé spojky čárkou):', 'zalomeni'))); ?></label>
		 <input name="zalomeni_conjunctions_list" type="text" id="zalomeni_conjunctions_list" value="<?php echo $zalomeni_options['zalomeni_conjunctions_list']; ?>" class="regular-text" <?php echo ($zalomeni_options['zalomeni_conjunctions'] != 'on') ? 'readonly="1"' : ''; ?>/></p>
	<h3><?php echo(zalomeni_texturize(__('Zkratky', 'zalomeni'))); ?></h3>
	<p><input name="zalomeni_abbreviations_check" type="checkbox" id="zalomeni_abbreviations_check" value="on" <?php echo ($zalomeni_options['zalomeni_abbreviations'] == 'on') ? 'checked="checked" ' : ''; ?> onchange="document.getElementById('zalomeni_abbreviations_list').readOnly = this.checked?'':'1';"/> 
	   <label for="zalomeni_abbreviations_check"><?php echo(zalomeni_texturize(__('Vkládat pevnou mezeru za následující zkratky.', 'zalomeni'))); ?></label></p>
	<p><label for="zalomeni_abbreviations_list"><?php echo(zalomeni_texturize(__('Výčet zkratek (oddělte jednotlivé zkratky čárkou):', 'zalomeni'))); ?></label>
		 <input name="zalomeni_abbreviations_list" type="text" id="zalomeni_abbreviations_list" value="<?php echo $zalomeni_options['zalomeni_abbreviations_list']; ?>" class="regular-text" <?php echo ($zalomeni_options['zalomeni_abbreviations'] != 'on') ? 'readonly="1"' : ''; ?>/></p>
	<h3><?php echo(zalomeni_texturize(__('Čísla', 'zalomeni'))); ?></h3>
	<p><input name="zalomeni_numbers_check" type="checkbox" id="zalomeni_numbers_check" value="on" <?php echo ($zalomeni_options['zalomeni_numbers'] == 'on') ? 'checked="checked" ' : ''; ?> /> 
	   <label for="zalomeni_numbers_check"><?php echo(zalomeni_texturize(__('Pokud jsou dvě čísla oddělena mezerou, předpokládat, že se jedná o formátování čísla pomocí mezery (např. telefonní číslo 800 123 456) a nahrazovat mezeru pevnou mezerou, aby nedošlo k zalomení řádku uprostřed čísla.', 'zalomeni'))); ?></label></p>
	<p class="submit">
		<input type="submit" name="Submit" class="button" value="<?php echo(zalomeni_texturize(__('Save Changes'))); ?>" />
	</p>
</div>
</form>