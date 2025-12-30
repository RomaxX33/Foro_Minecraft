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

if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (isset($_SESSION['users'][$user]) && $_SESSION['users'][$user] === $pass) {
        $_SESSION['user'] = $user;
        header("Location: foro.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Foro-Minecraft</title>
</head>
<body>
<h1>¡Bienvenido al Foro de Minecraft!</h1>
<p>En este foro podrás compartir tus conocimientos acerca de mods y trucos de minecraft,<br>
 así mismo buscar mods en especificos y trucos que otros usuarios tengan publicado en sus cuentas.<br>
<span class="centrado"> ¡ÚNETE A NOSOTROS SI NO TIENES CUENTA! </span></p>

<?php if ($error): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<h2>Iniciar sesión</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button name="login">Entrar</button>
</form>

<p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</body>
</html>