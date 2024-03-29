<?php
/**
 * Definicije funkcija za transkripciju tekstova s latinice na glagoljicu i
 * obratno.
 *
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 *
 */

// Učitaj skriptu `'lookup_tables.php'`.
require_once 'lookup_tables.php';

/**
 * Transkribiraj s latinice na glagoljicu.
 *
 * Znakovi na latinici zadaju se direktno (jedinstvenim znakom) ili imenom
 * glagoljičnog slova omeđenog znakovima `'/'`.  Druge varijante istog
 * glagoljočnog slova zadaju se tako da se znak (ili njegovo ime, unutar međa
 * `'/'`) zatvori slijeva znakom `'['`, a zdesna znakom `']'`.  Doslovni ispis
 * znakova `'/'`, `'['` i `']'` postiže se umetanjem znaka `'\'` neposredno
 * ispred takvog znaka.  Doslovni ispis znaka `'\'` postiže se dvama uzastopnim
 * znakovima `'\'`.  Unutar međa `'/.../'` znakovi `'\'`, `'['` i `']'` zadaju
 * se bez znaka `'\'` ispred, a znak `'/'` ne može postojati (prvi sljedeći znak
 * `'/'` nakon lijeve međe `'/'` smatra se desnom međom).  Slično vrijedi i za
 * međe `'[...]'`, osim što unutar tih međa ne može postojati znak `']'`, ali
 * mogu stajati znakovi `'\'`, `'/'` i `'['`.  Neprepoznati znakovi samo se
 * prepisuju.
 *
 * Iako glagoljica ima vlastiti sustav brojki, znamenke se ne "parsiraju" nego
 * se prepisuju kako su zadane.
 *
 * @param string $text tekst na latinici koji se transkribira
 * @return string transkripcija danog teksta na glagoljicu
 *
 */
