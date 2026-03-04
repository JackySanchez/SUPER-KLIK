<?php
session_start();
$accion = $_POST['accion'] ?? '';

// --- CASO 1: LOGIN ---
if ($accion === 'login') {
    $user = $_POST['usuario'] ?? '';
    $pass = $_POST['password'] ?? '';
    $archivo = "usuarios.txt";
    $valido = false;

    if (file_exists($archivo)) {
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lineas as $l) {
            list($u, $p) = explode("|", $l);
            if ($user === $u && $pass === $p) {
                $valido = true;
                break;
            }
        }
    }

    if ($valido) {
        $_SESSION['usuario'] = $user;
        header("Location: index.php"); // ESTO HACE QUE SE MUEVA A LA PRINCIPAL
        exit;
    } else {
        header("Location: login.php?error=credenciales");
        exit;
    }
}

// --- CASO 2: REGISTRO ---
if ($accion === 'registro') {
    $nuevo_u = $_POST['nuevo_usuario'] ?? '';
    $nuevo_p = $_POST['nueva_password'] ?? '';

    if (!empty($nuevo_u) && !empty($nuevo_p)) {
        $linea = $nuevo_u . "|" . $nuevo_p . PHP_EOL;
        file_put_contents("usuarios.txt", $linea, FILE_APPEND);
        
        $_SESSION['usuario'] = $nuevo_u; // Logueo automático tras registrar
        header("Location: index.php");
        exit;
    }
}