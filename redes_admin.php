<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
// Busca o conteúdo atual
$stmt = $db->query('SELECT * FROM redes LIMIT 1');
$redes = $stmt->fetch(PDO::FETCH_ASSOC);

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facebook = trim($_POST['facebook'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $youtube = trim($_POST['youtube'] ?? '');
    if (!$erro) {
        if ($redes) {
            $stmt = $db->prepare('UPDATE redes SET facebook=?, instagram=?, whatsapp=?, youtube=? WHERE id=?');
            $stmt->execute([$facebook, $instagram, $whatsapp, $youtube, $redes['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO redes (facebook, instagram, whatsapp, youtube) VALUES (?, ?, ?, ?)');
            $stmt->execute([$facebook, $instagram, $whatsapp, $youtube]);
        }
        header('Location: redes_admin.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Redes Sociais - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-redes { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-redes label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-redes input[type="text"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-redes button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-redes button:hover { background: #ff7979; }
        .form-redes .erro { color: red; margin-bottom: 10px; }
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
        <h2 style="text-align:center; color:#ee0606;">Editar Redes Sociais</h2>
        <?php if (isset($_GET['ok'])): ?><div class="ok-msg">Alterações salvas com sucesso!</div><?php endif; ?>
        <form method="post" class="form-redes">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="facebook">Facebook</label>
            <input type="text" name="facebook" id="facebook" value="<?= htmlspecialchars($redes['facebook'] ?? '') ?>">
            <label for="instagram">Instagram</label>
            <input type="text" name="instagram" id="instagram" value="<?= htmlspecialchars($redes['instagram'] ?? '') ?>">
            <label for="whatsapp">WhatsApp</label>
            <input type="text" name="whatsapp" id="whatsapp" value="<?= htmlspecialchars($redes['whatsapp'] ?? '') ?>">
            <label for="youtube">YouTube</label>
            <input type="text" name="youtube" id="youtube" value="<?= htmlspecialchars($redes['youtube'] ?? '') ?>">
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 