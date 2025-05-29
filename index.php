<?php
require 'db.php';
// Busca o conteúdo da home
$db = getDB();
$stmt = $db->query('SELECT * FROM home_content LIMIT 1');
$home = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$home) $home = [];
// Busca conteúdo do site
$sobre = $db->query('SELECT * FROM sobre LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$contato = $db->query('SELECT * FROM contato LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$redes = $db->query('SELECT * FROM redes LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$footer = $db->query('SELECT * FROM footer LIMIT 1')->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BigGym - Academia</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>
<body>
    <header>
        <nav>
            <a href="#" onclick="showSection('home')">
                <div class="header" style="background-color: black; height: 60px;">
                    <img src="logo.jpg" alt="Logo" class="logo">
                </div>
            </a>
            <ul>
                <li><a href="#" onclick="showSection('home')">Home</a></li>
                <li><a href="#" onclick="showSection('planos')">Planos</a></li>
                <li><a href="#" onclick="showSection('servicos')">Serviços</a></li>
                <li><a href="#" onclick="showSection('trabalhe-conosco')">Trabalhe Conosco</a></li> 
                <li><a href="produtos.php">Produtos</a></li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">Login</button>
                        <div class="dropdown-content">
                            <a href="login_cliente.php">Login Aluno</a>
                            <a href="login_admin.php">Login Professor/Admin</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">Sobre</button>
                        <div class="dropdown-content">
                            <a href="#" onclick="showSection('sobre')">Sobre Nós</a>
                            <a href="#" onclick="showSection('contato')">Contato</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="home">
            <div style="max-width: 600px; margin: 60px auto 0 auto; text-align: center; padding: 40px 20px 30px 20px; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07);">
                <h1 style="font-size:2.2rem; color:#ee0606; margin-bottom:18px;">Bem-vindo à BigGym!</h1>
                <p style="font-size:1.15rem; color:#222; margin-bottom:32px;">A academia mais completa para você transformar sua vida.<br>Equipamentos modernos, profissionais qualificados e um ambiente motivador para todos os objetivos.</p>
                <button type="button" class="btn home-btn" onclick="showSection('inscricao')">Matrícule-Se Agora e ganhe uma camiseta personalizada</button>
            </div>
        </section>

        <section id="sobre" style="display: none;">
            <h2>Sobre a BigGym</h2>
            <p><?= isset($sobre['texto']) && $sobre['texto'] ? nl2br(htmlspecialchars($sobre['texto'])) : 'Na BigGym, oferecemos um espaço completo com equipamentos de última geração e profissionais qualificados para te ajudar a alcançar seus resultados.' ?></p>
            <?php if (isset($sobre['imagem']) && $sobre['imagem']): ?>
                <img src="<?= htmlspecialchars($sobre['imagem']) ?>" alt="Sobre a BigGym" style="max-width:350px; border-radius:10px; margin:20px auto; display:block;">
            <?php endif; ?>
            <div class="dropdown">
                <!-- <button class="dropbtn">Saiba mais</button> -->
                <div class="dropdown-content">
                    <!-- <a href="#" onclick="showInfo('missao')">Missão</a>
                    <a href="#" onclick="showInfo('visao')">Visão</a>
                    <a href="#" onclick="showInfo('valores')">Valores</a> -->
                </div>
            </div>
        </section>

        <section id="planos" style="display: none;">
            <h2>Nossos Planos</h2>
            <div class="planos-container">
                <?php
                $planos = $db->query('SELECT * FROM planos ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($planos as $plano): ?>
                    <div class="plano" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; min-height: 260px;">
                        <h3 style="text-align:center; word-break:break-word; max-width: 90%;"><?= htmlspecialchars($plano['nome']) ?></h3>
                        <?php if (!empty($plano['descricao'])): ?>
                            <p style="margin: 10px 0 18px 0; color: #333; text-align:center; min-height: 48px; word-break:break-word; max-width: 90%; background: none; color: #222; font-weight: 500; line-height:1.3;">
                                <?= nl2br(htmlspecialchars($plano['descricao'])) ?>
                            </p>
                        <?php endif; ?>
                        <div style="margin: 10px 0 18px 0; font-size: 1.5rem; color: #ee0606; font-weight: bold; text-align:center; background: none;">
                            <?php
                            $preco = $plano['preco'];
                            // Remove caracteres não numéricos exceto vírgula e ponto
                            $preco_num = preg_replace('/[^0-9.,]/', '', $preco);
                            // Tenta converter para float
                            $preco_float = str_replace([','], ['.'], $preco_num);
                            if (is_numeric($preco_float)) {
                                echo 'R$ ' . number_format((float)$preco_float, 2, ',', '.');
                            } else {
                                echo 'R$ ' . htmlspecialchars($preco);
                            }
                            ?>
                        </div>
                        <button class="btn" style="margin-top:auto; width: 100%;" onclick="selecionarPlano('<?= htmlspecialchars(addslashes($plano['nome'])) ?>')">Matricule-se</button>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($planos)): ?>
                    <p style="color:#888; text-align:center;">Nenhum plano cadastrado.</p>
                <?php endif; ?>
            </div>
        </section>
        <script>
        function selecionarPlano(nomePlano) {
            document.getElementById('plano').value = nomePlano;
            showSection('inscricao');
        }
        </script>

        <section id="servicos" style="display: none;">
            <h2 style="color:#ee0606; text-align:center; margin-bottom:10px; text-shadow:1px 1px 6px #fff; background:none;">Serviços Oferecidos</h2>
            <p style="text-align:center; font-size:1.15rem; color:#222; margin-bottom:24px; font-weight:500; background:none;">Venha marcar sua aula!</p>
            <div class="servicos-container">
                <?php
                $servicos = $db->query('SELECT * FROM servicos ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($servicos as $servico): ?>
                    <div class="servico">
                        <?php if (!empty($servico['imagem'])): ?>
                            <img src="<?= htmlspecialchars($servico['imagem']) ?>" alt="<?= htmlspecialchars($servico['nome']) ?>">
                        <?php endif; ?>
                        <h3 style="color:#ee0606; font-size:1.2rem; margin-bottom:10px; font-weight:bold; background:none;"><?= htmlspecialchars($servico['nome']) ?></h3>
                        <p style="color:#222; font-size:1.05rem; margin-bottom:16px; min-height:48px; background:none;"><?= nl2br(htmlspecialchars($servico['descricao'])) ?></p>
                        <?php if (!empty($servico['link'])): ?>
                            <a href="<?= htmlspecialchars($servico['link']) ?>" class="btn" target="_blank" style="margin-top:10px; display:inline-block;">Agendar Serviço</a>
                        <?php endif; ?>
                        <div style="margin-top:12px; color:#555; font-size:0.98rem; background:none;">Aproveite para experimentar uma aula gratuita e conhecer nossos profissionais!</div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($servicos)): ?>
                    <p style="color:#888; text-align:center;">Nenhum serviço cadastrado.</p>
                <?php endif; ?>
            </div>
        </section>
        

        <section id="contato" style="display: none;">
            <h2>Entre em Contato</h2>
            <p>
                <?php if (!empty($contato['email'])): ?>Email: <?= htmlspecialchars($contato['email']) ?><br><?php endif; ?>
                <?php if (!empty($contato['telefone'])): ?>Telefone: <?= htmlspecialchars($contato['telefone']) ?><br><?php endif; ?>
                <?php if (!empty($contato['endereco'])): ?>Endereço: <?= htmlspecialchars($contato['endereco']) ?><br><?php endif; ?>
            </p>
            <?php if (!empty($contato['mapa'])): ?>
                <div style="margin:20px 0; text-align:center;">
                    <?= $contato['mapa'] ?>
                </div>
            <?php endif; ?>
        </section>

        <section id="inscricao" style="display: none;">
            <h2>Adquira seu Plano</h2>
            <form>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="genero">Gênero:</label>
                <select id="gender" name="gender">
                    <option value="male">Masculino</option>
                    <option value="female">Feminino</option>
                    <option value="other">Outro</option>
                </select>
                <label for="plano">Escolha seu plano:</label>
                <select id="plano" name="plano">
                    <?php foreach ($planos as $plano): ?>
                        <option value="<?= htmlspecialchars($plano['nome']) ?>"><?= htmlspecialchars($plano['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Enviar</button>
            </form>
        </section>

        <section id="login" style="display: none;">
            <h2>Login Aluno</h2>
            <form action="/login" method="POST">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" name="email" required>
                <label for="login-senha">Senha</label>
                <input type="password" id="login-senha" name="senha" required>
                <button type="submit" class="btn">Entrar</button>
                <a href="#" style="display: block; margin-top: 10px; color:#ee0606;" onclick="showSection('esqueceu-senha')">Esqueceu a Senha?</a>
                <a href="#" style="display: block; margin-top: 10px; color:#ee0606;" onclick="showSection('planos')">Ainda não faz parte, adquira seu plano!</a>
            </form>
        </section>

        <section id="login professor" style="display: none;">
            <h2>Login Professor</h2>
            <form action="/login" method="POST">
                <label for="login-email">Email</label>
                <input type="email" id="login-email" name="email" required>
                <label for="login-senha">Senha</label>
                <input type="password" id="login-senha" name="senha" required>
                <button type="submit" class="btn">Entrar</button>
                <a href="#" style="display: block; margin-top: 10px; color:#ee0606;" onclick="showSection('esqueceu-senha')">Esqueceu a Senha?</a>
                <a href="#" style="display: block; margin-top: 10px; color:#ee0606;" onclick="showSection('trabalhe-conosco')">Ainda não faz parte da nossa equipe? Envie seu currículo!</a>
            </form>
        </section>

        <section id="trabalhe-conosco" style="display: none;">
            <h2>Trabalhe Conosco</h2>
            <p>Preencha o formulário abaixo para se candidatar a uma vaga na BigGym.</p>
            <form id="trabalhe-conosco-form">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" required>
                
                <label for="cargo">Cargo de Interesse:</label>
                <select id="cargo" name="cargo" required>
                    <option value="personal">Personal Trainer</option>
                    <option value="recepcionista">Recepcionista</option>
                    <option value="gerente">Gerente</option>
                    <option value="auxiliar">Auxiliar Geral</option>
                </select>
                
                <label for="curriculo">Anexar Currículo (PDF):</label>
                <input type="file" id="curriculo" name="curriculo" accept=".pdf" required>
                
                <button type="submit" class="btn">Enviar Candidatura</button>
            </form>
        </section>

        <section id="esqueceu-senha" style="display: none;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>   
            
            <button type="submit" class="btn">Enviar</button>
        </section>

        <footer>
            <div class="footer-content">
                <p><?= isset($footer['texto']) && $footer['texto'] ? nl2br(htmlspecialchars($footer['texto'])) : '&copy; 2024 BigGym. Todos os direitos reservados.' ?></p>
                <div class="social-media">
                    <?php if (!empty($redes['facebook'])): ?><a href="<?= htmlspecialchars($redes['facebook']) ?>" target="_blank">Facebook</a><?php endif; ?>
                    <?php if (!empty($redes['instagram'])): ?><a href="<?= htmlspecialchars($redes['instagram']) ?>" target="_blank">Instagram</a><?php endif; ?>
                    <?php if (!empty($redes['whatsapp'])): ?><a href="<?= htmlspecialchars($redes['whatsapp']) ?>" target="_blank">WhatsApp</a><?php endif; ?>
                    <?php if (!empty($redes['youtube'])): ?><a href="<?= htmlspecialchars($redes['youtube']) ?>" target="_blank">YouTube</a><?php endif; ?>
                </div>
            </div>
        </footer>
    </main>