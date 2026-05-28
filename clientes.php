<?php require_once "auth.php"; ?>
<?php

require_once "model/Cliente.php";

$cliente  = new Cliente();
$clientes = $cliente->listarTodos();

// Salva um novo cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {

    if ($_POST['acao'] === 'criar') {

        $dados = [
            'nome_cliente'       => $_POST['nome_cliente'],
            'tipo_cliente'       => $_POST['tipo_cliente'],
            'documento_cliente'  => $_POST['documento_cliente'],
            'email_cliente'      => $_POST['email_cliente'],
            'telefone_cliente'   => $_POST['telefone_cliente'],
            'cep_cliente'        => $_POST['cep_cliente'],
            'endereco_cliente'   => $_POST['endereco_cliente'],
            'observacao_cliente' => $_POST['observacao_cliente']
        ];

        $cliente->criar($dados);
        header("Location: clientes.php?salvo=1");
        exit;
    }

    if ($_POST['acao'] === 'deletar') {
        $cliente->deletar($_POST['id_cliente']);
        header("Location: clientes.php?deletado=1");
        exit;
    }
}

// Conta os resumos
$total       = count($clientes);
$total_pf    = count(array_filter($clientes, fn($c) => $c['tipo_cliente'] === 'pf'));
$total_pj    = count(array_filter($clientes, fn($c) => $c['tipo_cliente'] === 'pj'));
$com_compras = count(array_filter($clientes, fn($c) => $c['total_pedidos'] > 0));

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/clientes.css">
</head>
<body>

<?php include("components/sidebar.php"); ?>
<?php include("components/modalSair.php"); ?>

<main class="principal">

    <header class="topbar">
        <h1>Clientes</h1>
        <div class="campo-busca">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="campoBusca" placeholder="Buscar por nome, CPF/CNPJ ou e-mail..." oninput="filtrarTabela()">
        </div>
        <button class="btn-novo-cliente" onclick="abrirModalCadastro()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo Cliente
        </button>
    </header>

    <!-- Resumos -->
    <section class="resumo-grid" aria-label="Resumo de clientes">
        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <span class="rotulo">Total</span>
            <span class="numero"><?= $total ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="rotulo">Pessoa Física</span>
            <span class="numero"><?= $total_pf ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#fff7ed">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                </svg>
            </div>
            <span class="rotulo">Pessoa Jurídica</span>
            <span class="numero"><?= $total_pj ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#f0fdf4">
                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                </svg>
            </div>
            <span class="rotulo">Com compras</span>
            <span class="numero"><?= $com_compras ?></span>
        </article>
    </section>

    <!-- Tabela -->
    <section class="tabela-container" aria-label="Lista de clientes">
        <div class="tabela-cabecalho">
            <h2>Lista de Clientes</h2>
            <span><?= $total ?> cliente<?= $total !== 1 ? 's' : '' ?></span>
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
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#7a85a3;font-weight:700;">
                            Nenhum cliente cadastrado ainda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <?php
                            $partes   = explode(' ', $c['nome_cliente']);
                            $iniciais = strtoupper(substr($partes[0],0,1) . (isset($partes[1]) ? substr($partes[1],0,1) : ''));
                        ?>
                        <tr>
                            <td>
                                <div class="celula-cliente">
                                    <div class="avatar-cliente"><?= $iniciais ?></div>
                                    <div>
                                        <div class="nome-cliente"><?= htmlspecialchars($c['nome_cliente']) ?> </div> <p style="font-size:12px;color:#7a85a3;margin-top:2px;">#<?= $c['id_cliente'] ?></p>
                                        <div class="doc-cliente"><?= htmlspecialchars($c['documento_cliente']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-tipo tipo-<?= $c['tipo_cliente'] ?>">
                                    <?= $c['tipo_cliente'] === 'pf' ? 'Pessoa Física' : 'Pessoa Jurídica' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($c['email_cliente']) ?></td>
                            <td><?= htmlspecialchars($c['telefone_cliente'] ?? '—') ?></td>
                            <td><?= $c['total_pedidos'] ?> pedido<?= $c['total_pedidos'] != 1 ? 's' : '' ?></td>
                            <td>
                                <div class="acoes-tabela">
                                    <button class="btn-acao btn-ver"
                                        onclick="abrirModalVer(this)"
                                        title="Ver cadastro"
                                        data-cliente='<?= json_encode([
                                            "id"        => $c['id_cliente'],
                                            "nome"      => $c['nome_cliente'],
                                            "tipo"      => $c['tipo_cliente'],
                                            "documento" => $c['documento_cliente'],
                                            "email"     => $c['email_cliente'],
                                            "telefone"  => $c['telefone_cliente'] ?? '',
                                            "cep"       => $c['cep_cliente'] ?? '',
                                            "endereco"  => $c['endereco_cliente'] ?? '',
                                            "obs"       => $c['observacao_cliente'] ?? ''
                                        ], JSON_HEX_APOS) ?>'>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="btn-acao btn-historico"
                                        onclick="abrirModalHistorico(<?= $c['id_cliente'] ?>, this)"
                                        title="Histórico de compras"
                                        data-cliente='<?= json_encode([
                                            "nome"      => $c['nome_cliente'],
                                            "documento" => $c['documento_cliente']
                                        ], JSON_HEX_APOS) ?>'>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <button class="btn-acao btn-deletar"
                                        onclick="abrirModalDeletar(<?= $c['id_cliente'] ?>, '<?= htmlspecialchars($c['nome_cliente'], ENT_QUOTES) ?>')"
                                        title="Deletar cliente">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</main>


