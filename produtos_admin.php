<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

// Buscar produtos do banco
$db = getDB();
$stmt = $db->query('SELECT * FROM produtos ORDER BY id DESC');
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .produtos-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .produtos-table th, .produtos-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .produtos-table th {
            background: #ee0606;
            color: #fff;
        }
        .btn-add {
            background: #28a745;
            color: #fff;
            padding: 8px 18px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-add:hover {
            background: #218838;
        }
        .btn-edit, .btn-remove {
            padding: 5px 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .btn-edit { background: #007bff; }
        .btn-edit:hover { background: #0056b3; }
        .btn-remove { background: #ee0606; }
        .btn-remove:hover { background: #b30000; }
        .img-thumb { max-width: 60px; max-height: 60px; border-radius: 6px; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php">&larr; Voltar ao Painel Admin</a>
        </nav>
    </header>
    <main>
        <h2>Gerenciar Produtos</h2>
        <a href="produto_novo.php" class="btn-add">+ Adicionar Novo Produto</a>
        <table class="produtos-table">
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Subtítulo</th>
                <th>Preço</th>
                <th>Desconto (%)</th>
                <th>Preço Antigo</th>
                <th>Disponível</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?php if ($p['imagem']): ?><img src="<?= htmlspecialchars($p['imagem']) ?>" class="img-thumb"><?php endif; ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['subtitulo']) ?></td>
                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                <td><?= (int)$p['desconto'] ?></td>
                <td><?php if ($p['preco_antigo']): ?>R$ <?= number_format($p['preco_antigo'], 2, ',', '.') ?><?php endif; ?></td>
                <td><?= $p['disponivel'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href="produto_editar.php?id=<?= $p['id'] ?>" class="btn-edit">Editar</a>
                    <a href="produto_remover.php?id=<?= $p['id'] ?>" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este produto?');">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php if (empty($produtos)): ?>
            <p style="color:#888; text-align:center;">Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </main>
</body>
</html> 