<?php

require 'lookup_tables.php';

function lat2gla ($text)
{
  global $LG, $LG_named;

  if (!(isset($LG) && isset($LG_named)))
    throw new Exception('Unexpected error.');

  if (!(is_array($LG) && is_array($LG)))
    throw new Exception('Unexpected error.');

  if (!is_string($text))
    throw new Exception('Argument must be a string.');

  $text = preg_split('//u', $text);
  $new_text = array();

  $space = TRUE;
  for ($i = 0; $i < sizeof($text); ++$i)
  {
    if ($text[$i] === '')
      continue;

    if ($text[$i] === "\\")
    {
      if ($i + 1 < sizeof($text))
        if (
          $text[$i + 1] === '/' ||
          $text[$i + 1] === '[' ||
          $text[$i + 1] === ']'
        )
        {
          array_push($new_text, $text[++$i]);

          continue;
        }

      throw new Exception("Escape character (\"\\\") misuse.");
    }

    if ($text[$i] === '' || ctype_space($text[$i]))
    {
      $space = TRUE;

      array_push($new_text, $text[$i]);

      continue;
    }

    switch ($text[$i])
    {
      case '/':
        $j = $i;

        for ($j = $i + 1; $j < sizeof($text); ++$j)
          if ($text[$j] === '/')
            break;

        if ($j === sizeof($text))
          throw new Exception("Special character (\"/\") misuse.");

        $key = implode(array_slice($text, $i + 1, $j - $i - 1));

        $i = $j;
        unset($j);

        if (array_key_exists($key, $LG_named))
          $key = $LG_named[$key];
        else
          throw new Exception("Named letter \"" . $key . "\" not found.");

        if ($space && array_key_exists('_' . $key, $LG))
          $key = '_' . $key;

        if (array_key_exists($key, $LG))
          array_push($new_text, $LG[$key]);
        else
          throw new Exception("Unexpected transcription chain break up.");

        unset($key);

        break;
      case '[':
        $j = $i;

        for ($j = $i + 1; $j < sizeof($text); ++$j)
          if ($text[$j] === ']')
            break;

        if ($j === sizeof($text))
          throw new Exception("Special character (\"[\") misuse.");

        $key = implode(array_slice($text, $i, $j - $i + 1));

        $i = $j;
        unset($j);

        if ($space && array_key_exists('_' . $key, $LG))
          $key = '_' . $key;

        if (array_key_exists($key, $LG))
          array_push($new_text, $LG[$key]);
        else
          throw new Exception("Letter variant \"" . $key . "\" not found.");

        unset($key);

        break;
      case ']':
        throw new Exception("Special character (\"]\") misuse.");

        break;
      default:
        if ($space && array_key_exists('_' . $text[$i], $LG))
          $text[$i] = '_' . $text[$i];

        if (array_key_exists($text[$i], $LG))
          array_push($new_text, $LG[$text[$i]]);
        else
          array_push($new_text, $text[$i]);
    }

    $space = FALSE;
  }

  return implode($new_text);
}

function gla2lat ($text)
{
  global $GL;

  if (!isset($GL))
    throw new Exception('Unexpected error.');

  if (!is_array($GL))
    throw new Exception('Unexpected error.');

  if (!is_string($text))
    throw new Exception('Argument must be a string.');

  return str_replace(array_keys($GL), $GL, $text);
}
