<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
// Busca o conteúdo atual
$stmt = $db->query('SELECT * FROM footer LIMIT 1');
$footer = $stmt->fetch(PDO::FETCH_ASSOC);

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim($_POST['texto'] ?? '');
    if (!$erro) {
        if ($footer) {
            $stmt = $db->prepare('UPDATE footer SET texto=? WHERE id=?');
            $stmt->execute([$texto, $footer['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO footer (texto) VALUES (?)');
            $stmt->execute([$texto]);
        }
        header('Location: footer_admin.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Rodapé - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-footer { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-footer label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-footer textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-footer button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-footer button:hover { background: #ff7979; }
        .form-footer .erro { color: red; margin-bottom: 10px; }
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
        <h2 style="text-align:center; color:#ee0606;">Editar Rodapé do Site</h2>
        <?php if (isset($_GET['ok'])): ?><div class="ok-msg">Alterações salvas com sucesso!</div><?php endif; ?>
        <form method="post" class="form-footer">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="texto">Texto do Rodapé *</label>
            <textarea name="texto" id="texto" rows="4" required><?= htmlspecialchars($footer['texto'] ?? '') ?></textarea>
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 