<!-- ========== MODAL CADASTRO ========== -->
<div class="modal-overlay" id="modalCadastro" role="dialog" aria-modal="true">
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
            <button class="btn-fechar" onclick="fecharModalCadastro()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="form-corpo">

            <form id="formCadastro" method="POST" action="clientes.php">
                <input type="hidden" name="acao" value="criar">
                <!-- Campo oculto que recebe o endereço montado pelo JS -->
                <input type="hidden" name="endereco_cliente" id="input-endereco-completo">

                <!-- Dados Gerais -->
                <p class="form-secao-titulo">Dados Gerais</p>
                <div class="form-grid">

                    <div class="form-grupo full">
                        <label>Razão Social / Nome</label>
                        <input type="text" name="nome_cliente" id="campo-razao"
                            placeholder="Nome completo ou Razão Social" required>
                    </div>

                    <div class="form-grupo">
                        <label>Tipo de Pessoa</label>
                        <select name="tipo_cliente" id="campo-tipo" required>
                            <option value="">Selecionar…</option>
                            <option value="pf">Pessoa Física</option>
                            <option value="pj">Pessoa Jurídica</option>
                        </select>
                    </div>

                    <div class="form-grupo">
                        <label>CPF / CNPJ</label>
                        <input type="text" name="documento_cliente" id="campo-documento"
                            placeholder="000.000.000-00">
                    </div>

                </div>

                <!-- Contato -->
                <p class="form-secao-titulo">Contato</p>
                <div class="form-grid">

                    <div class="form-grupo">
                        <label>E-mail</label>
                        <input type="email" name="email_cliente" id="campo-email"
                            placeholder="email@exemplo.com" required>
                    </div>

                    <div class="form-grupo">
                        <label>Telefone</label>
                        <!-- maxlength=15 comporta (00) 00000-0000 -->
                        <input type="tel" name="telefone_cliente" id="campo-telefone"
                            placeholder="(00) 00000-0000" maxlength="15">
                    </div>

                </div>

                <!-- Endereço -->
                <p class="form-secao-titulo">Endereço</p>
                <div class="form-grid">

                    <div class="form-grupo">
                        <label>CEP</label>
                        <input type="text" name="cep_cliente" id="campo-cep"
                            placeholder="00000-000" maxlength="9">
                    </div>

                    <div class="form-grupo full">
                        <label>Logradouro</label>
                        <input type="text" id="campo-endereco"
                            placeholder="Preenchido automaticamente pelo CEP" readonly>
                    </div>

                    <div class="form-grupo">
                        <label>Complemento</label>
                        <input type="text" name="complemento_cliente" id="campo-complemento"
                            placeholder="Apto, sala, bloco…">
                    </div>

                    <div class="form-grupo">
                        <label>Bairro</label>
                        <input type="text" id="campo-bairro"
                            placeholder="Preenchido pelo CEP" readonly>
                    </div>

                    <div class="form-grupo">
                        <label>Cidade</label>
                        <input type="text" id="campo-cidade"
                            placeholder="Preenchido pelo CEP" readonly>
                    </div>

                    <div class="form-grupo">
                        <label>Estado</label>
                        <input type="text" id="campo-estado"
                            placeholder="UF" readonly maxlength="2">
                    </div>

                </div>

                <!-- Observações -->
                <p class="form-secao-titulo">Observações</p>
                <div class="form-grid">
                    <div class="form-grupo full">
                        <label>Observação</label>
                        <textarea name="observacao_cliente" id="campo-obs"
                            placeholder="Informações adicionais sobre o cliente…"></textarea>
                    </div>
                </div>

            </form>

        </div>

        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalCadastro()">Cancelar</button>
            <button class="btn btn-primario" onclick="document.getElementById('formCadastro').dispatchEvent(new Event('submit'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                Salvar Cliente
            </button>
        </footer>

    </article>
