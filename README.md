# Projeto Biblioteca Virtual

## Visão Geral
Este projeto foi desenvolvido utilizando Laravel e Livewire, seguindo boas práticas de mercado para criar um sistema de cadastro de livros, autores e assuntos, além de incluir funcionalidades de vendas e relatórios.

## Tecnologias Utilizadas
- **Laravel**: Framework PHP para a construção do backend.
- **Livewire**: Para criação de componentes dinâmicos e reativos.
- **MySQL**: Banco de dados relacional.
- **Docker**: Para virtualização e gerenciamento do ambiente de desenvolvimento.
- **Docker Compose**: Para orquestração dos containers.

## Funcionalidades
- **CRUD de Livros, Autores e Assuntos:** Inclui validações e upload de imagens para a capa do livro.
- **Relatórios:** Criamos um relatório que agrega dados de livros, autores e assuntos por meio de uma view SQL.
- **Procedures e Views:**
    - **Procedure `ProcessarCompra`:** Responsável por processar as compras e calcular os valores totais.
    - **View `compras_view`:** Relaciona usuários e suas compras, incluindo livros comprados e valores totais.
    - **View `relatorio_autores_livros_assuntos_view`:** Cria um relatório detalhado que exibe os títulos dos livros, suas editoras, anos de publicação, os autores e os assuntos relacionados..

## Estrutura de Pastas
- **app/Services:** Contém a lógica de negócio para manipulação de livros, autores, assuntos e compras.
- **database/migrations:** Contém as migrações e a criação das views e procedures.

## Setup do Projeto
1. Clone o repositório.
2. Configure o `.env` com as credenciais do banco de dados.
3. Suba os containers do Docker:
    ```bash
    docker-compose up -d
    ```
4. O projeto será inicializado automaticamente dentro do container Docker e o script init_backend.sh será executado. Ele faz as seguintes verificações e operações:
    - Verifica se o arquivo `.env existe`, se não existir, ele será criado a partir do `.env.example`.
    - Aguarda o MySQL estar disponível antes de rodar as migrações e seeds.
    - Instala as dependências do Composer (caso não estejam instaladas).
    - Executa as migrations e seeds para preparar o banco de dados.
    - Cria um link simbólico para a pasta de storage.
    - Executa os testes automatizados.
    - Inicia o servidor Laravel na porta 8021.

## Acessar o Servidor
- http://localhost:8021

## Testes
- Utilizamos TDD em alguns pontos do projeto, incluindo testes para as procedures e relatórios.

## Relatório
O relatório exibe os livros agrupados por autor e assunto, gerado através de uma view no banco de dados em um arquivo Excel.

## Conclusão
Este projeto entrega além do que foi solicitado, integrando funcionalidades de vendas e relatórios dinâmicos, além de ser totalmente configurável via Docker para fácil implantação.
