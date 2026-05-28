<?php require_once "auth.php"; ?>
<?php

require_once "model/Pedido.php";

$pedido   = new Pedido();
$pedidos  = $pedido->listarTodos();
$clientes = $pedido->listarClientes();
$produtos = $pedido->listarProdutos();
$status   = $pedido->listarStatus();


// Criar novo pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {

    if ($_POST['acao'] === 'criar') {

        $itens = [];
        foreach ($_POST['id_produto'] as $i => $id_produto) {
            if (empty($id_produto)) continue;
            $itens[] = [
                'id_produto'    => intval($id_produto),
                'quantidade'    => intval($_POST['quantidade'][$i]),
                'preco_unitario'=> floatval($_POST['preco_unitario'][$i])
            ];
        }

        $dados = [
            'id_cliente'        => intval($_POST['id_cliente']),
            'id_status'         => intval($_POST['id_status']),
            'data_pedido'       => $_POST['data_pedido'],
            'total_pedido'      => floatval($_POST['total_pedido']),
            'observacao_pedido' => $_POST['observacao_pedido'] ?? ''
        ];

        $pedido->criar($dados, $itens);
        header("Location: pedidos.php?salvo=1");
        exit;
    }

    if ($_POST['acao'] === 'deletar') {
        $pedido->deletar($_POST['id_pedido']);
        header("Location: pedidos.php?deletado=1");
        exit;
    }

    if ($_POST['acao'] === 'status') {
        $pedido->atualizarStatus($_POST['id_pedido'], $_POST['id_status']);
        header("Location: pedidos.php?atualizado=1");
        exit;
    }
}

// Contadores de resumo
$total     = count($pedidos);
$pendentes = count(array_filter($pedidos, fn($p) => $p['nome_status'] === 'Pendente'));
$andamento = count(array_filter($pedidos, fn($p) => $p['nome_status'] === 'Em andamento'));
$entregues = count(array_filter($pedidos, fn($p) => $p['nome_status'] === 'Entregue'));
$cancelados= count(array_filter($pedidos, fn($p) => $p['nome_status'] === 'Cancelado'));

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/pedidos.css">
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

    --status-pendente-bg:  #fff7ed;
    --status-pendente-cor: #f97316;
    --status-andamento-bg: #eff6ff;
    --status-andamento-cor:#3b82f6;
    --status-entregue-bg:  #f0fdf4;
    --status-entregue-cor: #22c55e;
    --status-cancelado-bg: #fff1f2;
    --status-cancelado-cor:#ef4444;
}

/* ========== RESET ========== */
* { margin: 0; padding: 0; box-sizing: border-box; }

body { font-family: 'Nunito', sans-serif; background: var(--bg); color: var(--text); }

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

.topbar h1 { font-size: 20px; font-weight: 800; color: var(--text); margin-right: auto; }

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
    font-size: 14px; font-weight: 600; color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.campo-busca input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(108,126,248,.13);
}

/* ========== FILTROS DE STATUS ========== */
.filtro-status { display: flex; gap: 6px; flex-wrap: wrap; }

.filtro-btn {
    padding: 7px 14px;
    border-radius: 9px;
    border: 1.5px solid var(--border);
    background: white;
    font-family: 'Nunito', sans-serif;
    font-size: 12.5px; font-weight: 700; color: var(--muted);
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
}

.filtro-btn:hover { border-color: var(--primary); color: var(--primary); }

.filtro-btn.ativo {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    box-shadow: 0 3px 10px rgba(108,126,248,.30);
}

/* ========== BOTÃO NOVO PEDIDO ========== */
.btn-novo-pedido {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 18px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 10px;
    font-family: 'Nunito', sans-serif;
    font-size: 14px; font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(108,126,248,.30);
    transition: background .15s, transform .15s, box-shadow .15s;
    white-space: nowrap;
}

.btn-novo-pedido svg { width: 17px; height: 17px; }
.btn-novo-pedido:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(108,126,248,.40); }

