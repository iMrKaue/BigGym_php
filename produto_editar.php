<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: produtos_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('SELECT * FROM produtos WHERE id = ?');
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto) {
    header('Location: produtos_admin.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $preco = floatval(str_replace(',', '.', $_POST['preco'] ?? '0'));
    $desconto = intval($_POST['desconto'] ?? 0);
    $preco_antigo = floatval(str_replace(',', '.', $_POST['preco_antigo'] ?? '0'));
    $imagem = trim($_POST['imagem'] ?? '');
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;

    if ($nome && $preco > 0) {
        $stmt = $db->prepare('UPDATE produtos SET nome=?, subtitulo=?, preco=?, desconto=?, preco_antigo=?, imagem=?, disponivel=? WHERE id=?');
        $stmt->execute([$nome, $subtitulo, $preco, $desconto, $preco_antigo, $imagem, $disponivel, $id]);
        header('Location: produtos_admin.php');
        exit;
    } else {
        $erro = 'Preencha pelo menos o nome e o preço corretamente!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-produto { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-produto label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-produto input[type="text"], .form-produto input[type="number"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-produto input[type="checkbox"] { margin-right: 6px; }
        .form-produto button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; }
        .form-produto button:hover { background: #ff7979; }
        .form-produto .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="produtos_admin.php">&larr; Voltar para Produtos</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Editar Produto</h2>
        <form method="post" class="form-produto">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars($produto['nome']) ?>">
            <label for="subtitulo">Subtítulo</label>
            <input type="text" name="subtitulo" id="subtitulo" value="<?= htmlspecialchars($produto['subtitulo']) ?>">
            <label for="preco">Preço (R$) *</label>
            <input type="text" name="preco" id="preco" required pattern="^[0-9]+([\.,][0-9]{1,2})?$" title="Digite um valor válido" value="<?= htmlspecialchars($produto['preco']) ?>">
            <label for="desconto">Desconto (%)</label>
            <input type="number" name="desconto" id="desconto" min="0" max="100" value="<?= (int)$produto['desconto'] ?>">
            <label for="preco_antigo">Preço Antigo (R$)</label>
            <input type="text" name="preco_antigo" id="preco_antigo" pattern="^[0-9]+([\.,][0-9]{1,2})?$" title="Digite um valor válido" value="<?= htmlspecialchars($produto['preco_antigo']) ?>">
            <label for="imagem">URL da Imagem</label>
            <input type="text" name="imagem" id="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>">
            <label><input type="checkbox" name="disponivel" <?= $produto['disponivel'] ? 'checked' : '' ?>> Disponível</label>
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 