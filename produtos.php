<?php // Página de Produtos Suplementos - BigGym ?>
<?php
// Página de Produtos Suplementos - BigGym
require 'db.php';
$db = getDB();
$stmt = $db->query('SELECT * FROM produtos WHERE disponivel = 1 ORDER BY id DESC');
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Suplementos | BigGym</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .produtos-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            margin: 40px 0;
        }
        .produto-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 300px;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .produto-card img {
            max-width: 180px;
            margin-bottom: 15px;
        }
        .produto-nome {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .produto-preco-antigo {
            text-decoration: line-through;
            color: #888;
            font-size: 1rem;
        }
        .produto-preco-atual {
            color: #ee0606;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .produto-desconto {
            background: #ee0606;
            color: #fff;
            border-radius: 5px;
            padding: 2px 8px;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: inline-block;
        }
        .produto-btn {
            background: linear-gradient(45deg, #ee0606, #ff7979);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .produto-btn:hover {
            background: linear-gradient(45deg, #ff7979, #ee0606);
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Voltar para a Home</a>
        </nav>
    </header>
    <main>
        <h1 style="text-align:center; margin-top:30px;">Produtos e Suplementos</h1>
        <!-- Carrinho no topo -->
        <div id="carrinho-topo" style="position:fixed;top:20px;right:30px;z-index:10000;">
            <button onclick="abrirCarrinho()" style="background:#ee0606;color:#fff;border:none;border-radius:50%;width:50px;height:50px;box-shadow:0 2px 8px rgba(0,0,0,0.2);font-size:1.5rem;cursor:pointer;position:relative;">
                🛒
                <span id="carrinho-quantidade" style="position:absolute;top:2px;right:6px;background:#fff;color:#ee0606;border-radius:50%;padding:2px 7px;font-size:0.9rem;font-weight:bold;">0</span>
            </button>
        </div>
        <div class="produtos-container">
            <?php foreach ($produtos as $p): ?>
            <div class="produto-card">
                <?php if ($p['imagem']): ?>
                    <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                <?php endif; ?>
                <div class="produto-nome"><?= htmlspecialchars($p['nome']) ?></div>
                <?php if ($p['subtitulo']): ?>
                    <div style="color:#555; font-size:0.98rem; margin-bottom:8px;"> <?= htmlspecialchars($p['subtitulo']) ?> </div>
                <?php endif; ?>
                <?php if ($p['desconto'] > 0): ?>
                    <div class="produto-desconto"><?= (int)$p['desconto'] ?>% OFF</div>
                <?php endif; ?>
                <?php if ($p['preco_antigo'] > 0): ?>
                    <div class="produto-preco-antigo">R$ <?= number_format($p['preco_antigo'], 2, ',', '.') ?></div>
                <?php endif; ?>
                <div class="produto-preco-atual">R$ <?= number_format($p['preco'], 2, ',', '.') ?></div>
                <button class="produto-btn">Comprar</button>
            </div>
            <?php endforeach; ?>
            <?php if (empty($produtos)): ?>
                <p style="color:#888;">Nenhum produto disponível no momento.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 BigGym. Todos os direitos reservados.</p>
    </footer>
    <!-- Modal de Confirmação -->
    <div id="modal-compra" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:#fff; padding:30px 40px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.2); text-align:center; min-width:280px;">
            <h2>Compra Realizada!</h2>
            <p id="modal-produto-nome" style="font-weight:bold; color:#ee0606; margin:20px 0;"></p>
            <button onclick="fecharModalCompra()" style="background:#ee0606; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer;">Fechar</button>
        </div>
    </div>
    <!-- Modal Carrinho -->
    <div id="modal-carrinho" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:#fff; padding:30px 40px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.2); text-align:center; min-width:320px; max-width:90vw;">
            <h2>Seu Carrinho</h2>
            <div id="carrinho-lista" style="margin:20px 0; text-align:left;"></div>
            <div id="carrinho-total" style="font-weight:bold; color:#ee0606; margin-bottom:20px;"></div>
            <button onclick="fecharCarrinho()" style="background:#888; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer; margin-right:10px;">Fechar</button>
            <button onclick="abrirCheckout()" id="btn-finalizar" style="background:#ee0606; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer;">Finalizar Compra</button>
        </div>
    </div>
    <!-- Modal Checkout -->
    <div id="modal-checkout" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:10000; justify-content:center; align-items:center;">
        <div style="background:#fff; padding:30px 40px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.2); text-align:center; min-width:320px; max-width:95vw;">
            <h2>Finalizar Compra</h2>
            <form id="form-checkout" style="margin:20px 0;">
                <input type="text" id="nome" placeholder="Nome completo" required style="width:90%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;"><br>
                <input type="email" id="email" placeholder="E-mail" required style="width:90%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;"><br>
                <input type="tel" id="telefone" placeholder="Telefone" required style="width:90%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;"><br>
                <textarea id="obs" placeholder="Observações" style="width:90%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;"></textarea><br>
                <div id="checkout-resumo" style="text-align:left;margin-bottom:15px;"></div>
                <button type="submit" style="background:#ee0606; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer;">Confirmar Pedido</button>
                <button type="button" onclick="fecharCheckout()" style="background:#888; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer; margin-left:10px;">Cancelar</button>
            </form>
        </div>
    </div>
    <!-- Modal Sucesso -->
    <div id="modal-sucesso" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:10001; justify-content:center; align-items:center;">
        <div style="background:#fff; padding:30px 40px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.2); text-align:center; min-width:320px; max-width:95vw;">
            <h2>Pedido Realizado!</h2>
            <p>Obrigado por comprar na BigGym.<br>Em breve entraremos em contato.</p>
            <button onclick="fecharSucesso()" style="background:#ee0606; color:#fff; border:none; border-radius:5px; padding:10px 20px; font-size:1rem; cursor:pointer;">Fechar</button>
        </div>
    </div>
    <script>
    // Torna todos os botões 'Comprar' funcionais
    const comprarBtns = document.querySelectorAll('.produto-btn');
    comprarBtns.forEach(btn => {
        if(btn.textContent.trim() === 'Comprar') {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const nome = btn.parentElement.querySelector('.produto-nome').textContent;
                document.getElementById('modal-produto-nome').textContent = `Produto: ${nome}`;
                document.getElementById('modal-compra').style.display = 'flex';
            });
        }
    });
    function fecharModalCompra() {
        document.getElementById('modal-compra').style.display = 'none';
    }
    // Utilidades do Carrinho
    function getCarrinho() {
        return JSON.parse(localStorage.getItem('carrinhoBigGym') || '[]');
    }
    function setCarrinho(carrinho) {
        localStorage.setItem('carrinhoBigGym', JSON.stringify(carrinho));
        atualizarBadgeCarrinho();
    }
    function atualizarBadgeCarrinho() {
        const carrinho = getCarrinho();
        let total = 0;
        carrinho.forEach(item => total += item.quantidade);
        document.getElementById('carrinho-quantidade').textContent = total;
    }
    function abrirCarrinho() {
        const carrinho = getCarrinho();
        const lista = document.getElementById('carrinho-lista');
        const btnFinalizar = document.getElementById('btn-finalizar');
        if (carrinho.length === 0) {
            lista.innerHTML = '<p style="color:#888;">Seu carrinho está vazio.</p>';
            document.getElementById('carrinho-total').textContent = '';
            btnFinalizar.disabled = true;
            btnFinalizar.style.opacity = 0.5;
        } else {
            let html = '<ul style="list-style:none;padding:0;">';
            let total = 0;
            carrinho.forEach((item, idx) => {
                html += `<li style='margin-bottom:12px;display:flex;align-items:center;gap:10px;'>`
                    + `<img src='${item.img}' alt='' style='width:40px;height:40px;border-radius:5px;'>`
                    + `<span style='flex:1;'>${item.nome} <br><span style='color:#888;font-size:0.95em;'>${item.subtitulo||''}</span></span>`
                    + `<span style='font-weight:bold;'>R$ ${item.preco.toFixed(2)}</span>`
                    + `<button onclick='alterarQtdCarrinho(${idx},-1)' style='background:#eee;color:#ee0606;border:none;border-radius:3px;padding:2px 8px;cursor:pointer;font-weight:bold;'>-</button>`
                    + `<span style='margin:0 8px;'>${item.quantidade}</span>`
                    + `<button onclick='alterarQtdCarrinho(${idx},1)' style='background:#eee;color:#ee0606;border:none;border-radius:3px;padding:2px 8px;cursor:pointer;font-weight:bold;'>+</button>`
                    + `<button onclick='removerDoCarrinho(${idx})' style='background:#ee0606;color:#fff;border:none;border-radius:3px;padding:2px 8px;cursor:pointer;margin-left:8px;'>Remover</button>`
                    + `</li>`;
                total += item.preco * item.quantidade;
            });
            html += '</ul>';
            lista.innerHTML = html;
            document.getElementById('carrinho-total').textContent = 'Total: R$ ' + total.toFixed(2);
            btnFinalizar.disabled = false;
            btnFinalizar.style.opacity = 1;
        }
        document.getElementById('modal-carrinho').style.display = 'flex';
    }
    function fecharCarrinho() {
        document.getElementById('modal-carrinho').style.display = 'none';
    }
    function removerDoCarrinho(idx) {
        let carrinho = getCarrinho();
        carrinho.splice(idx, 1);
        setCarrinho(carrinho);
        abrirCarrinho();
    }
    function alterarQtdCarrinho(idx, delta) {
        let carrinho = getCarrinho();
        carrinho[idx].quantidade += delta;
        if (carrinho[idx].quantidade < 1) carrinho[idx].quantidade = 1;
        setCarrinho(carrinho);
        abrirCarrinho();
    }
    // Adicionar ao carrinho ao clicar em Comprar
    const comprarBtns = document.querySelectorAll('.produto-btn');
    comprarBtns.forEach(btn => {
        if(btn.textContent.trim() === 'Comprar') {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const card = btn.parentElement;
                const nome = card.querySelector('.produto-nome').textContent;
                const preco = parseFloat((card.querySelector('.produto-preco-atual')||{textContent:'0'}).textContent.replace('R$','').replace(',','.'));
                const img = card.querySelector('img').getAttribute('src');
                const subtituloEl = card.querySelector('div[style*="font-size:0.98rem"]');
                const subtitulo = subtituloEl ? subtituloEl.textContent : '';
                let carrinho = getCarrinho();
                let found = carrinho.find(item => item.nome === nome && item.preco === preco);
                if(found) found.quantidade += 1;
                else carrinho.push({nome, preco, img, subtitulo, quantidade:1});
                setCarrinho(carrinho);
            });
        }
    });
    // Badge inicial
    atualizarBadgeCarrinho();
    // Checkout
    function abrirCheckout() {
        fecharCarrinho();
        const carrinho = getCarrinho();
        let resumo = '<b>Resumo do Pedido:</b><ul style="list-style:none;padding:0;">';
        let total = 0;
        carrinho.forEach(item => {
            resumo += `<li>${item.nome} (${item.quantidade}x) - R$ ${(item.preco*item.quantidade).toFixed(2)}</li>`;
            total += item.preco * item.quantidade;
        });
        resumo += `</ul><b>Total: R$ ${total.toFixed(2)}</b>`;
        document.getElementById('checkout-resumo').innerHTML = resumo;
        document.getElementById('modal-checkout').style.display = 'flex';
    }
    document.getElementById('form-checkout').onsubmit = function(e) {
        e.preventDefault();
        // Aqui você pode enviar para backend, WhatsApp, etc. Por enquanto só mostra sucesso e limpa carrinho
        setCarrinho([]);
        fecharCheckout();
        document.getElementById('modal-sucesso').style.display = 'flex';
    }
    function fecharCheckout() {
        document.getElementById('modal-checkout').style.display = 'none';
    }
    function fecharSucesso() {
        document.getElementById('modal-sucesso').style.display = 'none';
    }
    </script>
</body>
</html> 

