<?php

require 'lookup_tables.php';

function latin2glagolitic ($text)
{
  global $LG, $LG_named;

  if (!is_string($text))
    throw new Exception('Argument must be a string.');

  $text = preg_split('//u', $text);
  $new_text = array();

  $space = FALSE;
  for ($i = 0; $i < sizeof($text); ++$i)
  {
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

    $space = FALSE;

    if ($text[$i] === '/')
    {
      $j = $i;

      for ($j = $i + 1; $j < sizeof($text); ++$j)
      {
        if ($text[$j] === "\\")
        {
          if ($j + 1 < sizeof($text))
            if (
              $text[$j + 1] === '/' ||
              $text[$j + 1] === '[' ||
              $text[$j + 1] === ']'
            )
            {
              ++$j;

              continue;
            }

          throw new Exception("Escape character (\"\\\") misuse.");
        }

        if ($text[$j] === '/')
          break;
      }

      if ($j === sizeof($text))
        throw new Exception("Special character (\"/\") misuse.");

      $key = implode(array_slice($text, $i + 1, $j - $i - 1));

      $i = $j;
      unset($j);

      if (array_key_exists($key, $LG_named))
      {
        $key = $LG_named[$key];

        if ($space && $key === 'Ï' || $key === 'ï')
          array_push($new_text, $LG['_' . $key]);
        else
          array_push($new_text, $LG[$key]);
      }
      else
        throw new Exception("Named letter \"" . $key . "\" not found.");

      unset($key);
    }
    elseif ($text[$i] === '[')
    {
      $j = $i;

      for ($j = $i + 1; $j < sizeof($text); ++$j)
      {
        if ($text[$j] === "\\")
        {
          if ($j + 1 < sizeof($text))
            if (
              $text[$j + 1] === '/' ||
              $text[$j + 1] === '[' ||
              $text[$j + 1] === ']'
            )
            {
              ++$j;

              continue;
            }

          throw new Exception("Escape character (\"\\\") misuse.");
        }

        if ($text[$j] === ']')
          break;
      }

      if ($j === sizeof($text))
        throw new Exception("Special character (\"[\") misuse.");

      $key = implode(array_slice($text, $i, $j - $i + 1));

      $i = $j;
      unset($j);

      if (array_key_exists($key, $LG))
        array_push($new_text, $LG[$key]);
      else
        throw new Exception("Letter variant \"" . $key . "\" not found.");

      unset($key);
    }
    elseif ($text[$i] === ']')
      throw new Exception("Special character (\"]\") misuse.");
    else
    {
      if ($space && $text[$i] === 'Ï' || $text[$i] === 'ï')
        array_push($new_text, $LG['_' . $text[$i]]);
      else
      {
        if (array_key_exists($text[$i], $LG))
          array_push($new_text, $LG[$text[$i]]);
        else
          array_push($new_text, $text[$i]);
      }
    }
  }

  return implode($new_text);
}

function glagolitic2latin ($text)
{
  global $GL;

  if (!is_string($text))
    throw new Exception('Argument must be a string.');

  return str_replace(array_keys($GL), $GL, $text);
}
