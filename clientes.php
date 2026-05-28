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
<style>
    /* ========== VARIÁVEIS ========== */
:root {
    --sidebar-width: 230px;
    --bg: #f0f4ff;
    --card: #ffffff;
    --primary: #6c7ef8;
    --primary-hover: #5668f0;
    --text: #1e2a45;
    --muted: #7a85a3;
    --border: #dde3f0;
    --danger: #ef4444;
    --danger-bg: #fff1f2;
    --success: #22c55e;
    --success-bg: #f0fdf4;
}

/* ========== RESET ========== */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Nunito', sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* ========== MAIN ========== */
.principal {
    margin-left: var(--sidebar-width);
    padding: 36px 36px 60px;
    min-height: 100vh;
}

/* ========== TOPBAR ========== */
.topbar {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.topbar h1 {
    font-size: 20px;
    font-weight: 800;
    color: var(--text);
    margin-right: auto;
}

/* ========== BUSCA ========== */
.campo-busca { position: relative; }

.campo-busca svg {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    width: 16px; height: 16px;
    color: #94a3b8;
    pointer-events: none;
}

.campo-busca input {
    width: 240px;
    padding: 10px 12px 10px 36px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: white;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.campo-busca input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(108,126,248,.13);
}

/* ========== BOTÃO NOVO CLIENTE ========== */
.btn-novo-cliente {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 18px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(108,126,248,.30);
    transition: background .15s, transform .15s, box-shadow .15s;
    white-space: nowrap;
}

.btn-novo-cliente svg { width: 17px; height: 17px; }
.btn-novo-cliente:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(108,126,248,.40); }

