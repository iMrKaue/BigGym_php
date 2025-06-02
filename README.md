# BigGym

> Este projeto foi desenvolvido como parte das atividades acadêmicas da faculdade.

Sistema web para gerenciamento de academia, com área do cliente, área administrativa, controle de produtos, planos, funcionários, serviços e muito mais.

## Funcionalidades

- **Página principal** com informações da academia, planos, serviços e produtos.
- **Área do Cliente**: login, visualização de aulas marcadas, compra de produtos.
- **Área Administrativa**: gerenciamento de produtos, planos, funcionários, serviços, informações do site, rodapé e redes sociais.
- **Carrinho de compras** para produtos/suplementos.
- **Sistema de login** para clientes e administradores.
- **Cadastro de novos clientes**.
- **Controle de sessões** e logout seguro.

## Tecnologias Utilizadas

- **PHP** (com PDO para acesso ao banco de dados)
- **SQLite** (ou MySQL, dependendo da configuração do seu `db.php`)
- **HTML5, CSS3 e JavaScript**
- **LocalStorage** para o carrinho de compras no frontend

## Instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/BigGym.git
   ```
2. **Coloque os arquivos em seu servidor local** (ex: XAMPP, WAMP, Laragon, etc).
3. **Configure o banco de dados**:
   - O arquivo `db.php` está preparado para criar as tabelas automaticamente se não existirem (verifique se o caminho do banco está correto).
   - Caso use MySQL, ajuste as credenciais no `db.php`.

4. **Acesse o sistema:**
   - Abra o navegador e acesse `http://localhost/BigGym/index.php`

## Estrutura de Pastas

- `index.php` — Página principal do site
- `admin.php` — Painel administrativo
- `produtos.php` — Página de produtos (pode ser desativada se não for mais usada)
- `login_cliente.php` / `login_admin.php` — Login de clientes e administradores
- `db.php` — Conexão com o banco de dados
- `style.css` — Estilos do site
- `imagens/` — Imagens do site e produtos

## Como usar

- **Clientes** podem se cadastrar, fazer login, marcar aulas e comprar produtos.
- **Administradores** podem acessar o painel admin para gerenciar todo o conteúdo do site.
- **Logout** redireciona sempre para a home.

## Observações

- O sistema utiliza LocalStorage para o carrinho de compras, então o carrinho é salvo apenas no navegador do usuário.
- Para produção, recomenda-se migrar o banco para MySQL e reforçar a segurança das sessões e senhas.

## Licença

Este projeto é livre para uso acadêmico e pessoal. Para uso comercial, consulte o autor.

---

> Feito com ❤️ por BigGym Team
