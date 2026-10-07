# Wonner — instruções para o Claude

Projeto Integrador (TCC) do IFPR Umuarama. E-commerce em PHP sem framework.
Autores: Igor M. Delmonaco e Felipe T. Rodrigues.

## Como trabalhar com o Felipe

**Seja guia, não executor.** Antes de rodar qualquer comando, diga qual é e o
que faz. Comandos que ALTERAM estado — criar banco, apagar arquivo, instalar,
mudar configuração — devem ser propostos para ele executar. Criar e editar
arquivo também é alterar: proponha antes. Diagnóstico só de leitura pode ser
executado, avisando antes o que vai verificar e por quê.

Ao explicar o que foi verificado, separe o que foi MEDIDO do que foi INFERIDO.

**Motivo:** é um TCC, e ele precisa sustentar o próprio código em banca. A
escolha por PHP sem framework (D-30) foi para não depender de assistência
externa. Fazer por ele anula a decisão.

## Nível de complexidade

Felipe é estudante de curso técnico, com PHP básico. Ele já rejeitou uma
versão anterior deste projeto por complexidade excessiva.

**Não use:** namespace, autoload PSR-4, Composer (D-34), `declare(strict_types=1)`,
classes `final`, tipos de retorno (`: void`, `: never`), output buffering,
roteamento por expressão regular, operador de espalhamento (`...`), tipos de
união, atributos tipados, `match`.

**Use:** `require`, funções soltas, classe apenas como agrupamento de métodos
estáticos, `if`/`foreach`, PDO com `prepare`/`execute`. Um conceito novo por
vez, nomeado quando aparece.

Prefira a solução que ele consegue ler, não a mais robusta. Quando houver
escolha entre o correto e o simples, apresente as duas e deixe ele decidir.

## Fonte da verdade

Antes de sugerir qualquer coisa, leia:

- `docs/DECISOES.md` — 42 decisões, com as alternativas descartadas
- `docs/PENDENCIAS.md` — o que ainda falta decidir
- `docs/ENTREGAS.md` — as onze entregas até o MVP, e o que verificar em cada
- `docs/TCC.md` — o documento acadêmico e a especificação do modelo
- `docs/ddl.sql` — as 13 tabelas do banco

Decisão registrada não se revisita sem motivo novo. Se for revisitar, registre
como revogação, sem apagar a anterior.

Pendência resolvida não some: vira entrada em `DECISOES.md` (ou linha em "Fora
de escopo") e só então sai de `PENDENCIAS.md`.

Decisão que muda esquema, arquivo ou nome propaga para `ENTREGAS.md` no mesmo
movimento. O `DECISOES.md` fica certo sozinho; o `ENTREGAS.md` não.

## Estado das entregas

Toda entrega do `ENTREGAS.md` carrega status no título: **✅** feita · **🔄** em
andamento · **⬜** não começada. Na entrega em andamento, cada item da lista traz
`[x]` ou `[ ]`. Nenhuma entrega fica sem status.

**Trava 1 — nada avança em silêncio.** Commit que muda comportamento do sistema
atualiza o `ENTREGAS.md` no mesmo commit: o item marcado e, quando for o caso, o
status da entrega. Código novo sem marca no documento é commit incompleto.

**Trava 2 — não listar tarefa sem reler.** Antes de dizer o que falta fazer, leia
o status e os itens da entrega corrente e declare o que já está feito. Lista
montada de memória manda refazer o que já foi entregue.

**Trava 3 — desenho que muda vira decisão.** Se a implementação divergir do que o
`ENTREGAS.md` descreve, não basta corrigir o texto: registra-se a decisão em
`DECISOES.md` com a alternativa descartada, e só então o `ENTREGAS.md` é
atualizado. O que o avaliador pergunta é por que não foi do outro jeito.

## Arquitetura

    publico/     única pasta exposta na web
      index.php    rotas (array), requires e verificação de acesso
      .htaccess    manda tudo para o index.php (só em produção, Apache)
      paginas/     uma por tela
      uploads/     imagens de produto; só as da demonstração são versionadas (D-40)
      img/         imagens do próprio site, como a de "imagem indisponível" (D-42)
    incluir/     código compartilhado, fora do alcance de URL
      config.php   senha do banco e constantes de negócio (não versionado)
      conexao.php  função conexao(), devolve o PDO
      funcoes.php  escapar(), dinheiro(), dataHora(), redirecionar(), avisar(), urlImagem()
      modelos.php  lista de require dos modelos
      modelos/     um por tabela, com TODO o SQL e as regras de negócio
      topo.php     começo do HTML; a página define $titulo antes
      rodape.php   fim do HTML
      protegido.php / protegido-admin.php   exigem login e papel
    server.php   roteador do servidor embutido (só desenvolvimento)

MySQL 8. Tailwind pelo CDN (vira CSS gerado pelo Tailwind CLI na E9). Sem
Composer, sem Node, sem Apache em desenvolvimento
(`php -S localhost:8000 -t publico server.php`).

## Regras que não se negociam

- Nenhum SQL nas páginas; todo SQL vive nos modelos
- Nenhum HTML nos modelos
- Nenhuma regra de negócio nas páginas; `if` sobre a requisição, nunca sobre a regra
- Ação que altera dados é **uma** operação do modelo — se mexer em várias tabelas, é um
  método só, que faz tudo numa transação (D-38). Leituras para montar a tela podem ser
  várias, **nunca dentro de um laço** (D-37)
- Todo dado que vai para a tela passa por `escapar()`
- Todo valor externo em consulta usa `prepare()` + `execute()`, nunca concatenação
- `incluir/` nunca vai para dentro de `publico/`
- Rota nova é uma linha no array `$rotas` do `index.php` mais um arquivo em `paginas/`
- Verificação de acesso fica no `index.php`, não dentro da página
- Constantes de negócio ficam em `config.php`, com o número da decisão ao lado
- `config.php` não vai para o Git; mudança nele também entra no `config-exemplo.php`
