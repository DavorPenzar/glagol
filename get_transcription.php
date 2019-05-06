<?php
/**
 * Skripta za dohvaćanje tarnskripcije.
 *
 * Metodom GET dohvaća se tekst za transkripciju (i smjer transkripcije), a u
 * JSON formatu ispisuje se njegova transkripcija: parametar 'text' zadaje tekst
 * za transkripciju (gotovi tekst ili JSON reprezentacija "stringa" teksta), a
 * parametar 'dir' je jedna od vrijednosti 'l2g' (transkribiraj s latinice na
 * glagoljicu) ili 'g2l' (transkribiraj s glagoljice na latinicu).  Povratni
 * objekt na ključu 'transcription' sadrži tekst transkripcije ("string"), a,
 * ako se pri transkripciji dogodila greška, na ključu 'transcription' je
 * nedefinirana vrijednost (null), dok je na ključu 'error' poruka greske
 * ("string").  Kod uspješne transkripcije ključ 'error' ne postoji).
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 */

require 'output_json.php';
require 'transcriptors.php';

// Inicijaliziraj varijable $text i $dir na NULL.
$text = NULL;
$dir = NULL;

/* Cijeli ostatak koda izvršava se u "try-catch" bloku.  Naime, svaka izbačena
 * iznimka ispisat će se slanjem JSON reprezentacije objekta {error: errmsg}
 * gdje je errmsg poruka izbačene iznimke. */

try
{
  // Ako $_POST i $_GET nisu definirani, izbaci iznimku.
  if (!(isset($_POST) && isset($_GET)))
    throw new Exception('Unexpected environment error.');

  // Ako $_POST i $_GET nisu nizovi, izbaci iznimku.
  if (!(is_array($_POST) && is_array($_GET)))
    throw new Exception('Unexpected environment error.');

  // Ako su metodom POST poslani neki argumenti, izbaci iznimku.
  if (!empty($_POST))
    throw new Exception('Parameters must be given using the GET method.');

  // Ako neki od parametara 'text' i 'dir' nisu poslani metodom GET, izbaci
  // iznimku.
  if (!(array_key_exists('text', $_GET) && array_key_exists('dir', $_GET)))
    throw new Exception("Parameters \"text\" and \"dir\" must be set.");

  // Ako je parametar 'text' "string", pokušaj ga dekodirati kao JSON i spremiti
  // u varijablu $text.
  if (is_string($_GET['text']))
    $text = json_decode($_GET['text']);

  // Ako vrijednost varijable $text (još uvijek) nije "string",
  // spremi doslovnu vrijednost $_GET['text'] u varijablu $text.
  if (!is_string($text))
    $text = $_GET['text'];

  // Spremi vrijednost $_GET['dir'] u varijablu $dir.
  $dir = $_GET['dir'];

  // Oslobodi memoriju.
  unset($_GET['text']);
  unset($_GET['dir']);

  // Ako je ostalo parametara poslanih metodom GET, izbaci iznimku.
  if (!empty($_GET))
    throw new Exception(
      'Unrecognised parameters: ' . implode(', ', array_keys($_GET)) . '.'
    );

  // Ako neka od vrijednosti $text i $dir nije "string", izbaci iznimku.
  if (!(is_string($text) && is_string($dir)))
    throw new Exception("Parameters \"text\" and \"dir\" must be strings.");

  // Ako $dir nije 'l2g' ni 'g2l', izbaci iznimku.
  if ($dir !== 'l2g' && $dir !== 'g2l')
    throw new Exception("Direction \"" . $dir . "\" not recognised.");

  // Ispiši traženu transkripciju u JSON formatu.
  output_json(
    array('transcription' => $dir === 'l2g' ? lat2gla($text) : gla2lat($text))
  );
}
catch (Exception $e)
{
  // Ako je bila izbačena iznimka, ispiši JSON objekt s objašnjenjem greške.
  // Dodatno, za transkripciju vrati nedefiniranu vrijednost.
  output_json(array('transcription' => NULL, 'error' => $e->getMessage()));
}