/* ========== CARTÕES RESUMO ========== */
.resumo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.resumo-card {
    background: white;
    border-radius: 14px;
    padding: 18px 20px;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 2px 10px rgba(30,42,69,.05);
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.resumo-card .icone {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 4px;
}

.resumo-card .icone svg { width: 17px; height: 17px; }
.resumo-card .rotulo { font-size: 11.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
.resumo-card .numero { font-size: 26px; font-weight: 800; color: var(--text); line-height: 1; }

/* ========== TABELA ========== */
.tabela-container {
    background: white;
    border-radius: 16px;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 2px 14px rgba(30,42,69,.06);
    overflow: hidden;
}

.tabela-cabecalho {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1.5px solid #f0f3fb;
}

.tabela-cabecalho h2 { font-size: 15px; font-weight: 800; color: var(--text); }

.tabela-cabecalho span {
    font-size: 12px; font-weight: 700; color: var(--muted);
    background: #f0f3fb; padding: 4px 10px; border-radius: 20px;
}

table { width: 100%; border-collapse: collapse; }

thead th {
    padding: 12px 20px;
    text-align: left;
    font-size: 11.5px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px;
    background: #fafbff;
    border-bottom: 1.5px solid #f0f3fb;
    white-space: nowrap;
}

tbody tr { border-bottom: 1px solid #f5f7ff; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8f9ff; }

tbody td {
    padding: 14px 20px;
    font-size: 14px; font-weight: 600;
    color: var(--text); vertical-align: middle;
}

.celula-cliente { display: flex; align-items: center; gap: 10px; }

.avatar-cliente {
    width: 34px; height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6c7ef8, #4f5de4);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: white;
    flex-shrink: 0;
}

.nome-cliente { font-weight: 800; color: var(--text); font-size: 13.5px; }
.doc-cliente  { font-size: 12px; font-weight: 600; color: var(--muted); margin-top: 1px; }

.badge-tipo { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; }
.tipo-pf { background: #eef0fd; color: #6c7ef8; }
.tipo-pj { background: #fff7ed; color: #f97316; }

.acoes-tabela { display: flex; align-items: center; gap: 6px; }

.btn-acao {
    width: 30px; height: 30px;
    border-radius: 8px; border: none;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s, transform .15s;
}

.btn-acao svg { width: 15px; height: 15px; }

.btn-ver      { background: #eef0fd; color: var(--primary); }
.btn-ver:hover { background: var(--primary); color: white; transform: translateY(-1px); }

.btn-historico { background: #f0fdf4; color: var(--success); }
.btn-historico:hover { background: var(--success); color: white; transform: translateY(-1px); }

.btn-deletar  { background: var(--danger-bg); color: var(--danger); }
.btn-deletar:hover { background: var(--danger); color: white; transform: translateY(-1px); }

.sem-resultados { text-align: center; padding: 50px 20px; color: var(--muted); font-size: 14px; font-weight: 700; }

/* ========== MODAIS ========== */
.modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,23,42,.40);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center; justify-content: center;
    padding: 16px;
}

.modal-overlay.aberto { display: flex; }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

.modal-cadastro, .modal-historico {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-height: 92vh;
    overflow-y: auto;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 24px 60px rgba(15,23,42,.18);
    animation: slideUp .25s ease;
}

.modal-cadastro  { max-width: 600px; }
.modal-historico { max-width: 640px; }

.modal-topo {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1.5px solid #f0f3fb;
    position: sticky; top: 0;
    background: white; z-index: 1;
    border-radius: 20px 20px 0 0;
}

.modal-topo-esquerda { display: flex; align-items: center; gap: 10px; }

.modal-topo-icone {
    width: 36px; height: 36px; background: #eef0fd;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
}

.modal-topo-icone svg { width: 18px; height: 18px; color: var(--primary); }
.modal-topo h2 { font-size: 16px; font-weight: 800; color: var(--text); }

.btn-fechar {
    width: 32px; height: 32px; border: none;
    background: #f0f3fb; border-radius: 8px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted);
    transition: background .15s, color .15s;
}

.btn-fechar:hover { background: #fee2e2; color: var(--danger); }
.btn-fechar svg { width: 15px; height: 15px; }

.form-corpo { padding: 22px 24px; }

.form-secao-titulo {
    font-size: 11.5px; font-weight: 800; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 14px; margin-top: 20px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid #f0f3fb;
}

.form-secao-titulo:first-child { margin-top: 0; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 4px; }
.form-grupo { display: flex; flex-direction: column; gap: 5px; }
.form-grupo.full { grid-column: 1 / -1; }

.form-grupo label { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }

.form-grupo input,
.form-grupo select,
.form-grupo textarea {
    padding: 10px 12px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: #f6f8fe;
    font-family: 'Nunito', sans-serif;
    font-size: 14px; font-weight: 600; color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.form-grupo input::placeholder,
.form-grupo textarea::placeholder { color: #b0bad4; }

.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    border-color: var(--primary); background: white;
    box-shadow: 0 0 0 3px rgba(108,126,248,.13);
}

.form-grupo textarea { resize: none; min-height: 80px; }

.form-rodape {
    padding: 16px 24px 22px;
    border-top: 1.5px solid #f0f3fb;
    display: flex; justify-content: flex-end; gap: 10px;
}

/* Histórico */
.historico-cliente-info {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 24px;
    background: #f8f9ff;
    border-bottom: 1.5px solid #f0f3fb;
}

.historico-avatar {
    width: 40px; height: 40px; border-radius: 11px;
    background: linear-gradient(135deg, #6c7ef8, #4f5de4);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: white; flex-shrink: 0;
}

.historico-nome { font-size: 14px; font-weight: 800; color: var(--text); }
.historico-doc  { font-size: 12px; font-weight: 600; color: var(--muted); }
.historico-corpo { padding: 20px 24px; }

.historico-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 22px; }

.stat-card {
    background: #f8f9ff; border: 1.5px solid #eef0fb;
    border-radius: 12px; padding: 14px 16px;
    display: flex; flex-direction: column; gap: 4px;
}

.stat-card .stat-rotulo { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
.stat-card .stat-valor  { font-size: 18px; font-weight: 800; color: var(--text); }

.historico-titulo { font-size: 13px; font-weight: 800; color: var(--text); margin-bottom: 12px; }
.lista-historico  { display: flex; flex-direction: column; gap: 10px; }

/* Modal confirmar */
.modal-confirmar {
    background: white; border-radius: 20px;
    width: 100%; max-width: 420px;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 24px 60px rgba(15,23,42,.18);
    animation: slideUp .25s ease; overflow: hidden;
}

.confirmar-corpo {
    padding: 32px 28px 24px;
    display: flex; flex-direction: column;
    align-items: center; text-align: center; gap: 12px;
}

.confirmar-icone {
    width: 52px; height: 52px; background: var(--danger-bg);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
}

.confirmar-icone svg { width: 26px; height: 26px; color: var(--danger); }
.confirmar-titulo { font-size: 17px; font-weight: 800; color: var(--text); }
.confirmar-texto  { font-size: 14px; font-weight: 600; color: var(--muted); line-height: 1.6; }
.confirmar-nome   { font-weight: 800; color: var(--text); }

.confirmar-rodape { padding: 0 28px 24px; display: flex; gap: 10px; }
.confirmar-rodape .btn { flex: 1; justify-content: center; }

/* ========== BOTÕES ========== */
.btn {
    padding: 10px 20px; border-radius: 11px;
    font-family: 'Nunito', sans-serif;
    font-size: 13.5px; font-weight: 800;
    cursor: pointer; border: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: all .15s; white-space: nowrap;
}

.btn-secundario { background: #f0f3fb; color: var(--muted); border: 1.5px solid var(--border); }
.btn-secundario:hover { background: #e4e9f7; }

.btn-primario { background: var(--primary); color: white; box-shadow: 0 4px 14px rgba(108,126,248,.30); }
.btn-primario:hover { background: var(--primary-hover); transform: translateY(-1px); }

.btn-perigo { background: var(--danger); color: white; box-shadow: 0 4px 14px rgba(239,68,68,.25); }
.btn-perigo:hover { background: #dc2626; transform: translateY(-1px); }

.btn svg { width: 15px; height: 15px; }

/* ========== RESPONSIVO ========== */

/* Tablet */
@media (max-width: 1024px) {
    .principal { margin-left: 0; padding: 72px 20px 40px; }
    .resumo-grid { grid-template-columns: repeat(2, 1fr); }

    /* Esconde colunas menos importantes na tabela */
    thead th:nth-child(3),
    tbody td:nth-child(3) { display: none; } /* email */
}

/* Mobile */
@media (max-width: 768px) {
    .principal { padding: 68px 14px 40px; }

    .topbar h1 { font-size: 18px; }
    .campo-busca { flex: 1; min-width: 0; }
    .campo-busca input { width: 100%; }

    .resumo-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .resumo-card .numero { font-size: 22px; }

    /* Tabela scroll horizontal */
    .tabela-container { overflow-x: auto; }
    table { min-width: 500px; }

    thead th:nth-child(4),
    tbody td:nth-child(4) { display: none; } /* telefone */

    .form-grid { grid-template-columns: 1fr; }
    .historico-stats { grid-template-columns: 1fr 1fr; }

    .modal-cadastro,
    .modal-historico,
    .modal-confirmar { border-radius: 16px; }
}

/* Telas pequenas */
@media (max-width: 480px) {
    .resumo-grid { grid-template-columns: 1fr 1fr; }

    thead th:nth-child(5),
    tbody td:nth-child(5) { display: none; } /* pedidos */

    .btn-novo-cliente span { display: none; }

    .historico-stats { grid-template-columns: 1fr; }
}

</style>
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