/* ========== RESUMO CARDS ========== */
.resumo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.resumo-card {
    background: white;
    border-radius: 14px;
    padding: 18px 20px;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 2px 10px rgba(30,42,69,.05);
    display: flex; flex-direction: column; gap: 6px;
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
    display: flex; align-items: center; justify-content: space-between;
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
    padding: 12px 22px;
    text-align: left;
    font-size: 11.5px; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px;
    background: #fafbff;
    border-bottom: 1.5px solid #f0f3fb;
    white-space: nowrap;
}

tbody tr { border-bottom: 1px solid #f5f7ff; cursor: pointer; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8f9ff; }

tbody td { padding: 14px 22px; font-size: 14px; font-weight: 600; color: var(--text); vertical-align: middle; }

.id-pedido { font-weight: 800; color: var(--primary); font-size: 13px; }

.celula-cliente { display: flex; align-items: center; gap: 10px; }

.avatar-cliente {
    width: 32px; height: 32px;
    border-radius: 9px;
    background: linear-gradient(135deg, #6c7ef8, #4f5de4);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: white;
    flex-shrink: 0;
}

.nome-cliente { font-weight: 700; color: var(--text); font-size: 13.5px; }
.doc-cliente  { font-size: 12px; font-weight: 600; color: var(--muted); margin-top: 1px; }

/* ========== BADGE E SELECT STATUS ========== */
.badge-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 11px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
}

.badge-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }

.status-pendente  { background: var(--status-pendente-bg);  color: var(--status-pendente-cor); }
.status-pendente::before  { background: var(--status-pendente-cor); }
.status-andamento { background: var(--status-andamento-bg); color: var(--status-andamento-cor); }
.status-andamento::before { background: var(--status-andamento-cor); }
.status-entregue  { background: var(--status-entregue-bg);  color: var(--status-entregue-cor); }
.status-entregue::before  { background: var(--status-entregue-cor); }
.status-cancelado { background: var(--status-cancelado-bg); color: var(--status-cancelado-cor); }
.status-cancelado::before { background: var(--status-cancelado-cor); }

.select-status {
    padding: 5px 10px;
    border-radius: 20px; border: none;
    font-family: 'Nunito', sans-serif;
    font-size: 12px; font-weight: 700;
    cursor: pointer; outline: none;
    transition: all .2s;
    appearance: none; -webkit-appearance: none;
    text-align: center;
}

.select-status.status-pendente  { background: var(--status-pendente-bg);  color: var(--status-pendente-cor); }
.select-status.status-andamento { background: var(--status-andamento-bg); color: var(--status-andamento-cor); }
.select-status.status-entregue  { background: var(--status-entregue-bg);  color: var(--status-entregue-cor); }
.select-status.status-cancelado { background: var(--status-cancelado-bg); color: var(--status-cancelado-cor); }

/* Seta */
.icone-ver {
    width: 28px; height: 28px;
    border-radius: 8px; background: #f0f3fb;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted); border: none; cursor: pointer;
    transition: background .15s, color .15s;
}

tbody tr:hover .icone-ver { background: var(--primary); color: white; }
.icone-ver svg { width: 14px; height: 14px; }

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

.modal-detalhe, .modal-criar {
    background: white; border-radius: 20px;
    width: 100%; max-height: 92vh; overflow-y: auto;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 24px 60px rgba(15,23,42,.18);
    animation: slideUp .25s ease;
}

.modal-detalhe { max-width: 600px; }
.modal-criar   { max-width: 580px; }

.modal-topo {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1.5px solid #f0f3fb;
    position: sticky; top: 0; background: white; z-index: 1;
    border-radius: 20px 20px 0 0;
}

.modal-topo-esquerda { display: flex; align-items: center; gap: 10px; }

.modal-topo-icone {
    width: 36px; height: 36px; background: #eef0fd;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
}

.modal-topo-icone svg { width: 18px; height: 18px; color: var(--primary); }

.modal-topo h2 { font-size: 17px; font-weight: 800; color: var(--text); }
.modal-topo .subtitulo { font-size: 12.5px; font-weight: 600; color: var(--muted); }

.btn-fechar {
    width: 32px; height: 32px; border: none;
    background: #f0f3fb; border-radius: 8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted); transition: background .15s, color .15s;
}

