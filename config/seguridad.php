<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION["usuario_id"])) {
        header("Location: ../views/login.php");
        exit;
    }
}

function require_rol($rol) {
    require_login();
    if (($_SESSION["usuario_rol"] ?? "") !== $rol) {
        header("Location: ../src/index.php");
        exit;
    }
}

function require_roles($roles) {
    require_login();
    $actual = $_SESSION["usuario_rol"] ?? "";
    if (!in_array($actual, $roles, true)) {
        header("Location: ../src/index.php");
        exit;
    }
}
