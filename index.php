<?php
session_start();
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
                <li><a href="#" onclick="showSection('produtos')">Produtos</a></li>
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <li><a href="#" onclick="showSection('minhas-aulas')">Minhas Aulas</a></li>
                    <li style="display:flex; align-items:center; gap:8px;">
                        <span style="color:#ee0606; font-weight:bold;">Olá, <?= htmlspecialchars($_SESSION['cliente']) ?></span>
                        <a href="logout.php" style="color:#ee0606; margin-left:10px;">Sair</a>
                    </li>
                <?php else: ?>
                    <li>
                        <div class="dropdown">
                            <button class="dropbtn">Login</button>
                            <div class="dropdown-content">
                                <a href="login_cliente.php">Login Aluno</a>
                                <a href="login_admin.php">Login Professor/Admin</a>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
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
        <section id="home" style="display: block;">
            <div class="home-card">
                <img src="logo.jpg" alt="Logo BigGym" style="max-width: 90px; margin-bottom: 18px; border-radius: 12px; box-shadow: 0 2px 8px #ee0606;">
                <h1>Bem-vindo à BigGym!</h1>
                <div style="font-size:1.18rem; color:#ee0606; font-weight:600; margin-bottom:18px;">Transforme seu corpo e sua mente. Venha fazer parte da família BigGym!</div>
                <div class="beneficios-home" style="display:flex; flex-wrap:wrap; gap:18px; justify-content:center; margin-bottom:28px;">
                    <div style="display:flex; align-items:center; gap:8px; background:#ffeaea; border-radius:8px; padding:8px 14px; font-size:1rem; color:#ee0606;"><span style="font-size:1.3em;">🏋️‍♂️</span>Equipamentos modernos</div>
                    <div style="display:flex; align-items:center; gap:8px; background:#ffeaea; border-radius:8px; padding:8px 14px; font-size:1rem; color:#ee0606;"><span style="font-size:1.3em;">👨‍🏫</span>Professores qualificados</div>
                    <div style="display:flex; align-items:center; gap:8px; background:#ffeaea; border-radius:8px; padding:8px 14px; font-size:1rem; color:#ee0606;"><span style="font-size:1.3em;">💪</span>Ambiente motivador</div>
                    <div style="display:flex; align-items:center; gap:8px; background:#ffeaea; border-radius:8px; padding:8px 14px; font-size:1rem; color:#ee0606;"><span style="font-size:1.3em;">📅</span>Aulas para todos os níveis</div>
                </div>
                <div style="margin-bottom: 18px;">
                    <span style="display:inline-block; background:#28a745; color:#fff; font-weight:bold; border-radius:8px; padding:7px 18px; font-size:1.08rem; box-shadow:0 2px 8px #28a74555; margin-bottom:6px;">🎉 Primeira aula grátis!</span>
                </div>
                <p>A academia mais completa para você transformar sua vida.<br>
                Equipamentos modernos, profissionais qualificados e um ambiente motivador para todos os objetivos.</p>
                <div style="display:flex; flex-wrap:wrap; gap:16px; justify-content:center; margin-bottom:18px;">
                    <button type="button" class="btn" onclick="showSection('inscricao')">Matricule-Se Agora e ganhe uma camiseta personalizada</button>
                </div>
            </div>
        </section>

        <section id="planos" style="display: none;">
            <h2>Nossos Planos</h2>
            <div class="planos-container">
                <?php
                $planos = $db->query('SELECT * FROM planos ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($planos as $plano): ?>
                    <div class="plano">
                        <h3><?= htmlspecialchars($plano['nome']) ?></h3>
                        <?php if (!empty($plano['descricao'])): ?>
                            <p><?= nl2br(htmlspecialchars($plano['descricao'])) ?></p>
                        <?php endif; ?>
                        <div class="preco">
                            <?php
                            $preco = $plano['preco'];
                            $preco_num = preg_replace('/[^0-9.,]/', '', $preco);
                            $preco_float = str_replace([','], ['.'], $preco_num);
                            if (is_numeric($preco_float)) {
                                echo 'R$ ' . number_format((float)$preco_float, 2, ',', '.');
                            } else {
                                echo 'R$ ' . htmlspecialchars($preco);
                            }
                            ?>
                        </div>
                        <button class="btn" onclick="selecionarPlano('<?= htmlspecialchars(addslashes($plano['nome'])) ?>')">Matricule-se</button>
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

        <section id="produtos" style="display: none;">
            <h2 style="color:#ee0606; text-align:center; margin-bottom:10px;">Produtos</h2>
            <div class="produtos-container" style="display:flex; flex-wrap:wrap; gap:32px; justify-content:center; margin-top:32px;">
                <?php
                $produtos = $db->query('SELECT * FROM produtos ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
                foreach ($produtos as $produto): ?>
                    <div class="produto-card" style="background:#fff; border-radius:22px; box-shadow:0 6px 32px rgba(238,6,6,0.10); padding:32px 24px 24px 24px; margin:12px 8px; max-width:340px; width:100%; display:flex; flex-direction:column; align-items:center; transition:box-shadow 0.2s, transform 0.2s; border:1.5px solid #f3f3f3;">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" style="max-width:140px; max-height:140px; border-radius:16px; margin-bottom:18px; box-shadow:0 2px 12px rgba(0,0,0,0.10); object-fit:cover; background:#fafafa;">
                        <?php endif; ?>
                        <h3 style="color:#ee0606; font-size:1.25rem; margin-bottom:8px; font-weight:bold; text-align:center; letter-spacing:0.5px;"> <?= htmlspecialchars($produto['nome']) ?> </h3>
                        <?php if (isset($produto['subtitulo']) && $produto['subtitulo']): ?>
                            <div style="color:#555; font-size:1.05rem; margin-bottom:8px; text-align:center; font-style:italic;"> <?= htmlspecialchars($produto['subtitulo']) ?> </div>
                        <?php endif; ?>
                        <div style="display:flex; flex-direction:column; align-items:center; gap:4px; margin-bottom:10px; width:100%;">
                            <div style="font-size:1.3rem; color:#ee0606; font-weight:bold; text-align:center; background:#ffeaea; border-radius:8px; padding:8px 18px; box-shadow:0 2px 8px #ee060633; letter-spacing:0.5px; width:100%;">
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
                            <?php if (isset($produto['preco_antigo']) && $produto['preco_antigo']): ?>
                                <div style="color:#888; font-size:1.02rem; text-decoration:line-through;">De: R$ <?= htmlspecialchars($produto['preco_antigo']) ?></div>
                            <?php endif; ?>
                            <?php if (isset($produto['desconto']) && $produto['desconto']): ?>
                                <div style="color:#28a745; font-size:1.02rem; font-weight:bold;">Desconto: <?= htmlspecialchars($produto['desconto']) ?>%</div>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($produto['descricao']) && $produto['descricao']): ?>
                            <p style="color:#222; font-size:1.08rem; margin-bottom:10px; min-height:32px; text-align:center; line-height:1.5;"> <?= nl2br(htmlspecialchars($produto['descricao'])) ?> </p>
                        <?php else: ?>
                            <p style="color:#888; font-size:1.02rem; text-align:center;">Sem descrição disponível.</p>
                        <?php endif; ?>
                        <?php if (isset($produto['disponivel'])): ?>
                            <div style="margin-top:8px; color:<?= ($produto['disponivel'] == 'Sim' || $produto['disponivel'] == 1) ? '#28a745' : '#ee0606' ?>; font-weight:bold; font-size:1.05rem;">
                                <?= ($produto['disponivel'] == 'Sim' || $produto['disponivel'] == 1) ? 'Disponível' : 'Indisponível' ?>
                            </div>
                        <?php endif; ?>
                        <div style="display:flex; gap:10px; margin-top:18px; width:100%; justify-content:center;">
                            <a href="pagamento.php?id=<?= $produto['id'] ?>" class="btn" style="background:#28a745; color:#fff; border-radius:8px; font-size:1.08rem; font-weight:bold; box-shadow:0 2px 8px #28a74555; padding:12px 28px; text-align:center; text-decoration:none; transition:background 0.2s, transform 0.2s;">Comprar</a>
                            <button type="button" class="btn" style="background:#fff; color:#28a745; border:2px solid #28a745; border-radius:8px; font-size:1.08rem; font-weight:bold; box-shadow:0 2px 8px #28a74533; padding:12px 18px; display:flex; align-items:center; gap:6px; transition:background 0.2s, color 0.2s;" onclick="adicionarCarrinho(<?= $produto['id'] ?>, '<?= htmlspecialchars(addslashes($produto['nome'])) ?>')"><span style='font-size:1.2em;'>🛒</span> Carrinho</button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($produtos)): ?>
                    <p style="color:#888; text-align:center;">Nenhum produto cadastrado.</p>
                <?php endif; ?>
            </div>
        </section>

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
                        <a href="marcar_aula.php?id=<?= $servico['id'] ?>" class="btn" style="margin-top:10px; display:inline-block; background:#28a745; color:#fff;">Marcar Aula</a>
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
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <span style="font-size:2.5rem; margin-bottom:10px; color:#ee0606;">💼</span>
                <h2>Trabalhe Conosco</h2>
                <div style="font-size:1.08rem; color:#444; margin-bottom:18px; text-align:center; max-width:420px;">Faça parte do time BigGym! Buscamos pessoas apaixonadas por saúde, bem-estar e atendimento de excelência. Envie seu currículo e venha crescer com a gente!</div>
                <form id="trabalhe-conosco-form" enctype="multipart/form-data">
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
            </div>
        </section>

        <section id="esqueceu-senha" style="display: none;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>   
            
            <button type="submit" class="btn">Enviar</button>
        </section>

        <?php if (isset($_SESSION['cliente_id'])): ?>
        <section id="minhas-aulas" style="display: none; max-width: 700px; margin: 40px auto 0 auto; background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 30px 20px 20px 20px;">
            <h2 style="color:#ee0606; text-align:center; margin-bottom:18px;">Minhas Aulas Marcadas</h2>
            <?php
            require_once 'db.php';
            $db = getDB();
            $id_cliente = $_SESSION['cliente_id'];
            $stmt = $db->prepare('SELECT m.id, m.status, s.nome, s.descricao FROM matriculas m JOIN servicos s ON m.id_aula = s.id WHERE m.id_cliente = ? ORDER BY m.id DESC');
            $stmt->execute([$id_cliente]);
            $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php if ($aulas): ?>
                <div class="servicos-container">
                <?php foreach ($aulas as $aula): ?>
                    <div class="servico">
                        <h3 style="color:#ee0606; font-size:1.2rem; margin-bottom:10px; font-weight:bold; background:none;"> <?= htmlspecialchars($aula['nome']) ?> </h3>
                        <p style="color:#222; font-size:1.05rem; margin-bottom:16px; min-height:48px; background:none;"> <?= nl2br(htmlspecialchars($aula['descricao'])) ?> </p>
                        <div style="margin-bottom:10px; color:#555; font-size:0.98rem;">Status: <b><?= htmlspecialchars($aula['status']) ?></b></div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color:#888; text-align:center;">Você ainda não marcou nenhuma aula.</p>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </main>
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
    <script>
    function adicionarCarrinho(id, nome) {
        alert('Produto "' + nome + '" adicionado ao carrinho!');
    }
    </script>