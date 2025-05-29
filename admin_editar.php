<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admins_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('SELECT * FROM admins WHERE id = ?');
$stmt->execute([$id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$admin) {
    header('Location: admins_admin.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $senha2 = $_POST['senha2'] ?? '';

    if ($usuario) {
        // Verifica se o usuário já existe para outro id
        $stmt = $db->prepare('SELECT * FROM admins WHERE usuario = ? AND id != ?');
        $stmt->execute([$usuario, $id]);
        if ($stmt->fetch()) {
            $erro = 'Usuário já existe!';
        } else {
            if ($senha) {
                if ($senha === $senha2) {
                    $hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $db->prepare('UPDATE admins SET usuario=?, senha=? WHERE id=?');
                    $stmt->execute([$usuario, $hash, $id]);
                } else {
                    $erro = 'As senhas não coincidem!';
                }
            } else {
                $stmt = $db->prepare('UPDATE admins SET usuario=? WHERE id=?');
                $stmt->execute([$usuario, $id]);
            }
            if (!$erro) {
                header('Location: admins_admin.php');
                exit;
            }
        }
    } else {
        $erro = 'Preencha o usuário!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Admin - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-admin { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-admin label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-admin input[type="text"], .form-admin input[type="password"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-admin button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-admin button:hover { background: #ff7979; }
        .form-admin .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admins_admin.php">&larr; Voltar para Admins</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Editar Admin</h2>
        <form method="post" class="form-admin">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="usuario">Usuário *</label>
            <input type="text" name="usuario" id="usuario" required value="<?= htmlspecialchars($admin['usuario']) ?>">
            <label for="senha">Nova Senha (opcional)</label>
            <input type="password" name="senha" id="senha">
            <label for="senha2">Repita a Nova Senha</label>
            <input type="password" name="senha2" id="senha2">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 