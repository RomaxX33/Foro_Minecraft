<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$posts_file = 'posts.json';
$posts = [];

// Leer posts
if (file_exists($posts_file)) {
    $posts = json_decode(file_get_contents($posts_file), true) ?? []; //cambiar esto para la base de datos
}

// Crear post
if (isset($_POST['post_content'])) {
    $content = trim($_POST['post_content']);
    if ($content !== '') { 
        $posts[] = [
        'user' => $user,
        'content' => $content,
        'comments' => []
    ];
    file_put_contents($posts_file, json_encode($posts)); //debes cambiar esto para la base de datos
}
}

// Comentario
if (isset($_POST['comment_content'], $_POST['post_index'])) {
    $index = intval($_POST['post_index']);
    $comment = trim($_POST['comment_content']);
    if (isset($posts[$index])){
    $posts[$index]['comments'][] = [
        'user' => $user,
        'comment' => $comment
    ];
    file_put_contents($posts_file, json_encode($posts)); //cambiar esto tambien para la base de datos
} else {
    $error = "El Post al que intentas comentar no existe";
}
}

if (isset($_POST['delete_post'])) {
    $index = intval($_POST['delete_post']);

    // Solo borrar si el post pertenece al usuario logueado
    if (isset($posts[$index]) && $posts[$index]['user'] === $user) {
        unset($posts[$index]);
        $posts = array_values($posts); // Reindexar
        file_put_contents($posts_file, json_encode($posts));
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Foro Minecraft</title>
</head>
<body>
<h1>Foro - Bienvenido <?= htmlspecialchars($user) ?></h1>
<a href="logout.php">Cerrar sesión</a>

<h2>Crear nuevo post</h2>
<form method="POST">
    <textarea name="post_content" required placeholder="Escribe tu post..."></textarea><br>
    <button>Publicar</button>
</form>

<h2>Posts recientes</h2>
<?php foreach ($posts as $i => $post): ?>
<div style="border:1px solid #000; padding:10px; margin:10px;">
    <strong><?= htmlspecialchars($post['user']) ?></strong><br>
    <?= htmlspecialchars($post['content']) ?><br><br>

    <?php if ($post['user'] === $user): ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_post" value="<?= $i ?>">
            <button style="color:red;">Borrar post</button>
        </form>
    <?php endif; ?>
    
    <form method="POST">
        <input type="hidden" name="post_index" value="<?= $i ?>">
        <input type="text" name="comment_content" placeholder="Comentar..." required>
        <button>Comentar</button>
    </form>

    <?php if (!empty($post['comments'])): ?>
        <ul>
        <?php foreach ($post['comments'] as $c): ?>
            <li><strong><?= htmlspecialchars($c['user']) ?>:</strong> <?= htmlspecialchars($c['comment']) ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php endforeach; ?>

</body>
</html>