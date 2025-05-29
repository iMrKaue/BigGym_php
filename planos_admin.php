<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
$stmt = $db->query('SELECT * FROM planos ORDER BY id ASC');
$planos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Planos - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .planos-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .planos-table th, .planos-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .planos-table th { background: #ee0606; color: #fff; }
        .btn-add { background: #28a745; color: #fff; padding: 8px 18px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-bottom: 20px; text-decoration: none; }
        .btn-add:hover { background: #218838; }
        .btn-edit, .btn-remove { padding: 5px 12px; border: none; border-radius: 4px; color: #fff; cursor: pointer; font-size: 0.95rem; }
        .btn-edit { background: #007bff; }
        .btn-edit:hover { background: #0056b3; }
        .btn-remove { background: #ee0606; }
        .btn-remove:hover { background: #b30000; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php">&larr; Voltar ao Painel Admin</a>
        </nav>
    </header>
    <main>
        <h2>Gerenciar Planos</h2>
        <a href="plano_novo.php" class="btn-add">+ Adicionar Novo Plano</a>
        <table class="planos-table">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($planos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= nl2br(htmlspecialchars($p['descricao'])) ?></td>
                <td><?= htmlspecialchars($p['preco']) ?></td>
                <td>
                    <a href="plano_editar.php?id=<?= $p['id'] ?>" class="btn-edit">Editar</a>
                    <a href="plano_remover.php?id=<?= $p['id'] ?>" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este plano?');">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php if (empty($planos)): ?>
            <p style="color:#888; text-align:center;">Nenhum plano cadastrado.</p>
        <?php endif; ?>
    </main>
</body>
</html> 