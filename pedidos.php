<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>

    <!-- Fontes -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>

    <!-- Estilos -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/pedidos.css">
</head>
<body>

<?php include("components/sidebar.php"); ?>
<?php include("components/modalSair.php"); ?>

<!-- Conteúdo principal -->
<main class="principal">

    <!-- Topbar -->
    <header class="topbar">
        <h1>Pedidos</h1>

        <div class="campo-busca">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="campoBusca" placeholder="Buscar pedido ou cliente..." oninput="filtrarTabela()">
        </div>

        <nav class="filtro-status" aria-label="Filtrar por status">
            <button class="filtro-btn ativo" onclick="filtrarStatus('todos', this)">Todos</button>
            <button class="filtro-btn" onclick="filtrarStatus('pendente', this)">Pendente</button>
            <button class="filtro-btn" onclick="filtrarStatus('andamento', this)">Em andamento</button>
            <button class="filtro-btn" onclick="filtrarStatus('entregue', this)">Entregue</button>
            <button class="filtro-btn" onclick="filtrarStatus('cancelado', this)">Cancelado</button>
        </nav>

        <button class="btn-novo-pedido" onclick="abrirModalCriar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo Pedido
        </button>
    </header>

    <!-- Cartões de resumo -->
    <section class="resumo-grid" aria-label="Resumo de pedidos">

        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="rotulo">Total</span>
            <span class="numero" id="resumo-total">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#fff7ed">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <span class="rotulo">Pendentes</span>
            <span class="numero" id="resumo-pendente">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#eff6ff">
                <svg viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
            <span class="rotulo">Em andamento</span>
            <span class="numero" id="resumo-andamento">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#f0fdf4">
                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <span class="rotulo">Entregues</span>
            <span class="numero" id="resumo-entregue">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#fff1f2">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </div>
            <span class="rotulo">Cancelados</span>
            <span class="numero" id="resumo-cancelado">0</span>
        </article>

    </section>

    <!-- Tabela de pedidos -->
    <section class="tabela-container" aria-label="Lista de pedidos">

        <div class="tabela-cabecalho">
            <h2>Lista de Pedidos</h2>
            <span id="contador-pedidos">0 pedidos</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Itens</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="corpoTabela">
                <!-- Preenchido pelo pedidos.js -->
            </tbody>
        </table>

        <div class="sem-resultados" id="semResultados" style="display:none;">
            Nenhum pedido encontrado.
        </div>

    </section>

</main>

<!-- Modal de detalhes do pedido -->
<div class="modal-overlay" id="modalOverlay" role="dialog" aria-modal="true" aria-label="Detalhes do pedido">
    <article class="modal-detalhe" id="modalDetalhe">

        <header class="modal-topo">
            <div class="modal-topo-esquerda">
                <h2 id="modalTitulo">Pedido</h2>
                <span class="subtitulo" id="modalSubtitulo"></span>
            </div>
            <button class="btn-fechar" onclick="fecharModal()" title="Fechar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="modal-corpo">

            <!-- Informações gerais -->
            <section class="secao-info" aria-label="Informações do pedido">
                <div class="info-item">
                    <span class="info-rotulo">Cliente</span>
                    <span class="info-valor" id="modalCliente">—</span>
                </div>
                <div class="info-item">
                    <span class="info-rotulo">Status</span>
                    <span class="info-valor" id="modalStatus">—</span>
                </div>
                <div class="info-item">
                    <span class="info-rotulo">Data do pedido</span>
                    <span class="info-valor" id="modalData">—</span>
                </div>
                <div class="info-item">
                    <span class="info-rotulo">Quantidade de itens</span>
                    <span class="info-valor" id="modalQtdItens">—</span>
                </div>
            </section>

            <hr class="divisor">

            <!-- Produtos -->
            <section aria-label="Produtos do pedido">
                <p class="secao-titulo">Produtos</p>
                <ul class="lista-produtos" id="modalProdutos"></ul>
            </section>

            <hr class="divisor">

            <!-- Total -->
            <div class="secao-total">
                <span>Total do pedido</span>
                <strong id="modalTotal">R$ 0,00</strong>
            </div>

        </div>

        <footer class="modal-rodape">
            <button class="btn btn-secundario" onclick="fecharModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Fechar
            </button>
            <button class="btn btn-primario">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Editar Pedido
            </button>
        </footer>

    </article>
</div>

<!-- ========== MODAL NOVO PEDIDO ========== -->
<div class="modal-overlay" id="modalCriarPedido" role="dialog" aria-modal="true" aria-label="Criar novo pedido">
    <article class="modal-criar">

        <header class="modal-topo">
            <div class="modal-topo-esquerda">
                <div class="modal-topo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h2>Novo Pedido</h2>
            </div>
            <button class="btn-fechar" onclick="fecharModalCriar()" title="Fechar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="form-corpo">

            <p class="form-secao-titulo">Dados do Pedido</p>
            <div class="form-grid">

                <div class="form-grupo">
                    <label for="criar-cliente">Cliente</label>
                    <select id="criar-cliente">
                        <option value="">Selecionar cliente…</option>
                        <option>Ana Souza</option>
                        <option>Tech Solutions Ltda</option>
                        <option>Carlos Melo</option>
                        <option>Fernanda Lima</option>
                        <option>Distribuidora Norte S/A</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="criar-status">Status</label>
                    <select id="criar-status">
                        <option value="pendente">Pendente</option>
                        <option value="andamento">Em andamento</option>
                        <option value="entregue">Entregue</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="criar-data">Data do Pedido</label>
                    <input type="date" id="criar-data">
                </div>

                <div class="form-grupo">
                    <label for="criar-obs">Observação</label>
                    <input type="text" id="criar-obs" placeholder="Ex: entrega expressa…">
                </div>

            </div>

            <p class="form-secao-titulo">Produtos</p>

            <div class="lista-itens-form" id="listaItensCriar"></div>

            <button type="button" class="btn-adicionar-item" onclick="adicionarItemCriar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Adicionar produto
            </button>

            <div class="form-total">
                <span>Total estimado</span>
                <strong id="criar-total-valor">R$ 0,00</strong>
            </div>

        </div>

        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalCriar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Cancelar
            </button>
            <button class="btn btn-primario" onclick="salvarNovoPedido()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                Criar Pedido
            </button>
        </footer>

    </article>
</div>

<div class="toast" id="toast"></div>

<!-- Scripts -->
<script src="./js/main.js"></script>
<script src="./js/pedidos.js"></script>

</body>
</html>