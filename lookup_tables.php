<?php
/**
 * Definicije lookup tablica realiziranih asocijativnim nizovima čiji su
 * ključevi i vrijednosti "stringovi".
 *
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 *
 */

/**
 * Latiničnim slovima, kao "stringovima", (po potrebi posuđena su slova iz
 * ćirilice i grčkog alfabeta) pridružena su odgovarajuća glagoljična slova,
 * također kao "stringovi".  Za znak × oznaka _× označava njegovu varijantu ako
 * se nalazi na početku riječi, a [×] označava njegovu drugu (alternativnu ako
 * su samo dvije, za treću bi se moglo uzeti [[×]] i tako dalje analogno)
 * varijantu.  Redoslijed oznaka _[×] odgovarao bi varijanti druge varijante
 * kada se nalazi na početku riječi, a ne [_×] jer se početak riječi zaključuje
 * iz konteksta dok se obična druga varijanta zadaje beskontekstno.
 *
 * @var array LG latinica >> glagoljica
 * @name LG
 *
 */
define(
	'LG',
	array(
		'A' => "Ⰰ",
		'[A]' => "Ⱝ",
		'B' => "Ⰱ",
		'V' => "Ⰲ",
		'G' => "Ⰳ",
		'D' => "Ⰴ",
		'E' => "Ⰵ",
		"Ž" => "Ⰶ",
		"Ʒ" => "Ⰷ",
		'Z' => "Ⰸ",
		"Ï" => "Ⰹ",
		"_Ï" => "Ⰺ",
		"Θ" => "Ⱚ",
		'I' => "Ⰻ",
		'J' => "Ⰼ",
		'Đ' => "Ⰼ",
		'K' => "Ⰽ",
		'L' => "Ⰾ",
		'M' => "Ⰿ",
		'[M]' => "Ⱞ",
		'N' => "Ⱀ",
		'O' => "Ⱁ",
		'P' => "Ⱂ",
		'R' => "Ⱃ",
		'S' => "Ⱄ",
		'T' => "Ⱅ",
		'U' => "Ⱆ",
		'F' => "Ⱇ",
		'H' => "Ⱈ",
		"Ō" => "Ⱉ",
		"Ć" => "Ⱋ",
		'C' => "Ⱌ",
		"Č" => "Ⱍ",
		"Š" => "Ⱎ",
		"Ŭ" => "Ⱏ",
		"[Ŭ]" => "Ⱜ",
		"Ĭ" => "Ⱐ",
		"Ĕ" => "Ⱑ",
		"Ę" => "Ⱔ",
		"Ɛ" => "Ⱕ",
		"Ë" => "Ⱖ",
		"Ǫ" => "Ⱘ",
		"Ü" => "Ⱛ",

		'a' => "ⰰ",
		'[a]' => "ⱝ",
		'b' => "ⰱ",
		'v' => "ⰲ",
		'g' => "ⰳ",
		'd' => "ⰴ",
		'e' => "ⰵ",
		"đ" => "ⰵ",
		"ž" => "ⰶ",
		"ʒ" => "ⰷ",
		'z' => "ⰸ",
		"ï" => "ⰹ",
		"_ï" => "ⰺ",
		"θ" => "ⱚ",
		'i' => "ⰻ",
		'j' => "ⰼ",
		'đ' => "ⰼ",
		'k' => "ⰽ",
		'l' => "ⰾ",
		'm' => "ⰿ",
		'[m]' => "ⱞ",
		'n' => "ⱀ",
		'o' => "ⱁ",
		'p' => "ⱂ",
		'r' => "ⱃ",
		's' => "ⱄ",
		't' => "ⱅ",
		'u' => "ⱆ",
		'f' => "ⱇ",
		'h' => "ⱈ",
		"ō" => "ⱉ",
		"ć" => "ⱋ",
		'c' => "ⱌ",
		"č" => "ⱍ",
		"š" => "ⱎ",
		"ŭ" => "ⱏ",
		"[ŭ]" => "ⱜ",
		"ĭ" => "ⱐ",
		"ĕ" => "ⱑ",
		"ę" => "ⱔ",
		"ɛ" => "ⱕ",
		"ë" => "ⱖ",
		"ǫ" => "ⱘ",
		"ü" => "ⱛ"
	)
);

