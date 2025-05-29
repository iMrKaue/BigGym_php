<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $senha2 = $_POST['senha2'] ?? '';

    if ($senha !== $senha2) {
        $erro = "As senhas não coincidem!";
    } else {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM clientes WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erro = "E-mail já cadastrado!";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $hash]);
            $sucesso = "Cadastro realizado! Faça login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Aluno - BigGym</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php"><img src="logo.jpg" alt="Logo" class="logo"></a>
        </nav>
    </header>
    <main>
        <section style="max-width: 500px; margin: 40px auto;">
            <h2 style="background: #888; color: #ee0606; text-shadow: 2px 2px #333; border-radius: 10px 10px 0 0; padding: 10px;">Cadastro Aluno</h2>
            <form method="post" style="padding: 30px;">
                <label for="nome">Nome completo</label>
                <input type="text" name="nome" id="nome" required value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                <label for="senha2">Repita a senha</label>
                <input type="password" name="senha2" id="senha2" required>
                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Cadastrar</button>
                <?php
                if (!empty($erro)) echo "<p style='color:red; margin-top:10px;'>$erro</p>";
                if (!empty($sucesso)) echo "<p style='color:green; margin-top:10px;'>$sucesso</p>";
                ?>
            </form>
            <a href="login_cliente.php" style="display: block; margin-top: 10px; color:#ee0606;">Já tem conta? Faça login</a>
        </section>
    </main>
</body>
</html> 