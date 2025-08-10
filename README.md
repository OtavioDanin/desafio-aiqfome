# API de Gerenciamento de Produtos Favoritos

![Versão do PHP](https://img.shields.io/badge/php-8.1%2B-blue.svg)
![Framework](https://img.shields.io/badge/Framework-Hyperf%203.0-green.svg)
![Licença](https://img.shields.io/badge/License-MIT-brightgreen.svg)

API RESTful desenvolvida como parte do desafio Aiqfome. O projeto consiste em um sistema para gerenciar clientes e suas listas de produtos favoritos, com integração a uma API externa de produtos e um sistema de cache para otimização de performance.

# Arquitetura e Design do Projeto: Monólito Modular com DDD e Clean Architecture

Este projeto foi estruturado utilizando uma abordagem moderna e robusta que combina os princípios de **Monólito Modular**, **Domain-Driven Design (DDD)** e **Clean Architecture**. O objetivo é criar uma base de código organizada, coesa, com baixo acoplamento e alta testabilidade, proporcionando os benefícios de organização dos microsserviços sem a complexidade operacional.

A imagem da estrutura de pastas reflete perfeitamente a aplicação destes conceitos:

```
Modules/
├── Clients/
│   ├── Application
│   ├── Domain
│   ├── DTO
│   ├── Infrastructure
│   └── UI
└── Favorites/
    ├── Application
    ├── Domain
    ├── DTO
    ├── Infrastructure
    └── UI
```

## 1. O Monólito Modular (A Estrutura Geral)

Em vez de construir uma única aplicação monolítica onde tudo está misturado, o código é dividido em **Módulos** independentes que podem se comunicar de forma bem definida.

-   **Aplicação no Projeto:** O diretório `Modules/` é a materialização desta ideia. `Clients` e `Favorites` são módulos de negócio distintos. Eles são deployados juntos como uma única aplicação (o monólito), mas seu código é logicamente separado, facilitando a manutenção e a evolução. O módulo `Common` (ou *Shared Kernel*) contém código que é verdadeiramente compartilhado entre os outros módulos, como interfaces genéricas ou classes base.

## 2. Domain-Driven Design - DDD (O Coração do Negócio)

O DDD foca em modelar o software em torno do domínio do negócio. Cada módulo representa um **Bounded Context** (Contexto Delimitado), que é uma fronteira onde um modelo de domínio específico se aplica.

-   **Aplicação no Projeto:**
    -   **Módulo `Clients`:** É o Bounded Context para tudo relacionado a clientes. As regras de negócio, como "o e-mail deve ser único", vivem aqui.
    -   **Módulo `Favorites`:** É o Bounded Context para a lógica de favoritos. Regras como "um produto não pode ser duplicado" pertencem a este módulo.
    -   Essa separação garante que a complexidade de cada parte do negócio seja tratada de forma isolada.

## 3. Clean Architecture (A Organização Interna dos Módulos)

Enquanto o Monólito Modular define a organização *macro* do projeto, a Clean Architecture define a organização *micro* (interna) de cada módulo. Ela organiza o código em camadas, com uma regra estrita de dependência: **as dependências apontam sempre para dentro**.

A estrutura de pastas dentro de cada módulo (`Application`, `Domain`, `Infrastructure`, `UI`) implementa diretamente essas camadas:

-   **`Domain` (O Núcleo):**
    -   **Propósito:** Contém a lógica de negócio mais pura e as regras essenciais.
    -   **Conteúdo:** Entidades (ex: `Cliente.php`), Value Objects, e, crucialmente, as **interfaces dos repositórios** (ex: `ClienteRepositoryInterface.php`).
    -   **Regra:** Não depende de nenhuma outra camada.

-   **`Application` (Casos de Uso):**
    -   **Propósito:** Orquestra o fluxo de dados e dispara a lógica do domínio para executar os casos de uso do sistema.
    -   **Conteúdo:** Classes de serviço ou "Use Cases" (ex: `Um produto não pode ser duplicado na lista do CLiente.php`).
    -   **Regra:** Depende da camada `Domain`, mas não sabe nada sobre a `UI` ou a `Infrastructure`.

-   **`UI` (Interface com o Mundo Externo):**
    -   **Propósito:** É o ponto de entrada do módulo.
    -   **Conteúdo:** **Controllers** do Hyperf (`ClienteController.php`), rotas, e possivelmente comandos de console.
    -   **Regra:** Recebe as requisições HTTP e chama os serviços da camada `Application`.

-   **`Infrastructure` (Detalhes Técnicos):**
    -   **Propósito:** Contém tudo que é volátil e externo: banco de dados, caches, clientes de API, etc.
    -   **Conteúdo:** Implementações concretas das interfaces do `Domain` (ex: `HttpClient.php`, `RedisCache.php`), clientes HTTP para a `FakeStoreAPI`.
    -   **Regra:** Depende das camadas internas (`Domain`, `Application`), mas as camadas internas não dependem dela, apenas de suas abstrações (interfaces).

-   **`DTO` (Data Transfer Objects):**
    -   **Propósito:** Objetos simples que carregam dados entre as camadas, especialmente da `UI` para a `Application`, garantindo um contrato de dados claro e imutável.

### Fluxo de uma Requisição

A combinação destes padrões cria um fluxo de dados limpo e desacoplado:

1.  Uma requisição HTTP chega à camada **UI** (`ClienteController`).
2.  O `Controller` utiliza um **DTO** para empacotar os dados da requisição.
3.  O `Controller` invoca um caso de uso na camada de **Application**.
4.  O `UseCase` executa a lógica e utiliza uma interface do **Domain** (`ClienteRepositoryInterface`) para solicitar a persistência.
5.  O contêiner de Injeção de Dependência do Hyperf fornece a implementação concreta, que está na camada de **Infrastructure** (`ClienteRepository`), para salvar o dado no banco.

Essa arquitetura resulta em um sistema **escalável**, **fácil de testar** (pois cada camada pode ser testada de forma isolada) e **pronto para o futuro** (um módulo bem definido pode, se necessário, ser extraído para um microsserviço com muito menos esforço).

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