</div>

<!-- MODAL HISTÓRICO -->
<div class="modal-overlay" id="modalHistorico" role="dialog" aria-modal="true">
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
            <button class="btn-fechar" onclick="fecharModalHistorico()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>
        <div class="historico-cliente-info">
            <div class="historico-avatar" id="historico-avatar-texto">—</div>
            <div>
                <div class="historico-nome" id="historico-nome">—</div>
                <div class="historico-doc" id="historico-doc">—</div>
            </div>
        </div>
        <div class="historico-corpo">
            <div class="historico-stats">
                <div class="stat-card"><span class="stat-rotulo">Pedidos</span><span class="stat-valor" id="stat-pedidos">—</span></div>
                <div class="stat-card"><span class="stat-rotulo">Total gasto</span><span class="stat-valor" id="stat-total-gasto">—</span></div>
                <div class="stat-card"><span class="stat-rotulo">Última compra</span><span class="stat-valor" id="stat-ultima">—</span></div>
            </div>
            <p class="historico-titulo">Pedidos realizados</p>
            <div class="lista-historico" id="listaHistorico"></div>
        </div>
        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalHistorico()">Fechar</button>
        </footer>
    </article>
</div>


<!-- MODAL DELETAR -->
<div class="modal-overlay" id="modalDeletar" role="dialog" aria-modal="true">
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
                Tem certeza que deseja remover <strong id="confirmar-nome">—</strong>?<br>
                Esta ação não poderá ser desfeita.
            </p>
        </div>
        <div class="confirmar-rodape">
            <form id="formDeletar" method="POST" action="clientes.php">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" name="id_cliente" id="inputIdDeletar">
                <div style="display:flex;gap:10px;">
                    <button type="button" class="btn btn-secundario" onclick="fecharModalDeletar()">Cancelar</button>
                    <button type="submit" class="btn btn-perigo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6"/>
                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                        </svg>
                        Sim, remover
                    </button>
                </div>
            </form>
        </div>
    </article>
</div>


<!-- Toast -->
<div id="toast" style="position:fixed;bottom:30px;right:30px;background:#22c55e;color:white;padding:12px 20px;border-radius:12px;font-family:'Nunito',sans-serif;font-size:14px;font-weight:700;box-shadow:0 6px 20px rgba(0,0,0,.15);transform:translateY(80px);opacity:0;transition:transform .3s,opacity .3s;pointer-events:none;z-index:99999;"></div>
<style>#toast.show{transform:translateY(0)!important;opacity:1!important;}</style>