/**
 * Kao inverz nizu `{@link LG}`, ali sve varijante istog glagoljičnog slova, kao
 * "stringova", preslikavaju se u isto (jedinstveno) latinični slovo, također
 * kao "stringovi".  Dakle, usporedbe
 * `{@link GL}[{@link LG}['_×']] === {@link GL}[{@link LG}['×']]` i
 * `{@link GL}[{@link LG}['[×]']] === {@link GL}[{@link LG}['×']]` istinitne su
 * vrijednosti.  Također, ako se više slova (ključeva) u nizu `{@link LG}`
 * preslikava u isto slovo (vrijednost), tom je slovu (vrijednosti), kao kao
 * ključu, u nizu `{@link GL}` pridruženo samo jedno od početnih slova.
 *
 * @var array GL glagoljica >> latinica
 * @name GL
 *
 */
define(
	'GL',
	array(
		"Ⰰ" => 'A',
		"Ⱝ" => 'A',
		"Ⰱ" => 'B',
		"Ⰲ" => 'V',
		"Ⰳ" => 'G',
		"Ⰴ" => 'D',
		"Ⰵ" => 'E',
		"Ⰶ" => "Ž",
		"Ⰷ" => "Ʒ",
		"Ⰸ" => 'Z',
		"Ⰹ" => "Ï",
		"Ⰺ" => "Ï",
		"Ⱚ" => "Θ",
		"Ⰻ" => 'I',
		"Ⰼ" => 'J',
		"Ⰽ" => 'K',
		"Ⰾ" => 'L',
		"Ⰿ" => 'M',
		"Ⱞ" => 'M',
		"Ⱀ" => 'N',
		"Ⱁ" => 'O',
		"Ⱂ" => 'P',
		"Ⱃ" => 'R',
		"Ⱄ" => 'S',
		"Ⱅ" => 'T',
		"Ⱆ" => 'U',
		"Ⱇ" => 'F',
		"Ⱈ" => 'H',
		"Ⱉ" => "Ō",
		"Ⱋ" => "Ć",
		"Ⱌ" => 'C',
		"Ⱍ" => "Č",
		"Ⱎ" => "Š",
		"Ⱏ" => "Ŭ",
		"Ⱜ" => "Ŭ",
		"Ⱐ" => "Ĭ",
		"Ⱑ" => "Ĕ",
		"Ⱔ" => "Ę",
		"Ⱕ" => "Ɛ",
		"Ⱖ" => "Ë",
		"Ⱘ" => "Ǫ",
		"Ⱛ" => "Ü",

		"ⰰ" => 'a',
		"ⱝ" => 'a',
		"ⰱ" => 'b',
		"ⰲ" => 'v',
		"ⰳ" => 'g',
		"ⰴ" => 'd',
		"ⰵ" => 'e',
		"ⰶ" => "ž",
		"ⰷ" => "ʒ",
		"ⰸ" => 'z',
		"ⰹ" => "ï",
		"ⰺ" => "ï",
		"ⱚ" => "θ",
		"ⰻ" => 'i',
		"ⰼ" => 'j',
		"ⰽ" => 'k',
		"ⰾ" => 'l',
		"ⰿ" => 'm',
		"ⱞ" => 'm',
		"ⱀ" => 'n',
		"ⱁ" => 'o',
		"ⱂ" => 'p',
		"ⱃ" => 'r',
		"ⱄ" => 's',
		"ⱅ" => 't',
		"ⱆ" => 'u',
		"ⱇ" => 'f',
		"ⱈ" => 'h',
		"ⱉ" => "ō",
		"ⱋ" => "ć",
		"ⱌ" => 'c',
		"ⱍ" => "č",
		"ⱎ" => "š",
		"ⱏ" => "ŭ",
		"ⱜ" => "ŭ",
		"ⱐ" => "ĭ",
		"ⱑ" => "ĕ",
		"ⱔ" => "ę",
		"ⱕ" => "ɛ",
		"ⱖ" => "ë",
		"ⱘ" => "ǫ",
		"ⱛ" => "ü"
	)
);

