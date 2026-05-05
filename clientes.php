<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>

    <!-- Fontes -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>

    <!-- Estilos -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/clientes.css">
</head>
<body>

<?php include("components/sidebar.php"); ?>
<?php include("components/modalSair.php"); ?>

<!-- ========== CONTEÚDO PRINCIPAL ========== -->
<main class="principal">

    <!-- Topbar -->
    <header class="topbar">
        <h1>Clientes</h1>

        <!-- Campo de busca -->
        <div class="campo-busca">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input
                type="text"
                id="campoBusca"
                placeholder="Buscar por nome, CPF/CNPJ ou e-mail..."
                oninput="filtrarTabela()"
            >
        </div>

        <!-- Botão novo cliente -->
        <button class="btn-novo-cliente" onclick="abrirModalCadastro()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo Cliente
        </button>
    </header>

    <!-- Cartões de resumo -->
    <section class="resumo-grid" aria-label="Resumo de clientes">

        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <span class="rotulo">Total</span>
            <span class="numero" id="resumo-total">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="rotulo">Pessoa Física</span>
            <span class="numero" id="resumo-pf">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#fff7ed">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                </svg>
            </div>
            <span class="rotulo">Pessoa Jurídica</span>
            <span class="numero" id="resumo-pj">0</span>
        </article>

        <article class="resumo-card">
            <div class="icone" style="background:#f0fdf4">
                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                </svg>
            </div>
            <span class="rotulo">Com compras</span>
            <span class="numero" id="resumo-compras">0</span>
        </article>

    </section>

    <!-- Tabela de clientes -->
    <section class="tabela-container" aria-label="Lista de clientes">

        <div class="tabela-cabecalho">
            <h2>Lista de Clientes</h2>
            <span id="contador-clientes">0 clientes</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Tipo</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Pedidos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="corpoTabela">
                <!-- Preenchido pelo clientes.js -->
            </tbody>
        </table>

        <div class="sem-resultados" id="semResultados" style="display:none;">
            Nenhum cliente encontrado.
        </div>

    </section>

</main>

<!-- ========== MODAL CADASTRO / VISUALIZAR ========== -->
<div class="modal-overlay" id="modalCadastro" role="dialog" aria-modal="true" aria-label="Cadastro de cliente">
    <article class="modal-cadastro">

        <header class="modal-topo">
            <div class="modal-topo-esquerda">
                <div class="modal-topo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h2 id="modalCadastroTitulo">Novo Cliente</h2>
            </div>
            <button class="btn-fechar" onclick="fecharModalCadastro()" title="Fechar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="form-corpo">

            <!-- Dados gerais -->
            <p class="form-secao-titulo">Dados Gerais</p>
            <div class="form-grid">

                <div class="form-grupo full">
                    <label for="campo-razao">Razão Social / Nome</label>
                    <input type="text" id="campo-razao" placeholder="Nome completo ou Razão Social">
                </div>

                <div class="form-grupo">
                    <label for="campo-tipo">Tipo de Pessoa</label>
                    <select id="campo-tipo">
                        <option value="">Selecionar…</option>
                        <option value="pf">Pessoa Física</option>
                        <option value="pj">Pessoa Jurídica</option>
                    </select>
                </div>

                <div class="form-grupo">
                    <label for="campo-documento">CPF / CNPJ</label>
                    <input type="text" id="campo-documento" placeholder="000.000.000-00">
                </div>

            </div>

            <!-- Contato -->
            <p class="form-secao-titulo">Contato</p>
            <div class="form-grid">

                <div class="form-grupo">
                    <label for="campo-email">E-mail</label>
                    <input type="email" id="campo-email" placeholder="email@exemplo.com">
                </div>

                <div class="form-grupo">
                    <label for="campo-telefone">Telefone</label>
                    <input type="tel" id="campo-telefone" placeholder="(00) 00000-0000">
                </div>

            </div>

            <!-- Endereço -->
            <p class="form-secao-titulo">Endereço</p>
            <div class="form-grid">

                <div class="form-grupo">
                    <label for="campo-cep">CEP</label>
                    <input type="text" id="campo-cep" placeholder="00000-000">
                </div>

                <div class="form-grupo full">
                    <label for="campo-endereco">Endereço completo</label>
                    <input type="text" id="campo-endereco" placeholder="Rua, número, bairro, cidade/UF">
                </div>

            </div>

            <!-- Observações -->
            <p class="form-secao-titulo">Observações</p>
            <div class="form-grid">

                <div class="form-grupo full">
                    <label for="campo-obs">Observação</label>
                    <textarea id="campo-obs" placeholder="Informações adicionais sobre o cliente…"></textarea>
                </div>

            </div>

        </div>

        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalCadastro()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Cancelar
            </button>
            <button class="btn btn-primario" onclick="salvarCliente()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                Salvar Cliente
            </button>
        </footer>

    </article>
