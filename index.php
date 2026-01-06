<?php
session_start();

// Si ya inició sesión, redigire al foro
if (isset($_SESSION['user'])) {
    header("Location: foro.php");
    exit;
}

if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}
$error = null;

$users_file = 'users.json';
$users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : [];
if (!is_array($users)) $users = [];

if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (isset($users[$user]) && $users[$user]['password']  === $pass) {
        $_SESSION['user'] = $user;
        $_SESSION['avatar'] = $_SESSION['users'][$user]['avatar'];
        header("Location: foro.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro-Minecraft</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1 class="text-center">¡Bienvenido al Foro de Minecraft!</h1>
    <p class="text-center">
        Comparte tus conocimientos acerca de mods y trucos de minecraft.<br>
        <strong> ¡ ÚNETE A NOSOTROS SI NO TIENES CUENTA! </strong>
    </p>

<?php if ($error): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<h2>Iniciar sesión</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button name="login">Entrar</button>
</form>

<p class="text-center">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</div>
</body>
</html>
