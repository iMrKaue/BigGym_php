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
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    if ($nome && $preco) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO planos (nome, descricao, preco) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $descricao, $preco]);
        header('Location: planos_admin.php');
        exit;
    } else {
        $erro = 'Preencha pelo menos o nome e o preço!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Plano - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-plano { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-plano label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-plano input[type="text"], .form-plano textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-plano button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-plano button:hover { background: #ff7979; }
        .form-plano .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="planos_admin.php">&larr; Voltar para Planos</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Adicionar Novo Plano</h2>
        <form method="post" class="form-plano">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required>
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3"></textarea>
            <label for="preco">Preço *</label>
            <input type="text" name="preco" id="preco" required>
            <button type="submit">Salvar Plano</button>
        </form>
    </main>
</body>
</html> 