</div>

<!-- ========== MODAL HISTÓRICO ========== -->
<div class="modal-overlay" id="modalHistorico" role="dialog" aria-modal="true" aria-label="Histórico de compras">
    <article class="modal-historico">

        <header class="modal-topo">
            <div class="modal-topo-esquerda">
                <div class="modal-topo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2>Histórico de Compras</h2>
            </div>
            <button class="btn-fechar" onclick="fecharModalHistorico()" title="Fechar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <!-- Info do cliente -->
        <div class="historico-cliente-info">
            <div class="historico-avatar" id="historico-avatar-texto">—</div>
            <div>
                <div class="historico-nome" id="historico-nome">—</div>
                <div class="historico-doc" id="historico-doc">—</div>
            </div>
        </div>

        <div class="historico-corpo">

            <!-- Stats rápidos -->
            <div class="historico-stats">
                <div class="stat-card">
                    <span class="stat-rotulo">Pedidos</span>
                    <span class="stat-valor" id="stat-pedidos">0</span>
                </div>
                <div class="stat-card">
                    <span class="stat-rotulo">Total gasto</span>
                    <span class="stat-valor" id="stat-total-gasto">R$ 0,00</span>
                </div>
                <div class="stat-card">
                    <span class="stat-rotulo">Última compra</span>
                    <span class="stat-valor" id="stat-ultima">—</span>
                </div>
            </div>

            <!-- Lista de pedidos -->
            <p class="historico-titulo">Pedidos realizados</p>
            <div class="lista-historico" id="listaHistorico">
                <!-- Preenchido pelo JS -->
            </div>

        </div>

        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalHistorico()">Fechar</button>
        </footer>

    </article>
</div>

<!-- ========== MODAL CONFIRMAR EXCLUSÃO ========== -->
<div class="modal-overlay" id="modalDeletar" role="dialog" aria-modal="true" aria-label="Confirmar exclusão">
    <article class="modal-confirmar">

        <div class="confirmar-corpo">
            <div class="confirmar-icone">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                </svg>
            </div>
            <h2 class="confirmar-titulo">Remover cliente?</h2>
            <p class="confirmar-texto">
                Tem certeza que deseja remover
                <strong class="confirmar-nome" id="confirmar-nome">—</strong>?
                <br>Esta ação não poderá ser desfeita.
            </p>
        </div>

        <div class="confirmar-rodape">
            <button class="btn btn-secundario" onclick="fecharModalDeletar()">Cancelar</button>
            <button class="btn btn-perigo" onclick="confirmarDeletar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                    <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                </svg>
                Sim, remover
            </button>
        </div>

    </article>
</div>

<!-- Toast de feedback -->
<div id="toast" style="
    position:fixed; bottom:30px; right:30px;
    background:#22c55e; color:white;
    padding:12px 20px; border-radius:12px;
    font-family:'Nunito',sans-serif; font-size:14px; font-weight:700;
    box-shadow:0 6px 20px rgba(0,0,0,.15);
    transform:translateY(80px); opacity:0;
    transition:transform .3s, opacity .3s;
    pointer-events:none; z-index:99999;
"></div>

<style>
    #toast.show { transform: translateY(0) !important; opacity: 1 !important; }
</style>

<!-- Scripts -->
<script src="./js/main.js"></script>
<script src="./js/clientes.js"></script>

</body>
</html>