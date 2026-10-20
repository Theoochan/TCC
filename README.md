# Wonner

Plataforma de e-commerce. Projeto Integrador — IFPR Umuarama.
Igor M. Delmonaco · Felipe T. Rodrigues

PHP sem framework, MySQL, Tailwind CSS.

Modelo, view e controlador separados por responsabilidade: todo o SQL nos
modelos, o layout em `topo.php` e `rodape.php`, e o controlador dividido
entre o `index.php` — que roteia e verifica acesso — e o topo de cada
página, que lê a requisição e chama o modelo (D-37).

O Tailwind entra pelo CDN, que compila no navegador — nada para instalar.
Na entrega E9 isso vira um CSS gerado pelo Tailwind CLI (um executável
avulso, sem Node), e as classes do HTML não mudam.

---

## Como rodar

**1. Criar o arquivo de configuração**

```bash
cp incluir/config-exemplo.php incluir/config.php
```

Abra o `incluir/config.php` e ajuste usuário e senha do MySQL.

**2. Criar o banco**

```bash
mysql -u root -p < docs/ddl.sql
```

Sobem 13 tabelas, 12 chaves estrangeiras e 14 restrições `CHECK`.

**3. Carregar os dados de demonstração**

```bash
mysql -u root -p < docs/dml.sql
```

São as categorias, cores, produtos, variantes, imagens e faixas de frete da
apresentação. Sem isso o site abre, mas a vitrine fica vazia.

Para recomeçar do zero mais tarde, os três passos cabem num comando só — e
numa senha só:

```bash
{ echo "DROP DATABASE IF EXISTS wonner;"; cat docs/ddl.sql docs/dml.sql; } | mysql -u root -p
```

**4. Definir a senha do administrador**

A conta `admin@wonner.com.br` vem na carga **sem senha válida**: o campo tem um
texto que não é hash, de modo que a comparação sempre falha e ninguém entra com
ela. O hash não é versionado de propósito — este repositório é público, e cada
pessoa gera o seu.

Gere o hash da senha que você escolher:

```bash
php -r "echo password_hash('a-senha-que-voce-escolher', PASSWORD_DEFAULT), PHP_EOL;"
```

Copie a linha que sair — começa com `$2y$` — e grave-a **dentro do cliente do
MySQL**:

```sql
UPDATE usuario
   SET senha = 'cole-o-hash-aqui'
 WHERE email = 'admin@wonner.com.br';
```

Dentro do cliente, e não por `mysql -e "..."`: o hash tem `$2y$` e `$12$`, que o
shell tentaria expandir como variáveis em aspas duplas. O hash seria gravado
truncado, e a tela de login diria apenas "e-mail ou senha inválidos", sem pista
do motivo.

**5. Subir o site**

```bash
php -S localhost:8000 -t publico server.php
```

Abra <http://localhost:8000>.

Não precisa de Apache em desenvolvimento — o `php -S` já atende. O
Apache entra só na hospedagem, e é quando o `publico/.htaccess`
passa a valer.

---

## Estrutura

```
publico/          a única pasta que o servidor web expõe
├─ index.php        recebe toda requisição e decide a página
├─ .htaccess        manda tudo para o index.php (Apache)
├─ paginas/         uma por tela
└─ uploads/         imagens de produto

incluir/          código compartilhado, fora do alcance de URL
├─ config.php       senha do banco e constantes · não versionado
├─ conexao.php      função conexao(), devolve o PDO
├─ funcoes.php      escapar(), dinheiro(), redirecionar(), ...
├─ modelos.php      carrega os modelos
├─ modelos/         um por tabela, com todo o SQL
├─ topo.php         começo do HTML
├─ rodape.php       fim do HTML
├─ protegido.php    exige login
└─ protegido-admin.php

server.php        roteador do servidor embutido (desenvolvimento)
docs/             documento do TCC, decisões, DDL, diagramas
```

## Onde fica o que

| | Responsabilidade |
|---|---|
| `publico/index.php` | ligar endereço a arquivo, verificar acesso |
| `publico/paginas/*` | ler o formulário, chamar **uma** operação, montar o HTML |
| `incluir/modelos/*` | todo o SQL e **todas** as regras de negócio |
| `incluir/funcoes.php` | o que serve a várias páginas e não é de nenhuma tabela |

Nenhum SQL na página. Nenhum HTML no modelo.

**Toda** saída de dado passa por `escapar()`. **Toda** consulta com valor
usa `prepare()` e `execute()`, nunca concatenação.

## Documentação

| Arquivo | Conteúdo |
|---|---|
| [docs/TCC.md](docs/TCC.md) | o documento, versão corrente |
| [docs/DECISOES.md](docs/DECISOES.md) | 44 decisões, com as alternativas descartadas |
| [docs/PENDENCIAS.md](docs/PENDENCIAS.md) | o que falta decidir |
| [docs/ENTREGAS.md](docs/ENTREGAS.md) | as onze entregas até o MVP |
| [docs/ddl.sql](docs/ddl.sql) | criação do banco |
| [docs/diagramas/modelo-dados.html](docs/diagramas/modelo-dados.html) | referência visual do modelo |
