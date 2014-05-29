=== Zalomení ===
Contributors: honza.skypala
Donate link: http://www.honza.info
Tags: grammar, Czech
Requires at least: 3.5.1
Tested up to: 3.9.1
Stable tag: 1.3

This plugin helps to keep some grammar rules in Czech language related to word wrapping, e.g. prepositions 'k', 's', 'v' and 'z' cannot be placed at the end of line.


== Description ==

For English, see below.

Czech: Upravujeme-li písemný dokument, radí nám Pravidla českého pravopisu nepsat neslabičné předložky v, s, z, k na konec řádku, ale psát je na stejný řádek se slovem, které nese přízvuk (např. ve spojení k mostu, s bratrem, v Plzni, z&nbsp;nádraží). Typografické normy jsou ještě přísnější: podle některých je nepatřičné ponechat na konci řádku jakékoli jednopísmenné slovo, tedy také předložky a spojky a, i, o, u;. Někteří pisatelé dokonce nechtějí z estetických důvodů ponechávat na konci řádků jakékoli jednoslabičné výrazy (např. ve, ke, ku, že, na, do, od, pod).

<a href="http://prirucka.ujc.cas.cz/?id=880" title="Více informací k problematice">Více informací</a> na webu Ústavu pro jazyk český, Akademie věd ČR.

Tento plugin řeší některé z uvedených příkladů: v textu nahrazuje běžné mezery za pevné tak, aby nedošlo k zalomení řádku v nevhodném místě.

English: This plugin helps to keep some grammar rules in Czech language related to word wrapping, e.g. prepositions 'k', 's', 'v' and 'z' cannot be placed at the end of line.


== Installation ==

1.	Nahrajte kompletní adresář pluginu do wp-content/plugins.
2.	Aktivujte plugin Zalomení v administraci plug-inů.
3.	V Nastavení->Zobrazování můžete nastavit jednotlivé volby.

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
= 1.2.3 =
* Opraveno volání funkce add_options_page tak, aby nepoužívalo již nepodporovaný formát.
= 1.2.4 =
* Dvojité volání nahrazovací funkce, plugin nefungoval pro dvě příslušná slova nacházející se za sebou (např. pokud by byly zapnuty pevné mezery za předložkami i za spojkami, pak ve výrazu "a s někým" by došlo k nahrazení mezery za "a", ale již ne za "s")
* Nastavení pluginu přemístěno na stránku Nastavení->Zobrazování, je zbytečné, aby měl plugin celou vlastní stránku s nastavením
= 1.3 =
* Změna licence
* Změna ukládání nastavení (interní; původně pole proměnných, nyní jednotlivé proměnné samostatně, snad to vyřeší problémy některých uživatelů s ukládáním nastavení)
* Nová funkcionalita: zabránění zalomení po řadové číslovce (včetně data, např. 1. ledna)
* Nová funkcionalita: uživatelsky definované termíny, které nesmějí být zalomeny
* Screenshoty přesunuty do adresáře assets, aby se zbytečně nestahovaly uživatelům do jejich instalací WordPressu
* Plug-in předělán na PHP třídu, pro lepší izolaci a přehlednost
* WordPress již nevolá activation-hook při aktualizaci pluginu na novou verzi; aktualizace testována a volána v rámci admin_init()
= 1.4 =
* Zalomení po řadové číslovce nyní podporuje číslovku jako navazující slovo; takto je zajištěno nezalomení např. u data zapsaného ve formátu 1. 1. 2014
* Drobné optimalizace

== Licence ==

WTFPL License 2.0 applies

           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                   Version 2, December 2004

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

 0. You just DO WHAT THE FUCK YOU WANT TO.

== ToDo's ==

1. mezi číslo a jednotku (značku), např. 10 kg, 10,12 m, 10 min., 15 %, ...
2. mezi den a měsíc u datumu, kde rok už je možné oddělit, - např 15. 8. /2014
3. v měřítcích map, rozměrů, apod. - např 1:250000 či 1 : 250 000, 5 : 3, ...
Viz soupis těchto pravidel na http://prirucka.ujc.cas.cz/?id=880
- jedná se o 4. až 8. odrážku - pravidlo