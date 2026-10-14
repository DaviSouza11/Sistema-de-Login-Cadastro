# Sistema de Login e Cadastro

Aplicação web full stack para cadastro e autenticação de usuários. O projeto combina uma interface desenvolvida em React e TypeScript com uma API REST em PHP e persistência de dados em MySQL.

O objetivo é praticar conceitos fundamentais de desenvolvimento back-end, como organização em camadas, validação de dados, consultas preparadas, hash de senhas, autenticação por token e comunicação entre cliente, servidor e banco de dados.

> **Status:** projeto educacional em desenvolvimento. Antes de utilizar a aplicação, consulte a seção [Limitações atuais](#limitações-atuais).

## Funcionalidades

- Cadastro de usuários com nome, e-mail e senha.
- Verificação de e-mail já cadastrado.
- Armazenamento seguro de senhas com `password_hash`.
- Autenticação de usuários com `password_verify`.
- Geração de token JWT com tempo de expiração.
- Listagem, edição e exclusão de usuários pela API.
- Interface responsiva para cadastro e login.
- Persistência do token de autenticação no navegador.
- Acesso ao MySQL por PDO e consultas preparadas.

## Tecnologias

### Front-end

- React 19
- TypeScript
- Vite
- CSS
- Fetch API
- Oxlint

### Back-end

- PHP
- API REST
- PDO
- MySQL
- JWT com assinatura HMAC SHA-256
- CORS

## Arquitetura

```text
React + TypeScript
        |
        | HTTP / JSON
        v
     API PHP
        |
        | PDO / consultas preparadas
        v
      MySQL
```

O back-end está organizado em responsabilidades separadas:

```text
backend/
|-- api/
|   |-- auth/
|   |   `-- loginUsuarios.php
|   `-- usuarios.php
|-- class/
|   |-- classes.php
|   `-- Usuario.php
|-- config/
|   `-- Conexao.php
`-- index.php
```

O front-end possui páginas independentes para os principais fluxos:

```text
src/
|-- assets/
|-- pages/
|   |-- Cadastro/
|   |-- Home/
|   `-- Login/
|-- App.tsx
`-- main.tsx
```

## Requisitos

Antes de iniciar, instale:

- Node.js 20 ou superior.
- npm.
- PHP 8.1 ou superior.
- Extensão `pdo_mysql` habilitada no PHP.
- MySQL 8 ou superior.

Uma instalação como XAMPP ou WampServer também pode ser utilizada para disponibilizar PHP e MySQL no Windows.

## Configuração do banco de dados

Crie o banco e a tabela utilizados pela aplicação:

```sql
CREATE DATABASE sistema_react_php
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistema_react_php;

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

No estado atual, a conexão local utiliza os seguintes valores em `backend/config/Conexao.php`:

```text
Host: localhost
Porta: 3306
Banco: sistema_react_php
Usuário: root
```

Para evoluir o projeto, mova essas configurações para variáveis de ambiente e mantenha apenas um arquivo `.env.example` no repositório.

## Instalação

Clone o repositório:

```bash
git clone https://github.com/DaviSouza11/Sistema-de-Login-Cadastro.git
cd Sistema-de-Login-Cadastro
```

Instale as dependências do front-end:

```bash
npm install
```

## Execução local

### 1. Inicie o back-end

Na raiz do projeto, execute:

```bash
php -S localhost:8080 -t backend backend/index.php
```

### 2. Inicie o front-end

Em outro terminal, execute:

```bash
npm run dev
```

O Vite exibirá o endereço local da interface, normalmente:

```text
http://localhost:5173
```

## Endpoints planejados

### Cadastrar usuário

```http
POST /api/cadastro
Content-Type: application/json
```

```json
{
  "nome": "Usuário de Exemplo",
  "email": "usuario@example.com",
  "password": "senha-segura"
}
```

Resposta esperada:

```json
{
  "mensagem": "Usuário cadastrado com sucesso!"
}
```

### Autenticar usuário

```http
POST /api/login
Content-Type: application/json
```

```json
{
  "email": "usuario@example.com",
  "senha": "senha-segura"
}
```

Resposta esperada:

```json
{
  "mensagem": "Login realizado com sucesso!",
  "token": "TOKEN_JWT"
}
```

### Gerenciar usuários

O arquivo `backend/api/usuarios.php` possui operações para:

| Método | Finalidade |
| --- | --- |
| `GET` | Listar usuários ou consultar um identificador |
| `POST` | Cadastrar um usuário |
| `PUT` | Atualizar nome e e-mail |
| `DELETE` | Excluir um usuário |

Essas operações ainda precisam ser protegidas por autenticação e autorização antes de uso fora de um ambiente local.

## Segurança implementada

- Hash de senhas utilizando a API nativa do PHP.
- Verificação de senha por hash durante o login.
- Consultas preparadas com PDO.
- Desativação da emulação de prepared statements.
- Token assinado com HMAC SHA-256.
- Prazo de expiração incluído no token.

## Limitações atuais

O projeto ainda está em desenvolvimento e possui pontos que precisam ser corrigidos antes de ser considerado funcional ou seguro para produção:

- O endpoint de login contém marcadores de conflito de merge que precisam ser resolvidos.
- As URLs chamadas pelo front-end precisam ser alinhadas com as rotas registradas no back-end.
- O caminho do endpoint de cadastro precisa ser corrigido no roteador PHP.
- A chave usada para assinar o JWT deve sair do código-fonte e ser lida de uma variável de ambiente.
- A validação da assinatura e da expiração do JWT precisa ser revisada e coberta por testes.
- O CORS deve permitir somente origens conhecidas em ambientes publicados.
- As operações de listar, editar e excluir usuários precisam de autenticação e autorização.
- As respostas da API devem seguir um formato consistente para sucesso e erro.
- Ainda não existem testes automatizados ou integração contínua.

## Próximas melhorias

- [ ] Resolver os conflitos pendentes no endpoint de login.
- [ ] Padronizar as rotas entre React e PHP.
- [ ] Adicionar validação de entrada no servidor.
- [ ] Mover configurações e segredos para variáveis de ambiente.
- [ ] Proteger endpoints privados com middleware de autenticação.
- [ ] Implementar controle de autorização por perfil.
- [ ] Adicionar testes unitários e de integração.
- [ ] Documentar a API com OpenAPI/Swagger.
- [ ] Adicionar Docker e Docker Compose.
- [ ] Configurar verificações automáticas com GitHub Actions.
- [ ] Publicar uma demonstração da aplicação.

## Scripts do front-end

```bash
npm run dev      # inicia o ambiente de desenvolvimento
npm run build    # gera a versão de produção
npm run lint     # executa a análise estática
npm run preview  # visualiza localmente o build gerado
```

## Aprendizados demonstrados

Este projeto foi criado para exercitar:

- comunicação entre front-end e API por HTTP e JSON;
- modelagem e persistência de usuários em banco relacional;
- autenticação e conceitos fundamentais de JWT;
- proteção de senhas;
- consultas seguras com PDO;
- tratamento de respostas e códigos HTTP;
- organização de uma aplicação full stack.

## Autor

**Davi Belo de Souza**

- [GitHub](https://github.com/DaviSouza11)
- [LinkedIn](https://www.linkedin.com/in/davi-belo-de-souza-6bb351262)

## Licença

Este projeto ainda não possui uma licença definida. Antes de permitir reutilização ou contribuições externas, adicione um arquivo `LICENSE` ao repositório.
