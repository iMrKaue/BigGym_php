<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $foto = '';

    // Upload de foto
    if (isset($_FILES['foto_upload']) && $_FILES['foto_upload']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_upload']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('func_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['foto_upload']['tmp_name'], $destino)) {
                $foto = $destino;
            } else {
                $erro = 'Erro ao salvar a foto.';
            }
        } else {
            $erro = 'Formato de foto não permitido.';
        }
    }

    if ($nome && !$erro) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO funcionarios (nome, cargo, email, telefone, foto) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$nome, $cargo, $email, $telefone, $foto]);
        header('Location: funcionarios_admin.php');
        exit;
    } elseif (!$erro) {
        $erro = 'Preencha pelo menos o nome do funcionário!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Funcionário - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-func { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-func label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-func input[type="text"], .form-func input[type="email"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-func input[type="file"] { margin-bottom: 16px; }
        .form-func button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-func button:hover { background: #ff7979; }
        .form-func .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="funcionarios_admin.php">&larr; Voltar para Funcionários</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Adicionar Novo Funcionário</h2>
        <form method="post" class="form-func" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required>
            <label for="cargo">Cargo</label>
            <input type="text" name="cargo" id="cargo">
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone">
            <label for="foto_upload">Foto do Funcionário (envie um arquivo)</label>
            <input type="file" name="foto_upload" id="foto_upload" accept="image/*">
            <button type="submit">Salvar Funcionário</button>
        </form>
    </main>
</body>
</html> 