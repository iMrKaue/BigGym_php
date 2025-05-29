<?php
require 'db.php';

$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = $db->prepare('SELECT * FROM produtos WHERE id = ?');
$produto->execute([$id]);
$produto = $produto->fetch(PDO::FETCH_ASSOC);
if (!$produto) {
    echo '<h2 style="color:#ee0606; text-align:center; margin-top:40px;">Produto não encontrado.</h2>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - <?= htmlspecialchars($produto['nome']) ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
    .pagamento-container {
        max-width: 480px;
        margin: 40px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 32px rgba(0,0,0,0.10);
        padding: 36px 28px 28px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .pagamento-container img {
        max-width: 120px;
        max-height: 120px;
        border-radius: 12px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px #ee0606;
        object-fit: cover;
        background: #fafafa;
    }
    .pagamento-container h2 {
        color: #ee0606;
        font-size: 1.5rem;
        margin-bottom: 8px;
        text-align: center;
    }
    .pagamento-container .preco {
        font-size: 1.3rem;
        color: #ee0606;
        font-weight: bold;
        background: #ffeaea;
        border-radius: 8px;
        padding: 8px 18px;
        margin-bottom: 8px;
        box-shadow: 0 2px 8px #ee060633;
        letter-spacing: 0.5px;
        text-align: center;
    }
    .pagamento-container .preco-antigo {
        color: #888;
        font-size: 1.02rem;
        text-decoration: line-through;
        margin-bottom: 4px;
    }
    .pagamento-container .desconto {
        color: #28a745;
        font-size: 1.02rem;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .pagamento-container .disponivel {
        margin-bottom: 12px;
        color: <?= ($produto['disponivel'] == 'Sim' || $produto['disponivel'] == 1) ? '#28a745' : '#ee0606' ?>;
        font-weight: bold;
        font-size: 1.05rem;
    }
    .pagamento-container form {
        width: 100%;
        margin-top: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .pagamento-container button {
        margin-top: 10px;
    }
    </style>
</head>
<body>
    <div class="pagamento-container">
        <?php if (!empty($produto['imagem'])): ?>
            <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        <?php endif; ?>
        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
        <?php if (!empty($produto['subtitulo'])): ?>
            <div style="color:#555; font-size:1.05rem; margin-bottom:8px; text-align:center; font-style:italic;"> <?= htmlspecialchars($produto['subtitulo']) ?> </div>
        <?php endif; ?>
        <div class="preco">
            <?php
            $preco = $produto['preco'];
            $preco_num = preg_replace('/[^0-9.,]/', '', $preco);
            $preco_float = str_replace([','], ['.'], $preco_num);
            if (is_numeric($preco_float)) {
                echo 'R$ ' . number_format((float)$preco_float, 2, ',', '.');
            } else {
                echo 'R$ ' . htmlspecialchars($preco);
            }
            ?>
        </div>
        <?php if (!empty($produto['preco_antigo'])): ?>
            <div class="preco-antigo">De: R$ <?= htmlspecialchars($produto['preco_antigo']) ?></div>
        <?php endif; ?>
        <?php if (!empty($produto['desconto'])): ?>
            <div class="desconto">Desconto: <?= htmlspecialchars($produto['desconto']) ?>%</div>
        <?php endif; ?>
        <?php if (isset($produto['disponivel'])): ?>
            <div class="disponivel">
                <?= ($produto['disponivel'] == 'Sim' || $produto['disponivel'] == 1) ? 'Disponível' : 'Indisponível' ?>
            </div>
        <?php endif; ?>
        <form>
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>
            <label for="pagamento">Forma de Pagamento:</label>
            <select id="pagamento" name="pagamento" required>
                <option value="pix">PIX</option>
                <option value="credito">Cartão de Crédito</option>
                <option value="debito">Cartão de Débito</option>
                <option value="boleto">Boleto Bancário</option>
            </select>
            <button type="submit" class="btn">Finalizar Compra</button>
        </form>
    </div>
</body>
</html> 