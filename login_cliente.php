<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM clientes WHERE email = ?');
    $stmt->execute([$email]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente && password_verify($senha, $cliente['senha'])) {
        $_SESSION['cliente'] = $cliente['nome'];
        $_SESSION['cliente_id'] = $cliente['id'];
        header('Location: index.php');
        exit;
    } else {
        $erro = "E-mail ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Aluno - BigGym</title>
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
            <h2 style="background: #888; color: #ee0606; text-shadow: 2px 2px #333; border-radius: 10px 10px 0 0; padding: 10px;">Login Aluno</h2>
            <form method="post" style="padding: 30px;">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Entrar</button>
                <?php if (!empty($erro)) echo "<p style='color:red; margin-top:10px;'>$erro</p>"; ?>
            </form>
            <a href="cadastro_cliente.php" style="display: block; margin-top: 10px; color:#ee0606;">Ainda não faz parte? Cadastre-se!</a>
        </section>
    </main>
</body>
</html> 