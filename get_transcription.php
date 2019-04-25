<?php

session_start();

require 'output_json.php';
require 'transcriptors.php';

$text = NULL;
$dir = NULL;

try
{
  if (array_key_exists('text', $_GET) && array_key_exists('dir', $_GET))
  {
    $text = strval($_GET['text']);
    $dir = strval($_GET['dir']);

    unset($_GET['text']);
    unset($_GET['dir']);

    if (!empty($_GET))
      throw new Exception(
        'Unrecognised parameters ' . implode(', ', array_keys($_GET)) . '.'
      );
  }
  else
    throw new Exception("Parameters \"text\" and \"dir\" must be set.");

  if ($dir !== 'l2g' && $dir !== 'g2l')
    throw new Exception("Direction \"" . $dir . "\" not recognised.");

  output_json(
    $dir === 'l2g' ? latin2glagolitic($text) : glagolitic2latin($text)
  );
}
catch (Exception $e)
{
  output_json(array('error' => $e->getMessage()));
}
