<?php 

spl_autoload_register(function ($class_name) {
  $modelo = __DIR__ . '/modelo/';
  $dao = __DIR__ . '/dao/';
  $pastas = [$modelo, $dao];
  foreach ($pastas as $pasta) {
    $arquivo = $pasta . $class_name . '.php';
    if (file_exists($arquivo)) {
      require_once $arquivo;
    }
  }

});


