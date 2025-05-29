<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$sucesso = $erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $subtitulo = trim($_POST['subtitulo'] ?? '');
    $preco = floatval(str_replace(',', '.', $_POST['preco'] ?? '0'));
    $desconto = intval($_POST['desconto'] ?? 0);
    $preco_antigo = floatval(str_replace(',', '.', $_POST['preco_antigo'] ?? '0'));
    $imagem = trim($_POST['imagem'] ?? '');
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;

    // Upload de imagem
    if (isset($_FILES['imagem_upload']) && $_FILES['imagem_upload']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagem_upload']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('prod_') . '.' . $ext;
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

    if ($nome && $preco > 0 && !$erro) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO produtos (nome, subtitulo, preco, desconto, preco_antigo, imagem, disponivel) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nome, $subtitulo, $preco, $desconto, $preco_antigo, $imagem, $disponivel]);
        header('Location: produtos_admin.php');
        exit;
    } elseif (!$erro) {
        $erro = 'Preencha pelo menos o nome e o preço corretamente!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Produto - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-produto { max-width: 400px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-produto label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-produto input[type="text"], .form-produto input[type="number"] { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-produto input[type="file"] { margin-bottom: 16px; }
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
        <h2 style="text-align:center; color:#ee0606;">Adicionar Novo Produto</h2>
        <form method="post" class="form-produto" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <label for="nome">Nome *</label>
            <input type="text" name="nome" id="nome" required>
            <label for="subtitulo">Subtítulo</label>
            <input type="text" name="subtitulo" id="subtitulo">
            <label for="preco">Preço (R$) *</label>
            <input type="text" name="preco" id="preco" required pattern="^[0-9]+([\.,][0-9]{1,2})?$" title="Digite um valor válido">
            <label for="desconto">Desconto (%)</label>
            <input type="number" name="desconto" id="desconto" min="0" max="100">
            <label for="preco_antigo">Preço Antigo (R$)</label>
            <input type="text" name="preco_antigo" id="preco_antigo" pattern="^[0-9]+([\.,][0-9]{1,2})?$" title="Digite um valor válido">
            <label for="imagem_upload">Imagem do Produto (envie um arquivo)</label>
            <input type="file" name="imagem_upload" id="imagem_upload" accept="image/*">
            <label for="imagem">Ou URL da Imagem (opcional)</label>
            <input type="text" name="imagem" id="imagem">
            <label><input type="checkbox" name="disponivel" checked> Disponível</label>
            <button type="submit">Salvar Produto</button>
        </form>
    </main>
</body>
</html> 