<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/PETPI/app/core/Auth.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/PETPI/app/models/Perrito.php';

Auth::check();

if ($_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: /PETPI/public/index.php');
    exit;
}

if (!isset($_POST['id_perro'], $_POST['motivo'])) {
    header('Location: /PETPI/public/perritos.php');
    exit;
}

$id = $_POST['id_perro'];
$motivo = trim($_POST['motivo']);

if (empty($motivo)) {
    die("El motivo es obligatorio.");
}

Perrito::eliminar($id, $_SESSION['usuario']['id_usuario'], $motivo);

header('Location: /PETPI/public/perritos.php');
exit;
