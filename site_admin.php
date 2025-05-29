<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

$db = getDB();
// Buscar dados atuais
$sobre = $db->query('SELECT * FROM sobre LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$contato = $db->query('SELECT * FROM contato LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$redes = $db->query('SELECT * FROM redes LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$footer = $db->query('SELECT * FROM footer LIMIT 1')->fetch(PDO::FETCH_ASSOC);

$erro = '';
$ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SOBRE
    $sobre_texto = trim($_POST['sobre_texto'] ?? '');
    $sobre_img = $sobre['imagem'] ?? '';
    if (isset($_FILES['sobre_img']) && $_FILES['sobre_img']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['sobre_img']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            $nome_arquivo = uniqid('sobre_') . '.' . $ext;
            $destino = 'imagens/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['sobre_img']['tmp_name'], $destino)) {
                $sobre_img = $destino;
            } else {
                $erro = 'Erro ao salvar a imagem do Sobre.';
            }
        } else {
            $erro = 'Formato de imagem do Sobre não permitido.';
        }
    }
    // CONTATO
    $endereco = trim($_POST['endereco'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mapa = trim($_POST['mapa'] ?? '');
    // REDES
    $facebook = trim($_POST['facebook'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $youtube = trim($_POST['youtube'] ?? '');
    // FOOTER
    $footer_texto = trim($_POST['footer_texto'] ?? '');
    if (!$erro) {
        // SOBRE
        if ($sobre) {
            $stmt = $db->prepare('UPDATE sobre SET texto=?, imagem=? WHERE id=?');
            $stmt->execute([$sobre_texto, $sobre_img, $sobre['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO sobre (texto, imagem) VALUES (?, ?)');
            $stmt->execute([$sobre_texto, $sobre_img]);
        }
        // CONTATO
        if ($contato) {
            $stmt = $db->prepare('UPDATE contato SET endereco=?, telefone=?, email=?, mapa=? WHERE id=?');
            $stmt->execute([$endereco, $telefone, $email, $mapa, $contato['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO contato (endereco, telefone, email, mapa) VALUES (?, ?, ?, ?)');
            $stmt->execute([$endereco, $telefone, $email, $mapa]);
        }
        // REDES
        if ($redes) {
            $stmt = $db->prepare('UPDATE redes SET facebook=?, instagram=?, whatsapp=?, youtube=? WHERE id=?');
            $stmt->execute([$facebook, $instagram, $whatsapp, $youtube, $redes['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO redes (facebook, instagram, whatsapp, youtube) VALUES (?, ?, ?, ?)');
            $stmt->execute([$facebook, $instagram, $whatsapp, $youtube]);
        }
        // FOOTER
        if ($footer) {
            $stmt = $db->prepare('UPDATE footer SET texto=? WHERE id=?');
            $stmt->execute([$footer_texto, $footer['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO footer (texto) VALUES (?)');
            $stmt->execute([$footer_texto]);
        }
        $ok = true;
        // Atualizar variáveis para exibir após salvar
        $sobre = $db->query('SELECT * FROM sobre LIMIT 1')->fetch(PDO::FETCH_ASSOC);
        $contato = $db->query('SELECT * FROM contato LIMIT 1')->fetch(PDO::FETCH_ASSOC);
        $redes = $db->query('SELECT * FROM redes LIMIT 1')->fetch(PDO::FETCH_ASSOC);
        $footer = $db->query('SELECT * FROM footer LIMIT 1')->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Modificar o Site em Geral - BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-site { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        .form-site h3 { color: #ee0606; margin-top: 30px; }
        .form-site label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-site input[type="text"], .form-site input[type="email"], .form-site textarea { width: 100%; padding: 8px; margin-bottom: 16px; border-radius: 5px; border: 1px solid #ccc; }
        .form-site input[type="file"] { margin-bottom: 16px; }
        .form-site button { background: #ee0606; color: #fff; border: none; border-radius: 5px; padding: 10px 20px; font-size: 1rem; cursor: pointer; margin-top: 20px; }
        .form-site button:hover { background: #ff7979; }
        .form-site .erro { color: red; margin-bottom: 10px; }
        .form-site .ok-msg { color: #28a745; text-align: center; margin-bottom: 10px; }
        .img-thumb { max-width: 180px; max-height: 180px; border-radius: 6px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php">&larr; Voltar ao Painel Admin</a>
        </nav>
    </header>
    <main>
        <h2 style="text-align:center; color:#ee0606;">Modificar o Site em Geral</h2>
        <form method="post" class="form-site" enctype="multipart/form-data">
            <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
            <?php if ($ok): ?><div class="ok-msg">Alterações salvas com sucesso!</div><?php endif; ?>
            <h3>Sobre a Academia</h3>
            <label for="sobre_texto">Texto Sobre *</label>
            <textarea name="sobre_texto" id="sobre_texto" rows="5" required><?= htmlspecialchars($sobre['texto'] ?? '') ?></textarea>
            <label for="sobre_img">Imagem (opcional)</label>
            <?php if (!empty($sobre['imagem'])): ?><img src="<?= htmlspecialchars($sobre['imagem']) ?>" class="img-thumb"><br><?php endif; ?>
            <input type="file" name="sobre_img" id="sobre_img" accept="image/*">
            <h3>Contato</h3>
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($contato['endereco'] ?? '') ?>">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($contato['telefone'] ?? '') ?>">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($contato['email'] ?? '') ?>">
            <label for="mapa">Link do Mapa (iframe ou Google Maps)</label>
            <input type="text" name="mapa" id="mapa" value="<?= htmlspecialchars($contato['mapa'] ?? '') ?>">
            <h3>Redes Sociais</h3>
            <label for="facebook">Facebook</label>
            <input type="text" name="facebook" id="facebook" value="<?= htmlspecialchars($redes['facebook'] ?? '') ?>">
            <label for="instagram">Instagram</label>
            <input type="text" name="instagram" id="instagram" value="<?= htmlspecialchars($redes['instagram'] ?? '') ?>">
            <label for="whatsapp">WhatsApp</label>
            <input type="text" name="whatsapp" id="whatsapp" value="<?= htmlspecialchars($redes['whatsapp'] ?? '') ?>">
            <label for="youtube">YouTube</label>
            <input type="text" name="youtube" id="youtube" value="<?= htmlspecialchars($redes['youtube'] ?? '') ?>">
            <h3>Rodapé</h3>
            <label for="footer_texto">Texto do Rodapé *</label>
            <textarea name="footer_texto" id="footer_texto" rows="3" required><?= htmlspecialchars($footer['texto'] ?? '') ?></textarea>
            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>
</html> 