function transcribe_latinic_to_glagolitic ($text)
{
	// Ako je previše argumenata dano, izbaci iznimku.
	if (func_num_args() > 1)
		throw new Exception('Too many arguments given.');

	// Ako `LG` i `LG_named` nisu definirani, izbaci iznimku.
	if (LG === null || LG_named === null)
		throw new Exception(
			'Unexpected environment error: array(s) `LG` and/or `LG_named` ' .
						'missing.'
		);

	// Ako `LG` i `LG_named` nisu nizovi, izbaci iznimku.
	if (!(is_array(LG) && is_array(LG_named)))
		throw new Exception(
			'Unexpected environment error: `LG` and/or `LG_named` are not ' .
						'arrays.'
		);

	// Provjeri tip argumenta.  Ako nije "string", izbaci iznimku.
	if (!is_string($text))
		throw new Exception('Argument must be a string.');

	// "Rascjepkaj" ulazni tekst po znakovima i spremi rezultat (niz znakova) u
	// `$lat_text`, a izlazni tekst inicijaliziraj na prazni niz.
	$lat_text = preg_split('//u', $text);
	$gla_text = array();

	// Oslobodi memoriju.
	unset($text);

	// Ako je varijabla `$space` istinita (`TRUE`), sljedeći znak smatra se
	// početkom nove riječi.  Prvi znak u tekstu po logičnoj je pretpostavci
	// početak riječi pa se `$space` inicijalizira na istinu.
	$space = TRUE;

	// Iteriraj po svim znakovima u nizu `$lat_text`.
	for ($i = 0; $i < sizeof($lat_text); ++$i)
	{
		// Ako je trenutni znak `'\'`, provjeri valjanost sljedećeg znaka.  Ako
		// je sljedeći znak `'\'`, `'/'`, `'['` ili `']'`, prepiši ga.  Ako
		// sljedeći znak ne postoji ili nije neki od navedenih znakova, izbaci
		// iznimku.
		if ($lat_text[$i] === "\\")
		{
			// Provjeri postoji li sljedeći znak i je li neki od valjanih
			// znakova.
			if ($i + 1 < sizeof($lat_text))
				if (
					$lat_text[$i + 1] === "\\" ||
					$lat_text[$i + 1] === '/' ||
					$lat_text[$i + 1] === '[' ||
					$lat_text[$i + 1] === ']'
				)
				{
					// Prepiši sljedeći znak.
					array_push($gla_text, $lat_text[++$i]);

					// Prijeđi na sljedeću iteraciju petlje (na sljedeći znak).
					continue;
				}

			// Izbaci iznimku zbog pogrešnog korištenja znaka '\'.
			throw new Exception("Escape character (`'\\'`) misuse.");
		}

		// Ovisno o trenutnom znaku, to jest, je li `'/'`, `'['`, `']'` ili
		// nešto četvrto (koje do sad nije bilo provjereno), postupi
		// odgovarajuće.
		switch ($lat_text[$i])
		{
			// Ako je trenutni znak `'/'`, pronađi prvi sljedeći znak `'/'` i u
			// `$gla_text` umetni slovo sa zadanim imenom.  Ako sljedeći znak
			// `'/'` ne postoji ili ako ime nije valjano, izbaci iznimku.
			case '/':
				// Pronađi indeks sljedeće međe `'/'` `$j`.
				$j = array_search(
										'/',
										array_slice($lat_text, $i + 1, NULL, TRUE)
								);

				// Ako sljedeći znak `'/'` nije pronađen, izbaci iznimku.
				if ($j === FALSE)
					throw new Exception("Special character (`'/'`) misuse.");

				// Dohvati sadržaj između međa `'/.../'` u "string" `$key`.
				$key = implode(array_slice($lat_text, $i + 1, $j - $i - 1));

				// Pomakni `$i` i oslobodi memoriju.
				$i = $j;
				unset($j);

				// Ako slovo sa zadanim imenom postoji, postavi `$key` na
				// njegovu latiničnu reprezentaciju.  Inače izbaci iznimku.
				if (array_key_exists($key, LG_named))
					$key = LG_named[$key];
				else
					throw new Exception("Named letter \"" . $key . "\" not found.");

				// Ako je $space istina i ako glagoljična reprezentacija znaka
				// `$key` ima specijalnu varijantu kada se nalazi na početku
				// riječi, postavi `$key` na `'_' . $key`.
				if ($space && array_key_exists('_' . $key, LG))
					$key = '_' . $key;

				// Umetni glagoljičnu reprezentaciju trenutnog znaka u
				// `$gla_text`.  Ako ona nije pronađena, a do sada bi `$key`
				// trebao biti valjani latinični znak koji se može
				// transkribirati, izbaci iznimku.
				if (array_key_exists($key, LG))
					array_push($gla_text, LG[$key]);
				else
					throw new Exception(
						"Unexpected transcription chain break up."
					);

				// Oslobodi memoriju.
				unset($key);

				// Postavi varijablu `$space` na laž.
				$space = FALSE;

				break;

			// Ako je trenutni znak `'['`, pronađi prvi sljedeći znak `']'` i u
			// `$gla_text` umetni slovo tražene varijante.  Ako sljedeći znak
			// `']'` ne postoji ili ako ime nije valjano, izbaci iznimku.
			case '[':
				// Pronađi indeks sljedeće međe `']'` `$j`.
				$j = array_search(
					']',
					array_slice($lat_text, $i + 1, NULL, TRUE)
				);

				// Ako sljedeći znak `']'` nije pronađen, izbaci iznimku.
				if ($j === FALSE)
					throw new Exception("Special character (`'['`) misuse.");

				// Dohvati sadržaj od međe `'['` do međe `']'` u "string"
								// `$key`.
				$key = implode(array_slice($lat_text, $i, $j - $i + 1));

				// Pomakni `$i` i oslobodi memoriju.
				$i = $j;
				unset($j);

				// Ako je `$space` istina i ako glagoljična reprezentacija znaka
				// `$key` ima specijalnu varijantu kada se nalazi na početku
				// riječi, postavi `$key` na `'_' . $key`.
				if ($space && array_key_exists('_' . $key, LG))
					$key = '_' . $key;

				// Provjeri postoji li tražena varijanta znaka i, ako postoji,
				// dodaj ju u niz `$gla_text`.  Inače izbaci iznimku.
				if (array_key_exists($key, LG))
					array_push($gla_text, LG[$key]);
				else
					throw new Exception(
						"Letter variant \"" . $key . "\" not found."
					);

				// Oslobodi memoriju.
				unset($key);

				// Postavi varijablu `$space` na laž.
				$space = FALSE;

				break;

			// Ako je trenutni znak `']'`, izbaci iznimku.
			case ']':
				// Izbaci iznimku.
				throw new Exception("Special character (`']'`) misuse.");

				// Prekini `switch`-naredbu.
				break;

			// Inače, ako je trenutni znak prepoznat, transkribiraj ga odnosno,
			// ako nije prepoznat, prepiši ga.
			default:
				// Ako je `$space` istina i ako glagoljična reprezentacija
				// trenutnog znaka ima specijalnu varijantu kada se nalazi na
				// početku riječi, postavi trenutni znak na
				// `'_' . $lat_text[$i]`.
				if ($space && array_key_exists('_' . $lat_text[$i], LG))
					$lat_text[$i] = '_' . $lat_text[$i];

				// Ako trenutni znak ima glagoljičnu reprezentaciju,
				// transkripciju dodaj u niz `$gla_text`.  Inače ga prepiši.
				if (array_key_exists($lat_text[$i], LG))
					array_push($gla_text, LG[$lat_text[$i]]);
				else
					array_push($gla_text, $lat_text[$i]);

				// Ako je trenutni znak slovo ili znamenka, postavi varijablu
				// `$space` na laž, inače ju postavi na istinu ako je znak
				// prikaziv.
				if ($lat_text[$i] !== '')
					if (ctype_print($lat_text[$i]))
					{
						if (ctype_alnum($lat_text[$i]))
							$space = FALSE;
						else
							$space = TRUE;
					}

				// Prekini `switch`-naredbu.
				break;
		}
	}

	// Oslobodi memoriju.
	unset($space);

	// Vrati znakove u nizu `$gla_text` spojene u jedinstveni "string".
	return implode($gla_text);
}

/**
 * Transkribiraj s glagoljice na latinicu.
 *
 * Neprepoznati znakovi samo se prepisuju.
 *
 * Iako glagoljica ima vlastiti sustav brojki, i neka slova u zadanom tekstu
 * mogu predstavljati brojeve, a ne riječi, sva se glagoljična slova
 * transkribiraju u odgovarajuća latinična slova.
 *
 * @param string $text tekst na glagoljici koji se transkribira
 * @return string transkripcija danog teksta na latinicu
 *
 */
function transcribe_glagolitic_to_latinic ($text)
{
	// Ako je previše argumenata dano, izbaci iznimku.
	if (func_num_args() > 1)
		throw new Exception('Too many arguments given.');

	// Ako `GL` nije definiran, izbaci iznimku.
	if (GL === null)
		throw new Exception(
			'Unexpected environment error: array `GL` missing.'
		);

	// Ako `GL` nije niz, izbaci iznimku.
	if (!is_array(GL))
		throw new Exception(
			'Unexpected environment error: `GL` is not an array.'
		);

	// Provjeri tip argumenta.  Ako nije "string", izbaci iznimku.
	if (!is_string($text))
		throw new Exception('Argument must be a string.');

	// Vrati dani tekst sa izvršenim zamjenama glagoljičnih slova latiničnim.
	return str_replace(array_keys(GL), GL, $text);
}
