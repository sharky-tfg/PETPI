<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/PETPI/app/core/Auth.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/PETPI/app/models/Perrito.php';

Auth::check();

if ($_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: /PETPI/public/index.php');
    exit;
}

if (!isset($_POST['id_perro'])) {
    header('Location: /PETPI/public/perritos.php');
    exit;
}

$id = $_POST['id_perro'];

Perrito::eliminarPerrito($id, $_SESSION['usuario']['id_usuario']);

header('Location: /PETPI/public/perritos.php');
exit;
