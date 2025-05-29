<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cliente_id'])) {
    // Redireciona para login se não estiver logado
    header('Location: login_cliente.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<p style="color:red;">Serviço inválido.</p>';
    exit;
}

$id_aula = (int)$_GET['id'];
$id_cliente = $_SESSION['cliente_id'];

try {
    $db = getDB();
    // Verifica se já existe matrícula para evitar duplicidade
    $stmt = $db->prepare('SELECT COUNT(*) FROM matriculas WHERE id_cliente = ? AND id_aula = ?');
    $stmt->execute([$id_cliente, $id_aula]);
    if ($stmt->fetchColumn() > 0) {
        $msg = 'Você já está matriculado nesta aula!';
    } else {
        $stmt = $db->prepare('INSERT INTO matriculas (id_cliente, id_aula, status) VALUES (?, ?, ?)');
        $stmt->execute([$id_cliente, $id_aula, 'pendente']);
        $msg = 'Aula marcada com sucesso! Aguarde a confirmação.';
    }
} catch (Exception $e) {
    $msg = 'Erro ao marcar aula: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Marcar Aula - BigGym</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">&larr; Voltar para Home</a>
        </nav>
    </header>
    <main>
        <section style="max-width: 500px; margin: 40px auto; text-align:center;">
            <h2 style="color:#28a745; margin-bottom:20px;">Marcação de Aula</h2>
            <p style="font-size:1.2rem; color:#222; margin-bottom:24px;"> <?= htmlspecialchars($msg) ?> </p>
            <a href="index.php#servicos" class="btn" style="background:#ee0606; color:#fff;">Voltar para Serviços</a>
        </section>
    </main>
</body>
</html> 