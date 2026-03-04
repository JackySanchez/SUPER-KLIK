<?php
session_start();
if (isset($_SESSION['usuario'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | KLIK</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilo rápido para ocultar el registro al inicio */
        #form-registro { display: none; }
    </style>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-header">
            <div class="logo-circle">K</div>
            <h1 class="brand-name">KLIK</h1>
            <p class="brand-subtitle">SISTEMA DE GESTIÓN</p>
        </div>

        <div class="login-tabs">
            <button class="tab-btn active" onclick="mostrarTab('login')">Ingresar</button>
            <button class="tab-btn" onclick="mostrarTab('registro')">Registro</button>
        </div>

        <form action="Procesar.php" method="POST" id="form-login">
            <div class="input-group">
                <label>Usuario</label>
                <input type="text" name="usuario" placeholder="TU NOMBRE" required>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="•••••••••" required>
            </div>
            <button type="submit" name="accion" value="login" class="btn-acceder">ACCEDER</button>
        </form>

        <form action="Procesar.php" method="POST" id="form-registro">
            <div class="input-group">
                <label>Nuevo Usuario</label>
                <input type="text" name="nuevo_usuario" placeholder="CREA TU USUARIO" required>
            </div>
            <div class="input-group">
                <label>Nueva Contraseña</label>
                <input type="password" name="nueva_password" placeholder="•••••••••" required>
            </div>
            <button type="submit" name="accion" value="registro" class="btn-acceder" style="background:#00a896;">REGISTRARSE</button>
        </form>
    </div>

    <script>
        function mostrarTab(tipo) {
            const btnLogin = document.querySelectorAll('.tab-btn')[0];
            const btnReg = document.querySelectorAll('.tab-btn')[1];
            if (tipo === 'login') {
                document.getElementById('form-login').style.display = 'block';
                document.getElementById('form-registro').style.display = 'none';
                btnLogin.classList.add('active');
                btnReg.classList.remove('active');
            } else {
                document.getElementById('form-login').style.display = 'none';
                document.getElementById('form-registro').style.display = 'block';
                btnLogin.classList.remove('active');
                btnReg.classList.add('active');
            }
        }
    </script>
</body>
</html>