<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

// Buscar funcionários do banco
$db = getDB();
// Cria a tabela se não existir
$db->exec('CREATE TABLE IF NOT EXISTS funcionarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    cargo TEXT,
    email TEXT,
    telefone TEXT,
    foto TEXT
)');
$stmt = $db->query('SELECT * FROM funcionarios ORDER BY id DESC');
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Funcionários - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .func-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
        .func-table th, .func-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .func-table th { background: #ee0606; color: #fff; }
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
        <h2>Gerenciar Funcionários</h2>
        <a href="funcionario_novo.php" class="btn-add">+ Adicionar Novo Funcionário</a>
        <table class="func-table">
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($funcionarios as $f): ?>
            <tr>
                <td><?= $f['id'] ?></td>
                <td><?php if ($f['foto']): ?><img src="<?= htmlspecialchars($f['foto']) ?>" class="img-thumb"><?php endif; ?></td>
                <td><?= htmlspecialchars($f['nome']) ?></td>
                <td><?= htmlspecialchars($f['cargo']) ?></td>
                <td><?= htmlspecialchars($f['email']) ?></td>
                <td><?= htmlspecialchars($f['telefone']) ?></td>
                <td>
                    <a href="funcionario_editar.php?id=<?= $f['id'] ?>" class="btn-edit">Editar</a>
                    <a href="funcionario_remover.php?id=<?= $f['id'] ?>" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este funcionário?');">Remover</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php if (empty($funcionarios)): ?>
            <p style="color:#888; text-align:center;">Nenhum funcionário cadastrado.</p>
        <?php endif; ?>
    </main>
</body>
</html> 