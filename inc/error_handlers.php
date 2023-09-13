<?php
// establecer el tipo de contenido como JSON
header('Content-Type: application/json');

ini_set('display_errors', 0);

set_error_handler('error_handler');
// register_shutdown_function('fatal_error_handler');

function error_handler($errno, $errstr, $errfile, $errline)
{
  if (!(error_reporting() & $errno)) {
    return;
  }

  echo json_encode([
    'error' => [
      'message' => $errstr,
      'file' => $errfile,
      'line' => $errline
    ]
  ]);
}

function fatal_error_handler()
{
  $error = error_get_last();
  $errorReporting = error_reporting();

  if ($error && $errorReporting && $error['type'] === E_ERROR) {
    echo json_encode([
      'error' => [
        'message' => $error['message'],
        'file' => $error['file'],
        'line' => $error['line']
      ]
    ]);
  }
}
?>