<?php // Arquivo convertido para PHP estático ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinamento Personalizado - BigGym</title>
    <link rel="stylesheet" href="personal.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Voltar para a Home</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Contrate Treinamento Personalizado</h1>
            <p>Treinos exclusivos e sob medida para alcançar seus objetivos!</p>
        </section>

        <section class="personal-form">
            <form>
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="objetivo">Qual seu principal objetivo?</label>
                <select id="objetivo" name="objetivo">
                    <option value="hipertrofia">Hipertrofia</option>
                    <option value="emagrecimento">Emagrecimento</option>
                    <option value="condicionamento">Condicionamento Físico</option>
                    <option value="saude geral">Saúde Geral</option>
                </select>

                <label for="sessao">Quantas sessões deseja contratar?</label>
                <select id="sessao" name="sessao">
                    <option value="10">10 Sessões</option>
                    <option value="20">20 Sessões</option>
                    <option value="30">30 Sessões</option>
                </select>

                <button type="submit" class="btn">Contratar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 BigGym. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
