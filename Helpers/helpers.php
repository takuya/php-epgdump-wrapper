<?php


function dd ( ...$args ) {
  //var_dump(debug_backtrace());
  dump( ...$args );
  exit;
}

;

function dump ( ...$args ) {
  foreach ( func_get_args() as $e ) {
    var_dump( $e );
  }
}

;