.btn-fechar:hover { background: #fee2e2; color: #ef4444; }
.btn-fechar svg { width: 15px; height: 15px; }

.modal-corpo { padding: 22px 24px; display: flex; flex-direction: column; gap: 22px; }

.secao-info { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.info-item  { display: flex; flex-direction: column; gap: 3px; }
.info-item .info-rotulo { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }
.info-item .info-valor  { font-size: 14px; font-weight: 700; color: var(--text); }

.divisor { border: none; border-top: 1.5px solid #f0f3fb; }
.secao-titulo { font-size: 13px; font-weight: 800; color: var(--text); margin-bottom: 10px; }
.lista-produtos { display: flex; flex-direction: column; gap: 10px; }

.item-produto { display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8f9ff; border-radius: 12px; border: 1.5px solid #eef0fb; }
.item-produto img { width: 52px; height: 52px; border-radius: 9px; object-fit: cover; border: 1.5px solid #e8edf8; flex-shrink: 0; }
.item-produto-info { flex: 1; }
.item-produto-info .nome { font-size: 13.5px; font-weight: 800; color: var(--text); }
.item-produto-info .qtd  { font-size: 12px; font-weight: 600; color: var(--muted); margin-top: 2px; }
.item-produto .preco { font-size: 14px; font-weight: 800; color: var(--primary); }

.secao-total { background: #f8f9ff; border-radius: 12px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center; border: 1.5px solid #eef0fb; }
.secao-total span   { font-size: 14px; font-weight: 700; color: var(--muted); }
.secao-total strong { font-size: 18px; font-weight: 800; color: var(--text); }

.modal-rodape {
    padding: 16px 24px 22px;
    border-top: 1.5px solid #f0f3fb;
    display: flex; justify-content: flex-end;
    gap: 10px; flex-wrap: wrap;
}

/* Form criar pedido */
.form-corpo { padding: 22px 24px; }

.form-secao-titulo {
    font-size: 11.5px; font-weight: 800; color: var(--muted);
    text-transform: uppercase; letter-spacing: .5px;
    padding-bottom: 8px; margin-bottom: 14px; margin-top: 20px;
    border-bottom: 1.5px solid #f0f3fb;
}

.form-secao-titulo:first-child { margin-top: 0; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grupo { display: flex; flex-direction: column; gap: 5px; }
.form-grupo.full { grid-column: 1 / -1; }

.form-grupo label { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .4px; }

.form-grupo input,
.form-grupo select {
    padding: 10px 12px; border-radius: 10px;
    border: 1.5px solid var(--border); background: #f6f8fe;
    font-family: 'Nunito', sans-serif;
    font-size: 14px; font-weight: 600; color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.form-grupo input::placeholder { color: #b0bad4; }
.form-grupo input:focus,
.form-grupo select:focus { border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(108,126,248,.13); }

.lista-itens-form { display: flex; flex-direction: column; gap: 8px; margin-bottom: 10px; }

.item-pedido-form {
    display: grid;
    grid-template-columns: 1fr 80px 110px auto;
    gap: 8px; align-items: center;
    background: #f8f9ff; border: 1.5px solid #eef0fb;
    border-radius: 11px; padding: 10px 12px;
}

.item-pedido-form input {
    padding: 8px 10px; border-radius: 8px;
    border: 1.5px solid var(--border); background: white;
    font-family: 'Nunito', sans-serif;
    font-size: 13px; font-weight: 600; color: var(--text);
    outline: none; transition: border-color .2s; width: 100%;
}

.item-pedido-form input:focus { border-color: var(--primary); }

.btn-remover-item {
    width: 28px; height: 28px; border: none;
    background: #fee2e2; border-radius: 7px;
    color: #ef4444; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s; flex-shrink: 0;
}

.btn-remover-item:hover { background: #ef4444; color: white; }
.btn-remover-item svg { width: 14px; height: 14px; }

.btn-adicionar-item {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; background: #eef0fd; color: var(--primary);
    border: 1.5px dashed #b8c0f8; border-radius: 10px;
    font-family: 'Nunito', sans-serif; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: background .15s, border-color .15s;
}

.btn-adicionar-item:hover { background: #e0e4fd; border-color: var(--primary); }
.btn-adicionar-item svg { width: 15px; height: 15px; }

.form-total {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 14px; padding: 13px 16px;
    background: #f8f9ff; border: 1.5px solid #eef0fb; border-radius: 11px;
}

.form-total span   { font-size: 13px; font-weight: 700; color: var(--muted); }
.form-total strong { font-size: 17px; font-weight: 800; color: var(--text); }

.form-rodape {
    padding: 16px 24px 22px;
    border-top: 1.5px solid #f0f3fb;
    display: flex; justify-content: flex-end; gap: 10px;
}

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

.btn-sucesso { background: #22c55e; color: white; box-shadow: 0 4px 14px rgba(34,197,94,.30); }
.btn-sucesso:hover { background: #16a34a; transform: translateY(-1px); }

.btn-perigo { background: #ef4444; color: white; box-shadow: 0 4px 14px rgba(239,68,68,.30); }
.btn-perigo:hover { background: #dc2626; transform: translateY(-1px); }

.btn svg { width: 15px; height: 15px; }

/* ========== TOAST ========== */
.toast {
    position: fixed; bottom: 30px; right: 30px;
    background: #22c55e; color: white;
    padding: 12px 20px; border-radius: 12px;
    font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 700;
    box-shadow: 0 6px 20px rgba(0,0,0,.15);
    transform: translateY(80px); opacity: 0;
    transition: transform .3s, opacity .3s;
    pointer-events: none; z-index: 99999;
}

.toast.show { transform: translateY(0); opacity: 1; }

/* ========== RESPONSIVO ========== */

/* Tablet */
@media (max-width: 1024px) {
    .principal { margin-left: 0; padding: 72px 20px 40px; }
    .resumo-grid { grid-template-columns: repeat(3, 1fr); }
    .filtro-status { display: none; } /* filtros viram select no mobile */
}

/* Mobile */
@media (max-width: 768px) {
    .principal { padding: 68px 14px 40px; }

    .topbar h1 { font-size: 18px; }
    .campo-busca { flex: 1; min-width: 0; }
    .campo-busca input { width: 100%; }

    .btn-novo-pedido span { display: none; }

    .resumo-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .resumo-card .numero { font-size: 22px; }

    .tabela-container { overflow-x: auto; }
    table { min-width: 560px; }

    /* Esconde colunas menos importantes */
    thead th:nth-child(4),
    tbody td:nth-child(4) { display: none; } /* itens */

    .modal-detalhe,
    .modal-criar { border-radius: 16px; }

    .secao-info { grid-template-columns: 1fr; }

    .item-pedido-form { grid-template-columns: 1fr auto auto auto; }

    .form-grid { grid-template-columns: 1fr; }
}

/* Telas pequenas */
@media (max-width: 480px) {
    .resumo-grid { grid-template-columns: repeat(2, 1fr); }

    thead th:nth-child(3),
    tbody td:nth-child(3) { display: none; } /* data */

    .modal-rodape { flex-direction: column; }
    .modal-rodape .btn { width: 100%; justify-content: center; }
}
</style>
<body>

<?php include("components/sidebar.php"); ?>
<?php include("components/modalSair.php"); ?>

<main class="principal">

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
            <button class="filtro-btn ativo" onclick="filtrarStatus('Todos', this)">Todos</button>
            <button class="filtro-btn" onclick="filtrarStatus('Pendente', this)">Pendente</button>
            <button class="filtro-btn" onclick="filtrarStatus('Em andamento', this)">Em andamento</button>
            <button class="filtro-btn" onclick="filtrarStatus('Entregue', this)">Entregue</button>
            <button class="filtro-btn" onclick="filtrarStatus('Cancelado', this)">Cancelado</button>
        </nav>

        <button class="btn-novo-pedido" onclick="abrirModalCriar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Novo Pedido
        </button>
    </header>

    <!-- Resumos -->
    <section class="resumo-grid" aria-label="Resumo de pedidos">
        <article class="resumo-card">
            <div class="icone" style="background:#eef0fd">
                <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="rotulo">Total</span>
            <span class="numero"><?= $total ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#fff7ed">
                <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <span class="rotulo">Pendentes</span>
            <span class="numero"><?= $pendentes ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#eff6ff">
                <svg viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
            <span class="rotulo">Em andamento</span>
            <span class="numero"><?= $andamento ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#f0fdf4">
                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <span class="rotulo">Entregues</span>
            <span class="numero"><?= $entregues ?></span>
        </article>
        <article class="resumo-card">
            <div class="icone" style="background:#fff1f2">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </div>
            <span class="rotulo">Cancelados</span>
            <span class="numero"><?= $cancelados ?></span>
        </article>
    </section>

    <!-- Tabela -->
    <section class="tabela-container" aria-label="Lista de pedidos">

        <div class="tabela-cabecalho">
            <h2>Lista de Pedidos</h2>
            <span><?= $total ?> pedido<?= $total !== 1 ? 's' : '' ?></span>
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

                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#7a85a3;font-weight:700;">
                            Nenhum pedido cadastrado ainda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $p): ?>
                        <?php
                            $classeStatus = [
                                'Pendente'     => 'pendente',
                                'Em andamento' => 'andamento',
                                'Entregue'     => 'entregue',
                                'Cancelado'    => 'cancelado'
                            ][$p['nome_status']] ?? 'pendente';
                        ?>
                        <tr data-status="<?= $p['nome_status'] ?>">
                            <td><span class="id-pedido">#<?= str_pad($p['id_pedido'], 3, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <div class="celula-cliente">
                                    <?php
                                        $partes   = explode(' ', $p['nome_cliente']);
                                        $iniciais = strtoupper(substr($partes[0],0,1) . (isset($partes[1]) ? substr($partes[1],0,1) : ''));
                                    ?>
                                    <div class="avatar-cliente"><?= $iniciais ?></div>
                                        <div class="nome-cliente"><?= htmlspecialchars($p['nome_cliente']) ?></div>
    <div class="doc-cliente">#<?= $p['id_cliente'] ?></div>
                                </div>
                            </td>
                            <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                            <td><?= $p['total_itens'] ?> <?= $p['total_itens'] == 1 ? 'item' : 'itens' ?></td>
                            <td><strong>R$ <?= number_format($p['total_pedido'], 2, ',', '.') ?></strong></td>
 <td>
    <select class="select-status status-<?= $classeStatus ?>"
        onchange="alterarStatus(this, <?= $p['id_pedido'] ?>)">
        <?php if ($p['nome_status'] === 'Pendente'): ?>
            <option value="1" selected>Pendente</option>
            <option value="2">Em andamento</option>
            <option value="4">Cancelado</option>
        <?php elseif ($p['nome_status'] === 'Em andamento'): ?>
            <option value="2" selected>Em andamento</option>
            <option value="4">Cancelado</option>
        <?php elseif ($p['nome_status'] === 'Entregue'): ?>
            <option value="3" selected>Entregue</option>
        <?php elseif ($p['nome_status'] === 'Cancelado'): ?>
            <option value="4" selected>Cancelado</option>
        <?php endif; ?>
    </select>
</td>                    
                                <td>
                                <button class="icone-ver" onclick="abrirModal(<?= $p['id_pedido'] ?>)" title="Ver detalhes">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 18l6-6-6-6"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>

    </section>

</main>


<!-- MODAL DETALHES DO PEDIDO -->
<div class="modal-overlay" id="modalOverlay" role="dialog" aria-modal="true">
    <article class="modal-detalhe" id="modalDetalhe">

        <header class="modal-topo">
            <div class="modal-topo-esquerda">
                <h2 id="modalTitulo">Pedido</h2>
                <span class="subtitulo" id="modalSubtitulo"></span>
            </div>
            <button class="btn-fechar" onclick="fecharModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="modal-corpo">
            <section class="secao-info">
                <div class="info-item"><span class="info-rotulo">Cliente</span><span class="info-valor" id="modalCliente">—</span></div>
                <div class="info-item"><span class="info-rotulo">Status</span><span class="info-valor" id="modalStatus">—</span></div>
                <div class="info-item"><span class="info-rotulo">Data do pedido</span><span class="info-valor" id="modalData">—</span></div>
                <div class="info-item"><span class="info-rotulo">Quantidade de itens</span><span class="info-valor" id="modalQtdItens">—</span></div>
            </section>
            <hr class="divisor">
            <section>
                <p class="secao-titulo">Produtos</p>
                <ul class="lista-produtos" id="modalProdutos"></ul>
            </section>
            <hr class="divisor">
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

        <button class="btn btn-sucesso" onclick="darEntrada()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            Entregue
        </button>

        <form id="formDeletarPedido" method="POST" action="pedidos.php" style="display:inline">
            <input type="hidden" name="acao" value="deletar">
            <input type="hidden" name="id_pedido" id="inputIdDeletarPedido">
            <button type="submit" class="btn btn-perigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                </svg>
                Excluir Pedido
            </button>
        </form>

    </footer>

    </article>
</div>


<!-- MODAL NOVO PEDIDO -->
<div class="modal-overlay" id="modalCriarPedido" role="dialog" aria-modal="true">
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
            <button class="btn-fechar" onclick="fecharModalCriar()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <div class="form-corpo">
            <form id="formCriarPedido" method="POST" action="pedidos.php">
                <input type="hidden" name="acao" value="criar">
                <input type="hidden" name="total_pedido" id="input-total-pedido">
                <input type="hidden" name="id_status" value="1">
                <p class="form-secao-titulo">Dados do Pedido</p>
                <div class="form-grid">

<div class="form-grupo">
    <label>Cliente</label>
    <select name="id_cliente" id="criar-cliente" required>
        <option value="">Selecionar cliente…</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['id_cliente'] ?>">
                #<?= $c['id_cliente'] ?> | <?= htmlspecialchars($c['nome_cliente']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

                    <div class="form-grupo">
                        <label>Data do Pedido</label>
                        <input type="date" name="data_pedido" id="criar-data" required>
                    </div>

                    <div class="form-grupo">
                        <label>Observação</label>
                        <input type="text" name="observacao_pedido" id="criar-obs" placeholder="Ex: entrega expressa…">
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

            </form>
        </div>

        <footer class="form-rodape">
            <button class="btn btn-secundario" onclick="fecharModalCriar()">Cancelar</button>
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

<script src="./js/main.js"></script>
<script>

    // Produtos disponíveis vindos do PHP para o select dinâmico
    const produtosDisponiveis = <?= json_encode($produtos) ?>;

    <?php if (isset($_GET['salvo'])): ?>
        mostrarToast('Pedido criado com sucesso!');
    <?php elseif (isset($_GET['deletado'])): ?>
        mostrarToast('Pedido removido com sucesso!', '#ef4444');
    <?php elseif (isset($_GET['atualizado'])): ?>
        mostrarToast('Status atualizado com sucesso!');
    <?php endif; ?>

    function mostrarToast(msg, cor = '#22c55e') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.style.background = cor;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    // ── FILTRO DE STATUS
    function filtrarStatus(status, botao) {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('ativo'));
        botao.classList.add('ativo');

        document.querySelectorAll('#corpoTabela tr').forEach(tr => {
            const s = tr.dataset.status;
            tr.style.display = (status === 'Todos' || s === status) ? '' : 'none';
        });
    }

    // ── BUSCA
    function filtrarTabela() {
        const busca = document.getElementById('campoBusca').value.toLowerCase();
        document.querySelectorAll('#corpoTabela tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(busca) ? '' : 'none';
        });
    }

function formatarData(dataStr) {
    if (!dataStr || dataStr === '0000-00-00') return '—';
    const [ano, mes, dia] = dataStr.split('-');
    return `${dia}/${mes}/${ano}`;
}

function abrirModal(id) {
    document.getElementById('inputIdDeletarPedido').value = id;
    document.getElementById('modalTitulo').textContent    = `Pedido #${String(id).padStart(3,'0')}`;
    document.getElementById('modalProdutos').innerHTML    = '<li style="color:#7a85a3;font-weight:700;padding:10px;">Carregando…</li>';
    document.getElementById('modalOverlay').classList.add('aberto');
    document.body.style.overflow = 'hidden';

    fetch(`model/buscar_pedido.php?id=${id}`)
        .then(r => r.json())
        .then(({ pedido, itens }) => {

            const cor = {
                'Pendente'    : '#f97316',
                'Em andamento': '#3b82f6',
                'Entregue'    : '#22c55e',
                'Cancelado'   : '#ef4444'
            };

            document.getElementById('modalSubtitulo').textContent = `Realizado em ${formatarData(pedido.data_pedido)}`;
            document.getElementById('modalCliente').textContent   = pedido.nome_cliente;
            document.getElementById('modalData').textContent      = formatarData(pedido.data_pedido);
            document.getElementById('modalQtdItens').textContent  = `${itens.length} ${itens.length === 1 ? 'item' : 'itens'}`;
            document.getElementById('modalTotal').textContent     = 'R$ ' + parseFloat(pedido.total_pedido).toFixed(2).replace('.', ',');

            document.getElementById('modalStatus').innerHTML =
                `<span style="background:${cor[pedido.nome_status]}18;color:${cor[pedido.nome_status]};padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;">${pedido.nome_status}</span>`;

            document.getElementById('modalProdutos').innerHTML = itens.map(i => `
                <li style="display:flex;align-items:center;gap:12px;padding:10px;background:#f8f9ff;border:1.5px solid #eef0fb;border-radius:10px;margin-bottom:8px;list-style:none;">
                    <img src="${i.imagem_produto ? 'img/' + i.imagem_produto : 'assets/img/sem-imagem.png'}"
                        style="width:46px;height:46px;border-radius:8px;object-fit:cover;border:1.5px solid #e8edf8;" alt="">
                    <div style="flex:1;">
                        <div style="font-weight:800;font-size:13.5px;">${i.nome_produto}</div>
                        <div style="font-size:12px;color:#7a85a3;">Qtd: ${i.quantidade}</div>
                    </div>
                    <strong style="color:#6c7ef8;">R$ ${parseFloat(i.preco_unitario).toFixed(2).replace('.', ',')}</strong>
                </li>
            `).join('');
        });
}

    function fecharModal() {
        document.getElementById('modalOverlay').classList.remove('aberto');
        document.body.style.overflow = '';
    }

    // ── MODAL CRIAR PEDIDO
    let contadorItem = 0;

    function abrirModalCriar() {
        document.getElementById('criar-cliente').value = '';
        document.getElementById('criar-obs').value    = '';
        const hoje = new Date();
        const ano  = hoje.getFullYear();
        const mes  = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia  = String(hoje.getDate()).padStart(2, '0');
        document.getElementById('criar-data').value = `${ano}-${mes}-${dia}`;
        document.getElementById('listaItensCriar').innerHTML = '';
        document.getElementById('criar-total-valor').textContent = 'R$ 0,00';
        contadorItem = 0;
        adicionarItemCriar();
        document.getElementById('modalCriarPedido').classList.add('aberto');
        document.body.style.overflow = 'hidden';
    }

    function fecharModalCriar() {
        document.getElementById('modalCriarPedido').classList.remove('aberto');
        document.body.style.overflow = '';
    }

    function adicionarItemCriar() {
        contadorItem++;
        const id    = `item-criar-${contadorItem}`;
        const lista = document.getElementById('listaItensCriar');

        // Monta o select de produtos com os dados do PHP
        const options = produtosDisponiveis.map(p =>
            `<option value="${p.id_produto}" data-preco="${p.preco_produto}">${p.nome_produto}</option>`
        ).join('');

        const div = document.createElement('div');
        div.className = 'item-pedido-form';
        div.id = id;
        div.innerHTML = `
            <select name="id_produto[]" onchange="preencherPreco(this)">
                <option value="">Selecionar produto…</option>
                ${options}
            </select>
            <input type="number" name="quantidade[]" placeholder="Qtd" min="1" value="1" oninput="recalcularTotal()">
            <input type="number" name="preco_unitario[]" placeholder="R$ 0,00" min="0" step="0.01" oninput="recalcularTotal()">
            <button type="button" class="btn-remover-item" onclick="removerItemCriar('${id}')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        `;
        lista.appendChild(div);
    }

    // Preenche o preço automaticamente ao selecionar o produto
    function preencherPreco(select) {
        const preco  = select.options[select.selectedIndex].dataset.preco;
        const campos = select.closest('.item-pedido-form').querySelectorAll('input');
        if (preco) campos[1].value = preco;
        recalcularTotal();
    }

    function removerItemCriar(id) {
        document.getElementById(id)?.remove();
        recalcularTotal();
    }

    function recalcularTotal() {
        let total = 0;
        document.querySelectorAll('#listaItensCriar .item-pedido-form').forEach(item => {
            const qtd   = parseFloat(item.querySelectorAll('input')[0].value) || 0;
            const preco = parseFloat(item.querySelectorAll('input')[1].value) || 0;
            total += qtd * preco;
        });
        document.getElementById('criar-total-valor').textContent =
            'R$ ' + total.toFixed(2).replace('.', ',');
    }

function salvarNovoPedido() {

    const cliente = document.getElementById('criar-cliente').value;
    const data    = document.getElementById('criar-data').value;
    const itens   = document.querySelectorAll('#listaItensCriar .item-pedido-form');

    if (!cliente) {
        mostrarToast('Selecione um cliente!', '#ef4444');
        document.getElementById('criar-cliente').focus();
        return;
    }

    
    if (!data) {
        mostrarToast('Informe a data do pedido!', '#ef4444');
        document.getElementById('criar-data').focus();
        return;
    }
    const hoje     = new Date();
    hoje.setHours(0, 0, 0, 0);
    const dataSelecionada = new Date(data + 'T00:00:00');
    if (dataSelecionada < hoje) {
        mostrarToast('A data não pode ser no passado!', '#ef4444');
        document.getElementById('criar-data').focus();
        return;
    }

    if (itens.length === 0) {
        mostrarToast('Adicione pelo menos um produto!', '#ef4444');
        return;
    }


    let produtoVazio = false;
    itens.forEach(item => {
        const select = item.querySelector('select');
        if (!select.value) produtoVazio = true;
    });

    if (produtoVazio) {
        mostrarToast('Selecione o produto em todos os itens!', '#ef4444');
        return;
    }


    let qtdInvalida = false;
    itens.forEach(item => {
        const qtd = parseInt(item.querySelectorAll('input')[0].value);
        if (!qtd || qtd <= 0) qtdInvalida = true;
    });

    if (qtdInvalida) {
        mostrarToast('A quantidade deve ser maior que zero!', '#ef4444');
        return;
    }
    const total = parseFloat(
        document.getElementById('criar-total-valor')
            .textContent.replace('R$ ', '').replace(',', '.')
    );

    if (total <= 0) {
        mostrarToast('O total do pedido deve ser maior que zero!', '#ef4444');
        return;
    }

    document.getElementById('input-total-pedido').value = total;
    document.getElementById('formCriarPedido').submit();
}

    // Fecha modais clicando fora
    ['modalOverlay','modalCriarPedido'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('aberto');
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        fecharModal();
        fecharModalCriar();
    });

    function alterarStatus(select, idPedido) {
    const novoStatus = select.value;

    // Atualiza a cor do select na hora
    select.className = 'select-status';
    const classes = { '1':'pendente', '2':'andamento', '3':'entregue', '4':'cancelado' };
    select.classList.add('status-' + (classes[novoStatus] || 'pendente'));

    // Envia para o PHP via fetch sem recarregar a página
    fetch('pedidos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=status&id_pedido=${idPedido}&id_status=${novoStatus}`
    })
    .then(r => {
        if (r.ok) {
            mostrarToast('Status atualizado!');
        }
    })
    .catch(() => mostrarToast('Erro ao atualizar status!', '#ef4444'));
}

// Dar entrada — muda status para Entregue
function darEntrada() {
    const idPedido = document.getElementById('inputIdDeletarPedido').value;

    fetch('pedidos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=status&id_pedido=${idPedido}&id_status=3`  // 3 = Entregue
    })
    .then(r => {
        if (r.ok) {
            mostrarToast('Pedido marcado como Entregue!');
            fecharModal();
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(() => mostrarToast('Erro ao atualizar!', '#ef4444'));
}

</script>

</body>
</html>