# CRUD de Produtos com Docker Compose

## 1. Descrição do projeto

Aplicação web simples em **PHP** para gerenciamento de **Produtos**, implementando as quatro
operações de um CRUD (Create, Read, Update, Delete). Os dados são armazenados em um banco
**MySQL** e todo o ambiente (aplicação + banco + interface de administração) é orquestrado com
**Docker Compose**.

A entidade **Produto** possui os seguintes campos:

- `id` — chave primária, auto incremento
- `nome` — varchar(100)
- `descricao` — texto
- `estoque` — quantidade em estoque (inteiro)

A tabela `produto` é criada automaticamente pelo próprio código PHP (arquivo `conexao.php`),
que executa um `CREATE TABLE IF NOT EXISTS` assim que a aplicação sobe — não é necessário
rodar nenhum script SQL manualmente.

## 2. Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) instalado
- [Docker Compose](https://docs.docker.com/compose/install/) instalado (já vem junto com o
  Docker Desktop nas versões mais recentes)

Não é necessário ter PHP ou MySQL instalados na máquina — tudo roda dentro dos containers.

## 3. Passo a passo para executar o projeto

1. Clone o repositório:

   ```bash
   git clone <URL_DO_REPOSITORIO>
   cd <PASTA_DO_REPOSITORIO>
   ```

2. Suba os containers:

   ```bash
   docker-compose up -d
   ```

3. A criação da tabela é automática: ao subir, o container `php` executa `conexao.php` na
   primeira requisição, que roda um `CREATE TABLE IF NOT EXISTS produto (...)`. Não é preciso
   nenhum comando manual de SQL.

4. Acesse a aplicação no navegador:

   - Aplicação: [http://localhost:8080](http://localhost:8080)
   - phpMyAdmin (opcional, para inspecionar o banco): [http://localhost:8081](http://localhost:8081)
     (servidor: `mysql`, usuário: `app`, senha: `app`)

5. Para derrubar os containers:

   ```bash
   docker-compose down
   ```

   Para derrubar e também apagar os dados persistidos:

   ```bash
   docker-compose down -v
   ```

## 4. Explicação do `docker-compose.yml`

O arquivo define três serviços conectados pela rede personalizada `minha-rede` (tipo `bridge`),
que permite que os containers se comuniquem entre si pelo **nome do serviço** em vez de IP.

- **`php`** — roda a aplicação. Usa a imagem oficial `php:8.3-apache` (Apache + PHP já prontos).
  O código-fonte da pasta `./src2` é montado como volume dentro do container em
  `/var/www/html`, então qualquer alteração local é refletida imediatamente. Na inicialização,
  o comando do serviço instala as extensões `mysqli`, `pdo` e `pdo_mysql` (necessárias para
  conectar ao MySQL) antes de iniciar o Apache. A porta `80` do container é mapeada para a
  `8080` do host. As variáveis de ambiente `DB_HOST`, `DB_USER`, `DB_PASSWORD` e `DB_NAME` são
  passadas diretamente no `docker-compose.yml` (sem uso de `.env`) e lidas pelo PHP através de
  `getenv()` em `conexao.php`.

- **`mysql`** — roda o banco de dados, usando a imagem oficial `mysql:8.4`. As variáveis de
  ambiente `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD` e `MYSQL_ROOT_PASSWORD` fazem o
  próprio MySQL criar automaticamente o banco `app` e o usuário de aplicação na primeira
  subida. Um volume nomeado (`mysql_data`) é montado em `/var/lib/mysql`, garantindo que os
  dados **não se percam** quando os containers forem reiniciados ou recriados.

- **`phpmyadmin`** — interface web opcional para inspecionar o banco visualmente, conectada ao
  serviço `mysql` através das variáveis `PMA_HOST` e `PMA_PORT`. Exposta na porta `8081`.

Todas as diretivas também estão comentadas linha a linha, em português, diretamente no arquivo
`docker-compose.yml`.

## 5. Pontos interessantes observados pela dupla

- Utilizamos variáveis de ambiente diretamente no `docker-compose.yml` (seção `environment`)
  para configurar a conexão do PHP com o banco, o que facilita trocar usuário/senha/host sem
  alterar uma linha de código PHP.
- Configuramos um volume nomeado (`mysql_data`) para o serviço `mysql`, garantindo a
  persistência dos dados mesmo depois de rodar `docker-compose down` e subir de novo.
- Criamos uma rede `bridge` isolada (`minha-rede`) para que os containers se enxerguem apenas
  pelo nome do serviço (`mysql`, `php`), sem precisar expor IPs internos e aumentando o
  isolamento do ambiente.
- Optamos por instalar as extensões `mysqli`/`pdo_mysql` via `command` no próprio serviço
  `php`, em vez de criar uma imagem customizada com `Dockerfile`, o que simplificou o setup
  para esse trabalho.
- Usar `docker-php-ext-install` a cada `up` deixa a subida um pouco mais lenta, mas evita ter
  que manter e versionar uma imagem própria — um trade-off aceitável para o escopo do projeto.

## 6. Autores

- Nome Fabrício da Silva
- Nome Thaina de Sá
- Nome Gustavo Oliveira Alves
