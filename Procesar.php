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
            // Separamos el usuario y la clave que están en el TXT
            list($u, $p) = explode("|", $l);

            // CAMBIO AQUÍ: Usamos password_verify en lugar de ===
            if ($user === $u && password_verify($pass, $p)) {
                $valido = true;
                break;
            }
        }
    }

    if ($valido) {
        $_SESSION['usuario'] = $user;
        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?error=credenciales");
        exit;
    }
}

// --- CASO 2: REGISTRO ---
if ($accion === 'registro') {
    $nuevo_u = $_POST['nuevo_usuario'] ?? '';
    $nuevo_p = $_POST['nueva_password'] ?? ''; // <--- Aquí recibes la clave normal

    if (!empty($nuevo_u) && !empty($nuevo_p)) {
        
        // --- AQUÍ ENTRA TU CÓDIGO DE ENCRIPTACIÓN ---
        // Convertimos la clave "1234" en algo como "$2y$10$..."
        $password_encriptada = password_hash($nuevo_p, PASSWORD_DEFAULT);
        
        // Guardamos en el archivo: Usuario | Clave Encriptada
        $linea = $nuevo_u . "|" . $password_encriptada . PHP_EOL;
        
        file_put_contents("usuarios.txt", $linea, FILE_APPEND);
        
        header("Location: login.php?registro=exito");
        exit;
    }
}
