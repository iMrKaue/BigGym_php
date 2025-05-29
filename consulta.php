<?php // Arquivo convertido para PHP estático ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta - BigGym</title>
    <link rel="stylesheet" href="consulta.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Voltar para a Home</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Agendar Consulta com Nossos Especialistas</h1>
            <p>Preencha o formulário abaixo para agendar uma consulta personalizada.</p>
        </section>

        <section class="agendar-form">
            <form>
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="data">Data Preferida:</label>
                <input type="date" id="data" name="data" required>

                <label for="especialidade">Escolha a Especialidade:</label>
                <select id="especialidade" name="especialidade">
                    <option value="nutricionista">Nutricionista</option>
                    <option value="personal trainer">Personal Trainer</option>
                    <option value="fisioterapeuta">Fisioterapeuta</option>
                </select>

                <button type="submit" class="btn">Agendar Consulta</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 BigGym. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
