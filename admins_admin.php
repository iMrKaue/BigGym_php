<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

// Buscar admins do banco
$db = getDB();
// Cria a tabela se não existir
$db->exec('CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL
)');
$stmt = $db->query('SELECT * FROM admins ORDER BY id DESC');
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Admins - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .admin-table th, .admin-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .admin-table th { background: #ee0606; color: #fff; }
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
        <h2>Gerenciar Admins</h2>
        <a href="admin_novo.php" class="btn-add">+ Adicionar Novo Admin</a>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($admins as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['usuario']) ?></td>
                <td>
                    <a href="admin_editar.php?id=<?= $a['id'] ?>" class="btn-edit">Editar</a>
                    <a href="admin_remover.php?id=<?= $a['id'] ?>" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este admin?');">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php if (empty($admins)): ?>
            <p style="color:#888; text-align:center;">Nenhum admin cadastrado.</p>
        <?php endif; ?>
    </main>
</body>
</html> 