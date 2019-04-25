<?php

function output_json ($message, $exit_status = 0)
{
  header('Content-Type: Application/json; charset=utf-8');

  echo json_encode($message);

  if (isset($exit_status))
  {
    flush();

    exit($exit_status);
  }
}
