<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-menu {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin: 40px 0 30px 0;
        }
        .admin-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 300px;
            padding: 30px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow 0.3s;
        }
        .admin-card:hover {
            box-shadow: 0 4px 20px rgba(238,6,6,0.15);
        }
        .admin-card h3 {
            color: #ee0606;
            margin-bottom: 15px;
        }
        .admin-card a {
            background: linear-gradient(45deg, #ee0606, #ff7979);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .admin-card a:hover {
            background: linear-gradient(45deg, #ff7979, #ee0606);
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php"><img src="logo.jpg" alt="Logo" class="logo"></a>
            <ul style="float:right;list-style:none;">
                <li><a href="logout.php" style="color:#ee0606;">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; margin-top:30px; color:#ee0606;">Painel Administrativo</h2>
        <div class="admin-menu">
            <div class="admin-card">
                <h3>Editar Página Principal</h3>
                <p>Modifique textos, banners e informações da home.</p>
                <a href="editar_pagina.php">Editar Página</a>
            </div>
            <div class="admin-card">
                <h3>Gerenciar Produtos</h3>
                <p>Adicione, edite ou remova produtos da loja.</p>
                <a href="produtos_admin.php">Gerenciar Produtos</a>
            </div>
            <div class="admin-card">
                <h3>Funcionários</h3>
                <p>Adicione ou edite funcionários da academia.</p>
                <a href="funcionarios_admin.php">Gerenciar Funcionários</a>
            </div>
            <div class="admin-card">
                <h3>Admins</h3>
                <p>Adicione ou remova administradores do sistema.</p>
                <a href="admins_admin.php">Gerenciar Admins</a>
            </div>
            <div class="admin-card">
                <h3>Gerenciar Planos</h3>
                <p>Adicione, edite ou remova planos da academia.</p>
                <a href="planos_admin.php">Gerenciar Planos</a>
            </div>
            <div class="admin-card">
                <h3>Gerenciar Serviços</h3>
                <p>Adicione, edite ou remova serviços da academia.</p>
                <a href="servicos_admin.php">Gerenciar Serviços</a>
            </div>
            <div class="admin-card">
                <h3>Modificar o Site em Geral</h3>
                <p>Edite textos, imagens, informações de contato, redes sociais e rodapé do site em um só lugar.</p>
                <a href="site_admin.php">Modificar Site</a>
            </div>
        </div>
    </main>
</body>
</html> 