<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
// Busca o conteúdo atual
$stmt = $db->query('SELECT * FROM sobre LIMIT 1');
$sobre = $stmt->fetch(PDO::FETCH_ASSOC);

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim($_POST['texto'] ?? '');
    $imagem = $sobre['imagem'] ?? '';
    // Upload de nova imagem
    if (isset($_FILES['imagem_upload']) && $_FILES['imagem_upload']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagem_upload']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('sobre_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['imagem_upload']['tmp_name'], $destino)) {
                $imagem = $destino;
            } else {
                $erro = 'Erro ao salvar a imagem.';
            }
        } else {
            $erro = 'Formato de imagem não permitido.';
        }
    }
    if (!$erro) {
        if ($sobre) {
            $stmt = $db->prepare('UPDATE sobre SET texto=?, imagem=? WHERE id=?');
            $stmt->execute([$texto, $imagem, $sobre['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO sobre (texto, imagem) VALUES (?, ?)');
            $stmt->execute([$texto, $imagem]);
        }
        header('Location: sobre_admin.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Sobre - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-sobre { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-sobre label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-sobre textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-sobre input[type="file"] { margin-bottom: 16px; }
        .form-sobre button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-sobre button:hover { background: #ff7979; }
        .form-sobre .erro { color: red; margin-bottom: 10px; }
        .img-thumb { max-width: 180px; max-height: 180px; border-radius: 6px; margin-bottom: 10px; display: block; }
        .ok-msg { color: #28a745; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php">&larr; Voltar ao Painel Admin</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Editar Sobre a Academia</h2>
        <?php if (isset($_GET['ok'])): ?><div class="ok-msg">Alterações salvas com sucesso!</div><?php endif; ?>
        <form method="post" class="form-sobre" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="texto">Texto Sobre *</label>
            <textarea name="texto" id="texto" rows="6" required><?= htmlspecialchars($sobre['texto'] ?? '') ?></textarea>
            <label for="imagem_upload">Imagem (opcional)</label>
            <?php if (!empty($sobre['imagem'])): ?><img src="<?= htmlspecialchars($sobre['imagem']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="imagem_upload" id="imagem_upload" accept="image/*">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 