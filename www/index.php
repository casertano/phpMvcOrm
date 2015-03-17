<?php
// Instância da classe de controle do auto-load.
require_once '../model/system/kernel/LoaderClass.php';
$loaderClass = new LoaderClass();

// Inicia a aplicação.
$application = new Application();
$application->start();

?>