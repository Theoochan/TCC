# Pendências

Arquivo de trabalho. **O objetivo é sempre zerar.**

Cada pendência sai daqui de uma de três formas:

1. **Decidida** → vira uma entrada em [DECISOES.md](DECISOES.md) e é apagada daqui.
2. **Descartada** → vira uma linha na seção "Fora de escopo" de [DECISOES.md](DECISOES.md) e é apagada daqui.
3. **Executada** → o artefato passa a existir (diagrama, seção escrita, tela corrigida) e é apagada daqui.

Nada é resolvido "silenciosamente": se saiu daqui, ou está registrado lá, ou o artefato
está no repositório. É o registro de decisões que sustenta o capítulo de justificativas
do TCC.

Prefixos: `RQ` requisitos e regras de negócio · `MD` modelo de dados ·
`TP` tipos e DDL · `DV` divergência entre artefatos do documento ·
`DG` diagramas · `DS` correção nos arquivos de design ·
`DOC` seções do documento e apresentação.

Prioridade: 🔴 bloqueia · 🟡 importante · ⚪ pode esperar

Responsáveis: **Igor** — documento, diagramas e slides · **Felipe** — código e telas.

---

## Em aberto

| Código | Resp. | Prio | Item | Depende de |
|---|---|---|---|---|
| `DOC-01` | Igor | 🟡 | Colar o script DML na seção 4.3 — **o arquivo já existe** | — |
| `DOC-06` | Igor | 🟡 | Transcrever o `TCC.md` para o documento oficial | contínuo |
| `DG-01` | Igor | 🟡 | Redesenhar os três diagramas (2.1, 2.3, 2.4) | — |
| `DG-02` | Igor | ⚪ | Desenhar o diagrama de casos de uso (2.2) | — |
| `DOC-05` | Igor | 🟡 | Escrever a seção 5 — referências | — |
| `DOC-02` | Igor | 🟡 | Escrever a seção 3 — interfaces de vídeo e impressas | telas prontas (E9) |
| `DOC-04` | Igor | 🟡 | Escrever a seção 4.4 — cinco consultas dos relatórios | E8 |
| `DOC-03` | Igor | ⚪ | Escrever a seção 4.1 — protótipo | telas prontas (E9) |
| `DOC-07` | Igor | 🟡 | Slides da apresentação | documento fechado |
| `DS-01` | Felipe | 🟡 | Aplicar nas telas `.dc.html` as correções de escopo | — |

Os quatro primeiros não dependem de nada e podem começar hoje.

---

## Documento (Igor)

### DOC-01 🟡 A seção 4.3 está vazia, e a tabela de estado diz que não

O `docs/dml.sql` tem 282 linhas — a carga de demonstração completa, com as nove
combinações de `produto_cor` e as duas variantes de estoque zerado que existem de
propósito para demonstrar o tamanho riscado.

O corpo da seção 4.3 do `TCC.md` diz `⚠️ Não escrita`, mas a tabela de estado no topo do
mesmo arquivo diz `✅ Escrito`. **É a tabela que está errada.** Quem confiar nela entrega o
documento sem o script DML, que é entregável obrigatório e já está pronto.

**Fazer:** colar o conteúdo de `docs/dml.sql` na seção 4.3, como foi feito com o DDL na
4.2, e corrigir a linha da tabela de estado.

### DOC-02 🟡 Seção 3 — Interfaces

Hoje só existem os títulos. Precisa de:

- **3.1.1 Cadastros** e **3.1.2 Movimentações** — capturas das telas do sistema rodando
- **3.2 Interfaces impressas** — os cinco relatórios da seção 1.4.3-C: Vendas por Cliente ·
  Produtos Vendidos · Vendas por Tamanho · Estoque de Produtos · Pagamentos Recebidos

Depende das telas existirem. A vitrine e a página de produto já rodam; o resto vem com as
entregas E3 a E8.

### DOC-03 ⚪ Seção 4.1 — Protótipo

Não escrita. Os quatro arquivos de design em `docs/design/` são o protótipo e servem de
material.

### DOC-04 🟡 Seção 4.4 — Consultas dos relatórios

Não escrita. São as cinco consultas SQL definidas em `D-17`, que a entrega **E8** vai
escrever e executar. O texto da seção sai delas.

### DOC-05 🟡 Seção 5 — Referências

O corpo do texto já cita sete obras, mas a lista não existe. As citações usadas, com a
seção onde aparecem:

Visure Solutions (1.4) · Castro (2016) (1.4.1) · Brito (2010) (1.4.2) ·
Clemente (2024) (1.5) · Ramos (2013) (2.2) · Braz Junior (2007) (2.3) ·
Araújo (2008) (2.4).

**Não depende de mais nada** — é a tarefa mais rápida da lista.

