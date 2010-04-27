=== Zalomení ===
Contributors: honza.skypala
Donate link: http://www.honza.info
Tags: grammar, Czech
Requires at least: 2.0
Tested up to: 2.9.1
Stable tag: 1.2.2

This plugin helps to keep some grammar rules in Czech language related to word wrapping, e.g. prepositions 'k', 's', 'v' and 'z' cannot be placed at the end of line.


== Description ==

For English, see below.

Czech: Upravujeme-li písemný dokument, radí nám Pravidla českého pravopisu nepsat neslabičné předložky v, s, z, k na konec řádku, ale psát je na stejný řádek se slovem, které nese přízvuk (např. ve spojení k mostu, s bratrem, v Plzni, z&nbsp;nádraží). Typografické normy jsou ještě přísnější: podle některých je nepatřičné ponechat na konci řádku jakékoli jednopísmenné slovo, tedy také předložky a spojky a, i, o, u;. Někteří pisatelé dokonce nechtějí z estetických důvodů ponechávat na konci řádků jakékoli jednoslabičné výrazy (např. ve, ke, ku, že, na, do, od, pod).

<a href="http://prirucka.ujc.cas.cz/?id=880" title="Více informací k problematice">Více informací</a> na webu Ústavu pro jazyk český, Akademie věd ČR.

Tento plugin řeší některé z uvedených příkladů: v textu nahrazuje běžné mezery za pevné tak, aby nedošlo k zalomení řádku v nevhodném místě.

Související odkazy:

* <a href="http://www.honza.info/category/wordpress/" title="Kategorie počítače na mých stránkách">Plugin Homepage</a>
* <a href="http://www.honza/info/" title="honza.info">Moje webové stránky</a>
* <a href="http://prirucka.ujc.cas.cz/?id=880" title="Více informací k problematice">Více informací</a> k této problematice na webu Ústavu pro jazyk český, Akademie věd ČR.

English: This plugin helps to keep some grammar rules in Czech language related to word wrapping, e.g. prepositions 'k', 's', 'v' and 'z' cannot be placed at the end of line.


== Installation ==

Czech:
1.	Nahrajte kompletní adresář pluginu do wp-content/plugins.
2.	Aktivujte plugin TopList.cz v administraci plug-inů.
3.	V konfiguraci pluginu můžete nastavit jednotlivé volby.

Ve výchozím stavu po první instalaci plug-inu je zapnuto pouze umísťování pevných mezer za předložky 'k', 's', 'v' a 'z'. Ostatní volby je možné zapnout v nastavení.

== Screenshots ==

1. Konfigurace pluginu
2. Příklad

== Changelog ==

= 1.0 =
* Initial release.
= 1.1 =
* Nyní umí vložit pevnou mezeru také za předložku (či jiné slovo), které se nachází na následujících pozicích: první slovo za otevírací závorkou, první slovo po nějakém tagu (např tag pro zapnutí italiky či tučného písma), na začátku odstavce.
* Rozšířen výchozí seznam zkratek, za něž se vkládá mezera
* Nahrazuje mezery v číslech za pevné mezery (např. v telefonním čísle zapsaném jako 800 123 456 nahradí mezery za pevné mezery, aby nebylo číslo rozděleno zalomením řádku).
* Interně přepsáno, již nevyužívá stávající filter wptexturize(), ale přidává vlastní filtr.
= 1.2 =
* Kompatibilita s WordPress 2.9
= 1.2.1 =
* Opravena chyba v HTML kódu konfigurace pluginu.
= 1.2.2 =
* Opravena chyba v konfiguraci.

== Licence ==

GNU General Public License version 2 applies
