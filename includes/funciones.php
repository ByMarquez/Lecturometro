<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}
function isAuth_registrar_libro() : void {
    if(!isset($_SESSION['registrar_libro']) || !boolval($_SESSION['registrar_libro'])) {
        header('Location: /dashboard');
    }
}
function isAuth_registrar_usuarios() : void {
    if(!isset($_SESSION['registrar_usuarios']) || !boolval($_SESSION['registrar_usuarios'])) {
        header('Location: /dashboard');
    }
}