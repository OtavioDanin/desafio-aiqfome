# API de Gerenciamento de Produtos Favoritos

![Versão do PHP](https://img.shields.io/badge/php-8.1%2B-blue.svg)
![Framework](https://img.shields.io/badge/Framework-Hyperf%203.0-green.svg)
![Licença](https://img.shields.io/badge/License-MIT-brightgreen.svg)

API RESTful desenvolvida como parte do desafio Aiqfome. O projeto consiste em um sistema para gerenciar clientes e suas listas de produtos favoritos, com integração a uma API externa de produtos e um sistema de cache para otimização de performance.

## Tecnologias Utilizadas

- **Framework:** [Hyperf 3+](https://hyperf.io/) (baseado em Swoole)
- **Linguagem:** PHP 8.3+
- **Banco de Dados:** PostgreSQL(17+)
- **Cache:** Redis
- **Visualização do Cache com Interface redisinsight:** Redis/Redisinsight
- **Autenticação:** JWT (JSON Web Tokens) com a biblioteca `firebase/php-jwt`
- **Containerização:** Docker

## Funcionalidades

### Clientes
- ✅ CRUD completo (Criar, Listar, Exibir, Atualizar, Remover).
- ✅ Validação de dados obrigatórios: `nome` e `email`.
- ✅ Garante que cada `email` seja único na base de dados.

### Favoritos
- ✅ Permite que um cliente adicione ou remova produtos de sua lista de favoritos.
- ✅ Valida a existência do produto através da API externa `fakestoreapi.com`.
- ✅ Impede que o mesmo produto seja adicionado mais de uma vez na lista de um cliente.
- ✅ Utiliza cache em **Redis** para armazenar um produto já consultado, otimizando o tempo de resposta e reduzindo a dependência da API externa.

### Autenticação
- ✅ Endpoints protegidos utilizando autenticação via token JWT.
- ✅ Rota de autenticação para gerar o token de acesso a partir das credenciais do cliente(nome e email).

## Pré-requisitos

- PHP >= 8.3
- Composer 2.8+
- Docker e Docker Compose
- Git

## Instalação e Execução

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/desafio-aiqfome.git](https://github.com/seu-usuario/desafio-aiqfome.git)
    cd desafio-aiqfome
    ```

2.  **Instale as dependências:**
    ```bash
    composer install
    ```

3.  **Configure o ambiente:**
    Copie o arquivo de exemplo `.env.example` e configure suas variáveis de ambiente.
    ```bash
    cp .env.example .env
    ```
    Preencha as variáveis no arquivo `.env`, especialmente as de conexão com o **PostgreSQL**, **Redis** e a sua **JWT_SECRET**.

4.  **Inicie os serviços:**
    É recomendado o uso de Docker Compose para subir os contêineres do PostgreSQL e Redis.
    ```bash
    docker-compose up -d
    ```

5.  **Execute as Migrations:**
    Para criar as tabelas no banco de dados.
    ```bash
    php bin/hyperf.php migrate
    ```

6.  **Inicie o servidor:**
    ```bash
    php bin/hyperf.php start
    ```
    A API estará disponível em `http://localhost:9501`.

## Autenticação

Para acessar os endpoints protegidos, primeiro obtenha um token de acesso.

1.  **Faça uma requisição `POST` para a rota `/login`** com o e-mail e a senha de um cliente cadastrado.
    ```json
    {
      "email": "cliente@exemplo.com",
      "password": "sua-senha"
    }
    ```
2.  **A API retornará um `access_token`**. Use este token no cabeçalho `Authorization` para as requisições seguintes:
    ```
    Authorization: Bearer seu_token_aqui
    ```

## Endpoints da API

### Autenticação
| Método | Rota      | Descrição                       | Autenticação |
| :----- | :-------- | :-------------------------------- | :----------- |
| `POST` | `/api/auth/generate`  | Gera um token JWT para um cliente.| Pública      |

### Clientes
| Método   | Rota           | Descrição                       | Autenticação   |
| :------- | :------------- | :-------------------------------- | :------------- |
| `GET`    | `/api/clients`    | Lista todos os clientes.          | **Requerida** |
| `GET`    | `/api/clients/{id}` | Exibe os dados de um cliente.     | **Requerida** |
| `POST`   | `/api/clients`    | Cria um novo cliente.             | Pública        |
| `PUT`    | `/api/clients/{id}` | Atualiza os dados de um cliente.  | **Requerida** |
| `DELETE` | `/api/clients/{id}` | Remove um cliente.                | **Requerida** |

### Favoritos
| Método   | Rota                                           | Descrição                                 | Autenticação   |
| :------- | :--------------------------------------------- | :---------------------------------------- | :------------- |
| `POST`   | `/api/favorites`              | Adiciona um produto à lista de favoritos. | **Requerida** |
| `DELETE` | `/api/favorites/{favoriteId}`  | Remove um produto da lista de favoritos.  | **Requerida** |


## API Externa de Produtos

Este projeto consome a API pública [Fake Store API](https://fakestoreapi.com/docs) para validação e busca de detalhes dos produtos.

---

<p align="center">
  Feito com ❤️ por [Luiz Danin]
</p>