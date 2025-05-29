<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

// Cria a tabela se não existir
$db = getDB();
$db->exec('CREATE TABLE IF NOT EXISTS home_content (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    banner_titulo TEXT,
    banner_texto TEXT,
    banner_img TEXT,
    info1_img TEXT,
    info1_texto TEXT,
    info2_img TEXT,
    info2_texto TEXT
)');

// Busca o conteúdo atual (só 1 registro)
$stmt = $db->query('SELECT * FROM home_content LIMIT 1');
$content = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$content) {
    $db->exec("INSERT INTO home_content (banner_titulo, banner_texto, banner_img, info1_img, info1_texto, info2_img, info2_texto) VALUES ('Bem-Vindo à BigGym', 'MAIS QUE UMA ACADEMIA!', '', '', '', '', '')");
    $stmt = $db->query('SELECT * FROM home_content LIMIT 1');
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $banner_titulo = trim($_POST['banner_titulo'] ?? '');
    $banner_texto = trim($_POST['banner_texto'] ?? '');
    $banner_img = $content['banner_img'];
    $info1_img = $content['info1_img'];
    $info1_texto = trim($_POST['info1_texto'] ?? '');
    $info2_img = $content['info2_img'];
    $info2_texto = trim($_POST['info2_texto'] ?? '');

    // Uploads
    if (isset($_FILES['banner_img']) && $_FILES['banner_img']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['banner_img']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('banner_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['banner_img']['tmp_name'], $destino)) {
                $banner_img = $destino;
            } else {
                $erro = 'Erro ao salvar a imagem do banner.';
            }
        } else {
            $erro = 'Formato de imagem do banner não permitido.';
        }
    }
    if (isset($_FILES['info1_img']) && $_FILES['info1_img']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['info1_img']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('info1_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['info1_img']['tmp_name'], $destino)) {
                $info1_img = $destino;
            } else {
                $erro = 'Erro ao salvar a imagem 1.';
            }
        } else {
            $erro = 'Formato de imagem 1 não permitido.';
        }
    }
    if (isset($_FILES['info2_img']) && $_FILES['info2_img']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['info2_img']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('info2_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['info2_img']['tmp_name'], $destino)) {
                $info2_img = $destino;
            } else {
                $erro = 'Erro ao salvar a imagem 2.';
            }
        } else {
            $erro = 'Formato de imagem 2 não permitido.';
        }
    }

    if (!$erro) {
        $stmt = $db->prepare('UPDATE home_content SET banner_titulo=?, banner_texto=?, banner_img=?, info1_img=?, info1_texto=?, info2_img=?, info2_texto=? WHERE id=?');
        $stmt->execute([$banner_titulo, $banner_texto, $banner_img, $info1_img, $info1_texto, $info2_img, $info2_texto, $content['id']]);
        header('Location: editar_pagina.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Página Principal - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-home { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-home label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-home input[type="text"], .form-home textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-home input[type="file"] { margin-bottom: 16px; }
        .form-home button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-home button:hover { background: #ff7979; }
        .form-home .erro { color: red; margin-bottom: 10px; }
        .form-home .ok { color: green; margin-bottom: 10px; }
        .img-thumb { max-width: 120px; max-height: 120px; border-radius: 6px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php">&larr; Voltar ao Painel Admin</a>
        </nav>
    </header>
    <main>
        <h2>Editar Página Principal</h2>
        <form method="post" class="form-home" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <?php if (isset($_GET['ok'])): ?><div class="ok">Alterações salvas com sucesso!</div><?php endif; ?>
            <label for="banner_titulo">Título do Banner</label>
            <input type="text" name="banner_titulo" id="banner_titulo" value="<?= htmlspecialchars($content['banner_titulo']) ?>">
            <label for="banner_texto">Texto do Banner</label>
            <textarea name="banner_texto" id="banner_texto" rows="3"><?= htmlspecialchars($content['banner_texto']) ?></textarea>
            <label for="banner_img">Imagem do Banner (envie um arquivo para trocar)</label>
            <?php if ($content['banner_img']): ?><img src="<?= htmlspecialchars($content['banner_img']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="banner_img" id="banner_img" accept="image/*">
            <hr>
            <label for="info1_texto">Texto da Camiseta/Card 1</label>
            <input type="text" name="info1_texto" id="info1_texto" value="<?= htmlspecialchars($content['info1_texto']) ?>">
            <label for="info1_img">Imagem da Camiseta/Card 1 (envie um arquivo para trocar)</label>
            <?php if ($content['info1_img']): ?><img src="<?= htmlspecialchars($content['info1_img']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="info1_img" id="info1_img" accept="image/*">
            <hr>
            <label for="info2_texto">Texto da Camiseta/Card 2</label>
            <input type="text" name="info2_texto" id="info2_texto" value="<?= htmlspecialchars($content['info2_texto']) ?>">
            <label for="info2_img">Imagem da Camiseta/Card 2 (envie um arquivo para trocar)</label>
            <?php if ($content['info2_img']): ?><img src="<?= htmlspecialchars($content['info2_img']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="info2_img" id="info2_img" accept="image/*">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 