### DOC-06 🟡 Transcrição para o documento oficial

O `TCC.md` é a cópia de trabalho e muda junto com o código. O documento oficial é derivado
dele. Para saber o que releer, a tabela de estado no topo do `TCC.md` traz duas linhas:

- **Decisões aplicadas ao `TCC.md`** — atualizada por quem edita o arquivo
- **Transcritas para o documento oficial** — atualizada pelo Igor ao transcrever

A diferença entre as duas é o que falta transcrever.

Ao exportar, apagar o bloco entre os comentários `SEÇÃO DE TRABALHO — APAGAR ANTES DE
EXPORTAR` e `FIM DA SEÇÃO DE TRABALHO`.

### DOC-07 🟡 Slides da apresentação

Ainda não começados. Material que já existe pronto para virar slide:

- `DECISOES.md` — 38 decisões com alternativas descartadas; a seção "Alternativas
  descartadas" de cada uma responde a "por que não fizeram de outro jeito?"
- `D-38` — o padrão de projeto adotado (Table Data Gateway, Fowler), com a comparação
  contra modelo único e contra agregados de DDD
- `D-36` — a modelagem de `produto_cor`, que é o ponto mais interessante do modelo
- `D-01` e `D-06` — reserva de estoque e confirmação assíncrona, o núcleo técnico do
  trabalho

---

## Diagramas (Igor)

### DG-01 🟡 Substituir as três imagens de diagrama
As especificações estão escritas no `TCC.md` (2.1, 2.3.1 e 2.4.1) e conferem com as
38 decisões. Falta reproduzi-las nas ferramentas e trocar os PNG em `docs/diagramas/`:

- **2.1 Diagrama geral** — árvore de módulos (8 cadastros, 4 movimentações, 5 relatórios)
- **2.3 Diagrama de classes** — 13 classes, operações próprias e multiplicidades (Astah)
- **2.4 Modelo relacional** — 13 relações, chaves e restrições (MySQL Workbench)

O diagrama Mermaid em 2.4.1 serve para conferir o modelo antes de redesenhar.

**Ao redesenhar a 2.3 (D-38):** manter as operações **sem** as chaves identificadoras —
`galeria()`, e não `galeria(produtoId, corId)` —, porque em UML o objeto carrega a própria
identidade. E acrescentar ao texto da seção a frase que liga diagrama e código:

> O diagrama representa o modelo em UML, onde cada objeto carrega a própria identidade. A
> implementação adota *Table Data Gateway*, com métodos estáticos por tabela, de modo que as
> chaves que o objeto carregaria aparecem como parâmetros no código: `galeria()` do diagrama
> corresponde a `ProdutoCor::galeria($produtoId, $corId)`.

### DG-02 ⚪ Diagrama de casos de uso ausente
A seção 2.2 tem apenas o parágrafo teórico, e a 1.4.1 afirma que cada requisito "inclui
o caso de uso associado" — nenhum RF cita. Com os 16 RF definidos, é montável.
**Fazer:** desenhar o diagrama e associar cada RF ao seu caso de uso.

---

## Design (Felipe)

### DS-01 🟡 Aplicar nas telas as correções decorrentes de D-05, D-07 e D-08
Os arquivos `.dc.html` ainda prometem recursos cortados. Correções por arquivo:

**Todas as telas**
- remover o seletor "PT / EN" (D-07)
- remover "Envio grátis · pedidos +R$ 349" da barra de anúncio (D-05)
- remover a contagem regressiva e o "Drop 03 / № 003" (D-05)
- remover o bloco de newsletter do rodapé (D-08)

**Homepage**
- remover o selo "-15%" do card de produto — preço promocional foi adiado (D-10)

**PDP**
- remover "Favoritar ♡" (D-07)
- remover "Nº 007/024" e "a sua é a nº 007 de 024"; manter a menção ao acabamento
  manual como texto (D-08)
- manter a seção "Combine no time", renomeada para produtos da mesma categoria (D-07)

**Checkout**
- remover o campo de cupom e a linha "Desconto (WELCOME10)" (D-07)
- remover "Mover p/ favoritos" (D-07)
- remover "Retirar no atelier" das modalidades de entrega (D-08)
- remover "Faltam R$ 21 para frete grátis" e o "PAC — grátis" (D-05)
- adicionar campo de CPF (D-08)
- trocar "10× de R$ 122,01" por 6× (D-03, D-08)
- trocar "Troca grátis em 30 dias" por 7 dias, conforme o CDC (D-18)
- remover a ambiguidade "já tenho conta": conta é obrigatória (D-08)
- manter o cronômetro "reservadas por 14:32 min", fixando a janela em 15 min (D-01, D-08)

Os arquivos vivem em `docs/design/`, versionados junto com as decisões.