/**
 * Imenima glagoljičnih slova, kao "stringovima", pridružene su njihove
 * latinične reprezentacije, također kao "stringovi".  Varijante [×] u nizu
 * `{@link LG_named}` postoje, ali varijante _× ne, to jest, ako je ~ ime
 * glagoljične reprezentacije znaka ×, onda je [~] ključ kojemu je pridruženo
 * [×], ali ključ _~ ne postoji i to ponovo jer se početak riječi zaključuje tek
 * iz konteksta.
 *
 * @var array LG_named imena na latinici >> latinični znakovi
 * @name LG_named
 *
 */
define(
	'LG_named',
	array(
		'Az' => 'A',
		'[Az]' => '[A]',
		'Buki' => 'B',
		'Vjedje' => 'V',
		'Glagoli' => 'G',
		'Dobro' => 'D',
		'Jest' => 'E',
		"Živjeti" => "Ž",
		'Zelo' => "Ʒ",
		'Zemlja' => 'Z',
		"Iže" => "Ï",
		'Fita' => "Θ",
		'I' => 'I',
		"Đerv" => 'J',
		'Kako' => 'K',
		'Ljudje' => 'L',
		'Mislite' => 'M',
		'[Mislite]' => '[M]',
		"Naš" => 'N',
		'On' => 'O',
		'Pokoj' => 'P',
		'Reci' => 'R',
		'Slovo' => 'S',
		'Tvrdo' => 'T',
		'Uk' => 'U',
		'Frt' => 'F',
		'Hjer' => 'H',
		'Ot' => "Ō",
		"Šta" => "Ć",
		'Ci' => 'C',
		"Črv" => "Č",
		"Ša" => "Š",
		'Jor' => "Ŭ",
		'[Jor]' => "[Ŭ]",
		"Štapić" => "[Ŭ]",
		'Jer' => "Ĭ",
		'Jat' => "Ĕ",
		'Es' => "Ę",
		'Jes' => "Ɛ",
		'Jo' => "Ë",
		'Us' => "Ǫ",
		"Ižica" => "Ü",

		'az' => 'a',
		'[az]' => '[a]',
		'buki' => 'b',
		'vjedje' => 'v',
		'glagoli' => 'g',
		'dobro' => 'd',
		'jest' => 'e',
		"živjeti" => "ž",
		'zelo' => "ʒ",
		'zemlja' => 'z',
		"iže" => "ï",
		'fita' => "θ",
		'i' => 'i',
		"đerv" => 'j',
		'kako' => 'k',
		'ljudje' => 'l',
		'mislite' => 'm',
		'[mislite]' => '[m]',
		"naš" => 'n',
		'on' => 'o',
		'pokoj' => 'p',
		'reci' => 'r',
		'slovo' => 's',
		'tvrdo' => 't',
		'uk' => 'u',
		'frt' => 'f',
		'hjer' => 'h',
		'ot' => "ō",
		"šta" => "ć",
		'ci' => 'c',
		"črv" => "č",
		"ša" => "š",
		'jor' => "ŭ",
		'[jor]' => "[ŭ]",
		"štapić" => "[ŭ]",
		'jer' => "ĭ",
		'jat' => "ĕ",
		'es' => "ę",
		'jes' => "ɛ",
		'jo' => "ë",
		'us' => "ǫ",
		"ižica" => "ü"
	)
);
