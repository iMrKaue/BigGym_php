<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
$stmt = $db->query('SELECT * FROM servicos ORDER BY id ASC');
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Serviços - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .servicos-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .servicos-table th, .servicos-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .servicos-table th { background: #ee0606; color: #fff; }
        .btn-add { background: #28a745; color: #fff; padding: 8px 18px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-bottom: 20px; text-decoration: none; }
        .btn-add:hover { background: #218838; }
        .btn-edit, .btn-remove { padding: 5px 12px; border: none; border-radius: 4px; color: #fff; cursor: pointer; font-size: 0.95rem; }
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
        <h2>Gerenciar Serviços</h2>
        <a href="servico_novo.php" class="btn-add">+ Adicionar Novo Serviço</a>
        <table class="servicos-table">
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Link</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($servicos as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?php if ($s['imagem']): ?><img src="<?= htmlspecialchars($s['imagem']) ?>" class="img-thumb"><?php endif; ?></td>
                <td><?= htmlspecialchars($s['nome']) ?></td>
                <td><?= nl2br(htmlspecialchars($s['descricao'])) ?></td>
                <td><?= htmlspecialchars($s['link']) ?></td>
                <td>
                    <a href="servico_editar.php?id=<?= $s['id'] ?>" class="btn-edit">Editar</a>
                    <a href="servico_remover.php?id=<?= $s['id'] ?>" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este serviço?');">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php if (empty($servicos)): ?>
            <p style="color:#888; text-align:center;">Nenhum serviço cadastrado.</p>
        <?php endif; ?>
    </main>
</body>
</html> 