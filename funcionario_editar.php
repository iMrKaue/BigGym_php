<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: funcionarios_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('SELECT * FROM funcionarios WHERE id = ?');
$stmt->execute([$id]);
$func = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$func) {
    header('Location: funcionarios_admin.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $foto = $func['foto'];

    // Upload de nova foto
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
        $stmt = $db->prepare('UPDATE funcionarios SET nome=?, cargo=?, email=?, telefone=?, foto=? WHERE id=?');
        $stmt->execute([$nome, $cargo, $email, $telefone, $foto, $id]);
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
    <title>Editar Funcionário - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-func { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-func label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-func input[type="text"], .form-func input[type="email"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-func input[type="file"] { margin-bottom: 16px; }
        .form-func button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-func button:hover { background: #ff7979; }
        .form-func .erro { color: red; margin-bottom: 10px; }
        .img-thumb { max-width: 80px; max-height: 80px; border-radius: 6px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="funcionarios_admin.php">&larr; Voltar para Funcionários</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Editar Funcionário</h2>
        <form method="post" class="form-func" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <?php if ($func['foto']): ?><img src="<?= htmlspecialchars($func['foto']) ?>" class="img-thumb"><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($func['nome']) ?>">
            <label for="cargo">Cargo</label>
            <input type="text" name="cargo" id="cargo" value="<?= htmlspecialchars($func['cargo']) ?>">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($func['email']) ?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($func['telefone']) ?>">
            <label for="foto_upload">Nova Foto (opcional)</label>
            <input type="file" name="foto_upload" id="foto_upload" accept="image/*">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 