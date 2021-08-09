<?php
/**
 * Skripta za dohvaćanje tarnskripcije.
 *
 * Metodom `GET` dohvaća se tekst za transkripciju (i smjer transkripcije), a u
 * JSON formatu ispisuje se njegova transkripcija: parametar `'text'` zadaje
 * tekst za transkripciju (gotovi tekst ili JSON reprezentacija "stringa"
 * teksta), a parametar `'dir'` jedna je od vrijednosti `'l2g'` (transkribiraj s
 * latinice na glagoljicu) ili `'g2l'` (transkribiraj s glagoljice na latinicu).
 * Povratni objekt na ključu `'transcription'` sadrži tekst transkripcije
 * ("string"), a, ako se pri transkripciji dogodila greška, na ključu
 * `'transcription'` je nedefinirana vrijednost (`null`), dok je na ključu
 * `'error'` poruka greške ("string").  Kod uspješne transkripcije ključ
 * `'error'` ne postoji.
 *
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 *
 */

// Učitaj skripte `'output_json.php'` i `'transcriptors.php'`.
require_once 'output_json.php';
require_once 'transcriptors.php';

// Inicijaliziraj varijable `$text` i `$dir` na `NULL`.
$text = NULL;
$dir = NULL;

/* Cijeli ostatak kôda izvršava se u "try-catch" bloku.  Naime, svaka izbačena
 * iznimka ispisat će se slanjem JSON reprezentacije objekta
 * `{transcription: null, error: errmsg}` gdje je `errmsg` poruka izbačene
 * iznimke. */

try
{
	// Ako `$_SERVER` nije definiran, izbaci iznimku.
	if (!isset($_SERVER))
		throw new Exception(
			"Unexpected environment error: array `\$_SERVER` missing."
		);

	// Ako `$_SERVER` nije niz, izbaci iznimku.
	if (!is_array($_SERVER))
		throw new Exception(
			"Unexpected environment error: `\$_SERVER` is not an array."
		);

	// Ako kljuc `'REQUEST_METHOD'` ne postoji u nizu `$_SERVER`, izbaci
	// iznimku.
	if (!array_key_exists('REQUEST_METHOD', $_SERVER))
		throw new Exception(
			"Unexpected environment error: key `\"REQUEST_METHOD\"` missing " .
						"in array `\$_SERVER`."
		);

	// Ako zahtjev nije poslan metodom `GET`, izbaci iznimku.
	if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET'))
		throw new Exception('The request method must be `GET`.');

	// Ako `$_GET` nije definiran, izbaci iznimku.
	if (!isset($_GET))
		throw new Exception(
			"Unexpected environment error: array `\$_GET` missing."
		);

	// Ako `$_GET` nije niz, izbaci iznimku.
	if (!is_array($_GET))
		throw new Exception(
			"Unexpected environment error: `\$_GET` is not an array."
		);

	// Ako parametri `'text'` i `'dir'` nisu zadani, izbaci iznimku.
	if (!(array_key_exists('text', $_GET) && array_key_exists('dir', $_GET)))
		throw new Exception(
			'Missing parameter(s): ' .
			implode(', ', array_diff(array('text', 'dir'), array_keys($_GET)))
		);

	// Ako je parametar `'text'` "string", pokušaj ga dekodirati kao JSON i
	// spremiti u varijablu $text.
	if (is_string($_GET['text']))
	{
		try
		{
			$text = json_decode($_GET['text'], null, 512, JSON_THROW_ON_ERROR);
		}
		catch (JsonException $ex)
		{
		}
	}

	// Ako vrijednost varijable `$text` (još uvijek) nije "string",
	// spremi doslovnu vrijednost `$_GET['text']` u varijablu `$text`.
	if (!is_string($text))
		$text = $_GET['text'];

	// Spremi vrijednost `$_GET['dir']` u varijablu `$dir`.
	$dir = $_GET['dir'];

	// Ako je ostalo parametara poslanih metodom `GET`, izbaci iznimku.
	if (sizeof($_GET) > 2)
		throw new Exception(
			'Unrecognised parameters: ' .
			implode(', ', array_diff(array_keys($_GET), array('text', 'dir'))) .
			'.'
		);

	// Ako neka od vrijednosti `$text` i `$dir` nije "string", izbaci iznimku.
	if (!(is_string($text) && is_string($dir)))
		throw new Exception("Parameters must be strings.");

	// Detektiraj traženi smjer transkripcije.
	$l2g = !(boolean)strcasecmp($dir, 'l2g');
	$g2l = !(boolean)strcasecmp($dir, 'g2l');

	// Ako `$dir` nije `'l2g'` ni `'g2l'`, izbaci iznimku.
	if (!($l2g || $g2l))
		throw new Exception("Direction \"" . $dir . "\" not recognised.");

	// Ispiši traženu transkripciju u JSON formatu.
	output_json(
		array(
			'transcription' => $l2g ?
				transcribe_latinic_to_glagolitic($text) :
				transcribe_glagolitic_to_latinic($text)
		)
	);
}
catch (Exception $ex)
{
	// Ako je bila izbačena iznimka, ispiši JSON objekt s objašnjenjem greške.
	// Dodatno, za transkripciju vrati nedefiniranu vrijednost.
	output_json(array('transcription' => NULL, 'error' => $ex->getMessage()));
}
