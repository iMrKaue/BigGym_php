<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: servicos_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('SELECT * FROM servicos WHERE id = ?');
$stmt->execute([$id]);
$servico = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$servico) {
    header('Location: servicos_admin.php');
    exit;
}

$erro = '';
$imagem = $servico['imagem'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem = $servico['imagem'];
    // Upload de nova imagem
    if (isset($_FILES['imagem_upload']) && $_FILES['imagem_upload']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagem_upload']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('serv_') . '.' . $ext;
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
    if ($nome && !$erro) {
        $stmt = $db->prepare('UPDATE servicos SET nome=?, descricao=?, imagem=? WHERE id=?');
        $stmt->execute([$nome, $descricao, $imagem, $id]);
        header('Location: servicos_admin.php');
        exit;
    } elseif (!$erro) {
        $erro = 'Preencha pelo menos o nome do serviço!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Serviço - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-servico { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-servico label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-servico input[type="text"], .form-servico textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-servico input[type="file"] { margin-bottom: 16px; }
        .form-servico button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-servico button:hover { background: #ff7979; }
        .form-servico .erro { color: red; margin-bottom: 10px; }
        .img-thumb { max-width: 120px; max-height: 120px; border-radius: 6px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="servicos_admin.php">&larr; Voltar para Serviços</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Editar Serviço</h2>
        <form method="post" class="form-servico" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($servico['nome']) ?>">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3"><?= htmlspecialchars($servico['descricao']) ?></textarea>
            <label for="imagem_upload">Nova Imagem (opcional)</label>
            <?php if ($servico['imagem']): ?><img src="<?= htmlspecialchars($servico['imagem']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="imagem_upload" id="imagem_upload" accept="image/*">
            <label for="link">Link do Serviço (opcional)</label>
            <input type="text" name="link" id="link" value="<?= htmlspecialchars($servico['link']) ?>" placeholder="https://...">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 