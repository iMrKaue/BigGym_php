<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
// Busca o conteúdo atual
$stmt = $db->query('SELECT * FROM contato LIMIT 1');
$contato = $stmt->fetch(PDO::FETCH_ASSOC);

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $endereco = trim($_POST['endereco'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mapa = trim($_POST['mapa'] ?? '');
    if (!$erro) {
        if ($contato) {
            $stmt = $db->prepare('UPDATE contato SET endereco=?, telefone=?, email=?, mapa=? WHERE id=?');
            $stmt->execute([$endereco, $telefone, $email, $mapa, $contato['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO contato (endereco, telefone, email, mapa) VALUES (?, ?, ?, ?)');
            $stmt->execute([$endereco, $telefone, $email, $mapa]);
        }
        header('Location: contato_admin.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Contato - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-contato { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-contato label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-contato input[type="text"], .form-contato input[type="email"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-contato button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-contato button:hover { background: #ff7979; }
        .form-contato .erro { color: red; margin-bottom: 10px; }
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
        <h2 style="text-align:center; color:#ee0606;">Editar Contato</h2>
        <?php if (isset($_GET['ok'])): ?><div class="ok-msg">Alterações salvas com sucesso!</div><?php endif; ?>
        <form method="post" class="form-contato">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($contato['endereco'] ?? '') ?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($contato['telefone'] ?? '') ?>">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($contato['email'] ?? '') ?>">
            <label for="mapa">Link do Mapa (iframe ou Google Maps)</label>
            <input type="text" name="mapa" id="mapa" value="<?= htmlspecialchars($contato['mapa'] ?? '') ?>">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 