<script src="./js/main.js"></script>
<script src="./js/clientes_form.js"></script>
<script>

    <?php if (isset($_GET['salvo'])): ?>
        mostrarToast('Cliente cadastrado com sucesso!');
    <?php elseif (isset($_GET['deletado'])): ?>
        mostrarToast('Cliente removido com sucesso!', '#ef4444');
    <?php endif; ?>

    function mostrarToast(msg, cor = '#22c55e') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.style.background = cor;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    function abrirModalCadastro() {
        document.getElementById('modalCadastroTitulo').textContent = 'Novo Cliente';
        ['campo-razao','campo-tipo','campo-documento','campo-email','campo-telefone','campo-cep','campo-endereco','campo-obs']
            .forEach(id => document.getElementById(id).value = '');
        document.getElementById('modalCadastro').classList.add('aberto');
        document.body.style.overflow = 'hidden';
    }

    function fecharModalCadastro() {
        document.getElementById('modalCadastro').classList.remove('aberto');
        document.body.style.overflow = '';
    }

    function abrirModalVer(botao) {
        const dados = JSON.parse(botao.dataset.cliente);
        document.getElementById('modalCadastroTitulo').textContent = 'Dados do Cliente';
        document.getElementById('campo-razao').value     = dados.nome;
        document.getElementById('campo-tipo').value      = dados.tipo;
        document.getElementById('campo-documento').value = dados.documento;
        document.getElementById('campo-email').value     = dados.email;
        document.getElementById('campo-telefone').value  = dados.telefone;
        document.getElementById('campo-cep').value       = dados.cep;
        document.getElementById('campo-endereco').value  = dados.endereco;
        document.getElementById('campo-obs').value       = dados.obs;
        document.getElementById('modalCadastro').classList.add('aberto');
        document.body.style.overflow = 'hidden';
    }


    function formatarData(dataStr) {
    if (!dataStr || dataStr === '0000-00-00') return '—';
    const [ano, mes, dia] = dataStr.split('-');
    return `${dia}/${mes}/${ano}`;
}

    function abrirModalHistorico(id, botao) {
        const dados    = JSON.parse(botao.dataset.cliente);
        const iniciais = dados.nome.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase();

        document.getElementById('historico-avatar-texto').textContent = iniciais;
        document.getElementById('historico-nome').textContent         = dados.nome;
        document.getElementById('historico-doc').textContent          = dados.documento;
        document.getElementById('listaHistorico').innerHTML           = '<p style="color:#7a85a3;font-weight:700;text-align:center;padding:20px;">Carregando…</p>';
        document.getElementById('modalHistorico').classList.add('aberto');
        document.body.style.overflow = 'hidden';

        fetch(`model/buscar_historico.php?id=${id}`)
            .then(r => r.json())
            .then(pedidos => {
                const lista = document.getElementById('listaHistorico');

                if (pedidos.length === 0) {
                    lista.innerHTML = '<p style="color:#7a85a3;font-weight:700;text-align:center;padding:20px;">Nenhuma compra registrada.</p>';
                    document.getElementById('stat-pedidos').textContent     = '0';
                    document.getElementById('stat-total-gasto').textContent = 'R$ 0,00';
                    document.getElementById('stat-ultima').textContent      = '—';
                    return;
                }

                const total = pedidos.reduce((s, p) => s + parseFloat(p.total_pedido), 0);
                document.getElementById('stat-pedidos').textContent     = pedidos.length;
                document.getElementById('stat-total-gasto').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
                document.getElementById('stat-ultima').textContent = formatarData(pedidos[0].data_pedido);

                const cor = { 'Pendente':'#f97316','Em andamento':'#3b82f6','Entregue':'#22c55e','Cancelado':'#ef4444' };

                lista.innerHTML = pedidos.map(p => `
                    <article style="background:#f8f9ff;border:1.5px solid #eef0fb;border-radius:12px;padding:14px 16px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <span style="font-weight:800;color:#6c7ef8;">Pedido #${String(p.id_pedido).padStart(3,'0')}</span>
                            <span style="font-size:12px;color:#7a85a3;">${formatarData(p.data_pedido)}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="background:${cor[p.nome_status]}18;color:${cor[p.nome_status]};padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;">${p.nome_status}</span>
                            <strong>R$ ${parseFloat(p.total_pedido).toFixed(2).replace('.', ',')}</strong>
                        </div>
                    </article>
                `).join('');
            });
    }

    function fecharModalHistorico() {
        document.getElementById('modalHistorico').classList.remove('aberto');
        document.body.style.overflow = '';
    }

    function abrirModalDeletar(id, nome) {
        document.getElementById('confirmar-nome').textContent = nome;
        document.getElementById('inputIdDeletar').value       = id;
        document.getElementById('modalDeletar').classList.add('aberto');
        document.body.style.overflow = 'hidden';
    }

    function fecharModalDeletar() {
        document.getElementById('modalDeletar').classList.remove('aberto');
        document.body.style.overflow = '';
    }

    ['modalCadastro','modalHistorico','modalDeletar'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('aberto');
                document.body.style.overflow = '';
            }
        });
    });

    function filtrarTabela() {
        const busca = document.getElementById('campoBusca').value.toLowerCase();
        document.querySelectorAll('#corpoTabela tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(busca) ? '' : 'none';
        });
    }

</script>

</body>
</html>