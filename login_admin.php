<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM admins WHERE usuario = ?');
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin'] = $admin['usuario'];
        header('Location: admin.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Professor - BigGym</title>
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
            <h2 style="background: #888; color: #ee0606; text-shadow: 2px 2px #333; border-radius: 10px 10px 0 0; padding: 10px;">Login Professor</h2>
            <form method="post" style="padding: 30px;">
                <label for="usuario">Usuário</label>
                <input type="text" name="usuario" id="usuario" required value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Entrar</button>
                <?php if (!empty($erro)) echo "<p style='color:red; margin-top:10px;'>$erro</p>"; ?>
            </form>
        </section>
    </main>
</body>
</html> 