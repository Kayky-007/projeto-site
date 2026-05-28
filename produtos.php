<?php require_once "auth.php"; ?>
<?php
 
require_once "model/Produto.php";
 
$produto  = new Produto();
$produtos = $produto->listarTodos();
$categorias = $produto->listarCategorias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Criar categoria
if (isset($_POST['acao']) && $_POST['acao'] === 'criar_categoria') {
    $criou = $produto->criarCategoria($_POST['nome_categoria']);

    if ($criou) {
        header("Location: produtos.php?cat=1");
    } else {
        header("Location: produtos.php?cat=erro");
    }
    exit;
}
}
 
// Salva um novo produto quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $dados = [
        'nome_produto'      => $_POST['nome_produto'],
        'preco_produto'     => $_POST['preco_produto'],
        'id_categoria'      => $_POST['id_categoria'],
        'estoque_produto'   => $_POST['estoque_produto'],
        'descricao_produto' => $_POST['descricao_produto'],
        'imagem_produto'    => null
    ];
 
    // Se o usuário enviou uma imagem, salva ela na pasta
    if (!empty($_FILES['imagem_produto']['name'])) {
        $dados['imagem_produto'] = $produto->salvarImagem($_FILES['imagem_produto']);
    }
 
    $produto->criar($dados);
 
    // Recarrega a página para mostrar o novo produto
    header("Location: produtos.php");
    exit;
}
 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/produtos.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/modalExcluirProduto.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
/* ========== VARIÁVEIS ========== */
:root {
    --bg-main: #f0f4ff;
    --card-bg: #ffffff;
    --text-primary: #1e2a45;
    --text-secondary: #64748b;
    --indigo: #6c7ef8;
    --indigo-hover: #5668f0;
    --danger: #ef4444;
    --border-color: #dde3f0;
    --success: #22c55e;
    --sidebar-width: 230px;
}

/* ========== MAIN ========== */
.main {
    margin-left: var(--sidebar-width);
    padding: 36px 36px 60px;
    min-height: 100vh;
    background: var(--bg-main);
    font-family: 'Nunito', sans-serif;
}

/* ========== TOPBAR ========== */
.topbar {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.topbar h1 {
    font-size: 20px;
    font-weight: 800;
    color: var(--text-primary);
    margin-right: auto;
}

/* ========== BUSCA ========== */
.search-container { position: relative; }

.search-container i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 18px;
}

.search-container input {
    width: 240px;
    padding: 10px 12px 10px 38px;
    border-radius: 10px;
    border: 1.5px solid var(--border-color);
    outline: none;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    font-weight: 600;
    background: white;
    color: var(--text-primary);
    transition: border-color .2s, box-shadow .2s;
}

.search-container input:focus {
    border-color: var(--indigo);
    box-shadow: 0 0 0 3px rgba(108,126,248,.13);
}

/* ========== BOTÃO NOVO PRODUTO ========== */
.btn-add-product {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--indigo);
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(108,126,248,.25);
    transition: all .2s ease;
    white-space: nowrap;
}

.btn-add-product i { font-size: 18px; }
.btn-add-product:hover { background: var(--indigo-hover); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,126,248,.35); }
.btn-add-product:active { transform: scale(0.97); }

/* ========== GRID DE PRODUTOS ========== */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
}

/* ========== CARD ========== */
.product-card {
    background: var(--card-bg);
    border-radius: 14px;
    overflow: hidden;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 4px 12px rgba(15,23,42,.05);
    transition: all .3s ease;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(15,23,42,.12);
}

/* ========== IMAGEM ========== */
.product-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s;
}

.product-card:hover .product-image img { transform: scale(1.04); }

.badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(15,23,42,.75);
    color: white;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 700;
    border-radius: 6px;
    backdrop-filter: blur(4px);
}

/* ========== CONTEÚDO ========== */
.product-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.product-content h3 {
    font-size: 15px;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.price {
    display: inline-block;
    background: #f0f3fb;
    border: 1.5px solid var(--border-color);
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 800;
    font-style: normal;
    padding: 5px 10px;
    border-radius: 8px;
    width: fit-content;
    margin-bottom: 8px;
}

.product-content p {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
}

/* ========== ESTOQUE ========== */
.stock {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 14px;
}

.in-stock   { color: var(--success); }
.out-stock  { color: var(--danger); }
.stock-baixo  { color: #f97316; }
.stock-critico{ color: var(--danger); }

/* ========== AÇÕES DO CARD ========== */
.card-actions {
    margin-top: auto;
    display: flex;
    gap: 10px;
}

.btn {
    flex: 1;
    padding: 9px 8px;
    border-radius: 9px;
    font-family: 'Nunito', sans-serif;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all .2s ease;
}

.btn.primary {
    background: var(--indigo);
    color: white;
    border: none;
    box-shadow: 0 3px 10px rgba(108,126,248,.25);
}

.btn.primary:hover { background: var(--indigo-hover); transform: translateY(-1px); }

.btn.danger {
    background: transparent;
    border: 1.5px solid var(--danger);
    color: var(--danger);
}

.btn.danger:hover { background: var(--danger); color: white; }

/* ========== MODAL ========== */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(30,42,69,.35);
    backdrop-filter: blur(3px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: fadeIn .2s ease;
}

.modal.open { display: flex; }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

@keyframes slideUp {
    from { transform: translateY(24px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

.modal-product {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-width: 560px;
    max-height: 92vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(30,42,69,.18);
    border: 1.5px solid #e8edf8;
    animation: slideUp .25s ease;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 26px 18px;
    border-bottom: 1.5px solid #f0f3fb;
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    border-radius: 20px 20px 0 0;
}

.modal-header-left { display: flex; align-items: center; gap: 10px; }

.header-icon {
    width: 36px; height: 36px;
    background: #eef0fd;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
}

.header-icon svg { width: 18px; height: 18px; color: var(--indigo); }

.modal-header h2 { font-size: 17px; font-weight: 800; color: var(--text-primary); }

.btn-close {
    width: 32px; height: 32px;
    border: none; background: #f0f3fb;
    border-radius: 8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #7a85a3;
    transition: background .15s, color .15s;
}

.btn-close:hover { background: #fee2e2; color: var(--danger); }
.btn-close svg { width: 16px; height: 16px; }

.modal-body { padding: 22px 26px; }

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group.full { grid-column: 1 / -1; }

.form-group label {
    font-size: 12px;
    font-weight: 700;
    color: #7a85a3;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 10px 13px;
    border-radius: 10px;
    border: 1.5px solid var(--border-color);
    background: #f6f8fe;
    font-family: 'Nunito', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    transition: border-color .18s, box-shadow .18s, background .18s;
    outline: none;
}

.form-group input::placeholder,
.form-group textarea::placeholder { color: #b0bad4; font-weight: 600; }

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--indigo);
    background: white;
    box-shadow: 0 0 0 3px rgba(108,126,248,.13);
}

.form-group textarea { resize: none; }

/* File input */
.file-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 13px;
    border-radius: 10px;
    border: 1.5px dashed #c3cbe8;
    background: #f6f8fe;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    color: #7a85a3;
    transition: border-color .18s, background .18s, color .18s;
}

.file-label:hover { border-color: var(--indigo); background: #eef0fd; color: #4f5de4; }
.file-label svg { width: 17px; height: 17px; flex-shrink: 0; }
.file-label input[type="file"] { display: none; }

/* Preview imagem */
.img-preview {
    position: relative;
    width: 291px;
    height: 180px;
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid var(--border-color);
    background: #f6f8fe;
}

.img-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }

.img-remove {
    position: absolute;
    top: 8px; right: 8px;
    width: 28px; height: 28px;
    background: rgba(255,255,255,.9);
    border: none; border-radius: 7px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--danger);
    box-shadow: 0 2px 6px rgba(0,0,0,.12);
    transition: background .15s, transform .15s;
}

.img-remove:hover { background: #fee2e2; transform: scale(1.08); }
.img-remove svg { width: 14px; height: 14px; }

/* Modal footer */
.modal-footer {
    padding: 16px 26px 22px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-top: 1.5px solid #f0f3fb;
}

.btn.cancel {
    background: #f0f3fb;
    color: #7a85a3;
    border: 1.5px solid var(--border-color);
}

.btn.cancel:hover { background: #e4e9f7; }

a { text-decoration: none; }

/* ========== RESPONSIVO ========== */

/* Tablet */
@media (max-width: 1024px) {
    .main {
        margin-left: 0;
        padding: 72px 20px 40px;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }
}

/* Mobile */
@media (max-width: 768px) {
    .main { padding: 68px 14px 40px; }

    .topbar { gap: 10px; }

    .topbar h1 { font-size: 18px; }

    .search-container input { width: 100%; }

    .search-container { flex: 1; min-width: 0; }

    .btn-add-product span { display: none; } /* só ícone no mobile */

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .product-image { height: 140px; }

    .product-content { padding: 14px; }

    .product-content h3 { font-size: 13px; }

    .form-grid { grid-template-columns: 1fr; }

    .img-preview { width: 100%; height: 160px; }

    .modal-product { max-width: 100%; border-radius: 16px; }
}

/* Telas muito pequenas */
@media (max-width: 420px) {
    .products-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .badge { font-size: 10px; padding: 4px 7px; }
}

/* ========== BOTÕES GERAIS ========== */
.btn {
    padding: 9px 16px;
    border-radius: 9px;
    font-family: 'Nunito', sans-serif;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: none;
    transition: all .2s ease;
    white-space: nowrap;
}

/* ========== BOTÕES DO CARD ========== */
.card-actions .btn {
    flex: 1;
}

/* ========== BOTÕES DO MODAL ========== */
.modal-footer .btn {
    flex: none;
    padding: 10px 20px;
}

.btn.primary {
    background: var(--indigo);
    color: white;
    box-shadow: 0 3px 10px rgba(108,126,248,.25);
}

.btn.primary:hover { background: var(--indigo-hover); transform: translateY(-1px); }

.btn.danger {
    background: transparent;
    border: 1.5px solid var(--danger);
    color: var(--danger);
}

.btn.danger:hover { background: var(--danger); color: white; }

.btn.cancel {
    background: #f0f3fb;
    color: #7a85a3;
    border: 1.5px solid var(--border-color);
}

.btn.cancel:hover { background: #e4e9f7; }
</style>
<body>
 
<?php include("components/sidebar.php"); ?>
 
<div class="main">
 
    <div class="topbar">
        <h1>Produtos</h1>
 
        <div class="search-container">
            <i class='bx bx-search'></i>
            <input
                type="text"
                id="searchProduct"
                placeholder="Buscar produtos..."
                onkeyup="buscarProdutos()"
            >
        </div>
 
        <button class="btn-add-product" onclick="abrirModalProduto()">
            <i class='bx bx-plus'></i>
            Novo Produto
        </button>
    </div>
 
 
    <!-- GRID DE PRODUTOS -->
    <div class="products-grid" id="productsGrid">
 
        <?php if (empty($produtos)): ?>
 
            <p style="color:#7a85a3; font-weight:700;">Nenhum produto cadastrado ainda.</p>
 
        <?php else: ?>
 
            <?php foreach ($produtos as $p): ?>
 
                <div class="product-card">
 
                    <div class="product-image">
 
                        <?php if ($p['imagem_produto']): ?>
                            <img src="./img/<?= htmlspecialchars($p['imagem_produto']) ?>" alt="<?= htmlspecialchars($p['nome_produto']) ?>">
                        <?php else: ?>
                            <img src="assets/img/sem-imagem.png" alt="Sem imagem">
                        <?php endif; ?>
 
                        <div class="badge">
                            ID #<?= str_pad($p['id_produto'], 3, '0', STR_PAD_LEFT) ?>
                            • <?= htmlspecialchars($p['nome_categoria'] ?? 'Sem categoria') ?>
                        </div>
 
                    </div>
 
                    <div class="product-content">
 
                        <h3><?= htmlspecialchars($p['nome_produto']) ?></h3>
 
                        <span class="price">
                            <i>R$ <?= number_format($p['preco_produto'], 2, ',', '.') ?></i>
                        </span>
 
                        <p><?= htmlspecialchars($p['descricao_produto'] ?? '') ?></p>

                        <?php if ($p['estoque_produto'] == 0): ?>
                            <span class="stock out-stock">Esgotado</span>
                        <?php elseif ($p['estoque_produto'] <= 5): ?>
                            <span class="stock stock-critico">Estoque Crítico (<?= $p['estoque_produto'] ?>)</span>
                        <?php elseif ($p['estoque_produto'] <= 15): ?>
                            <span class="stock stock-baixo">Estoque baixo (<?= $p['estoque_produto'] ?>)</span>
                        <?php else: ?>
                            <span class="stock in-stock">Em estoque (<?= $p['estoque_produto'] ?>)</span>
                        <?php endif; ?>
 
                        <div class="card-actions">
                            <a href="produto_editar.php?id=<?= $p['id_produto'] ?>" class="btn primary">
                                Editar
                            </a>
                            <button
                                class="btn danger"
                                onclick="abrirModalExcluir(this)"
                                data-id="<?= $p['id_produto'] ?>"
                                data-nome="<?= htmlspecialchars($p['nome_produto']) ?>"
                                >
                                Remover
                            </button>
                        </div>
 
                    </div>
 
                </div>
 
            <?php endforeach; ?>
 
        <?php endif; ?>
 
    </div>
 
</div>
 
 
<!-- MODAL NOVO PRODUTO -->
<div id="productModal" class="modal">
    <div class="modal-product">
 
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#6c7ef8">
                        <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <h2>Novo Produto</h2>
            </div>
            <button class="btn-close" onclick="fecharModalProduto()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
 
        <div class="modal-body">
 
            <!-- action="" envia para essa mesma página -->
            <!-- enctype="multipart/form-data" é obrigatório para enviar imagem -->
            <form class="product-form" id="productForm" method="POST" action="produtos.php" enctype="multipart/form-data">
 
                <div class="form-grid">
 
                    <div class="form-group">
                        <label>Nome do Produto</label>
                        <input type="text" name="nome_produto" required placeholder="Ex: Tênis Air Max"/>
                    </div>
 
                    <div class="form-group">
                        <label>Preço (R$)</label>
                        <input type="number" name="preco_produto" required placeholder="0,00" step="0.01"/>
                    </div>
 
<div class="form-group">
    <label>Categoria</label>
    <div style="display:flex;gap:8px;align-items:center;">
        <select name="id_categoria" required style="flex:1">
            <option value="" disabled selected>Selecionar…</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>">
                    <?= htmlspecialchars($cat['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="button" onclick="abrirModalCategoria()" title="Nova categoria"
            style="width:38px;height:38px;border-radius:9px;border:1.5px solid #dde3f0;background:#f6f8fe;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#6c7ef8;flex-shrink:0;transition:background .15s;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;">
                <path d="M12 5v14M5 12h14"/>
            </svg>
        </button>
    </div>
</div>
 
                    <div class="form-group">
                        <label>Estoque</label>
                        <input type="number" name="estoque_produto" required placeholder="0"/>
                    </div>
 
                    <div class="form-group full">
                        <label>Descrição</label>
                        <textarea name="descricao_produto" rows="3" placeholder="Descrição curta do produto…"></textarea>
                    </div>
 
                    <div class="form-group full">
                        <label>Imagem do Produto</label>
 
                        <div class="img-preview" id="imgPreview" style="display:none;">
                            <img id="previewImg" src="" alt="Preview"/>
                            <button type="button" class="img-remove" onclick="removerImagem()" title="Remover imagem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6L6 18M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
 
                        <label class="file-label" id="filePickerLabel">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="M21 15l-5-5L5 21"/>
                            </svg>
                            <span id="fileLabel">Clique para escolher uma imagem…</span>
                            <input type="file" name="imagem_produto" accept="image/*" id="fileInput"/>
                        </label>
                    </div>
 
                </div>
 
                <div class="modal-footer">
                    <button type="button" class="btn cancel" onclick="fecharModalProduto()">Cancelar</button>
                    <button type="submit" class="btn primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Salvar Produto
                    </button>
                </div>
 
            </form>
 
        </div>
 
    </div>
</div>
 
 
<div id="toastContainer" class="toast-container"></div>
<?php include("components/modalExcluirProduto.php"); ?>
<?php include("components/modalSair.php"); ?>
<!-- MODAL NOVA CATEGORIA -->
<div id="modalCategoria" style="display:none;position:fixed;inset:0;background:rgba(30,42,69,.4);backdrop-filter:blur(3px);z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div style="background:white;border-radius:16px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(30,42,69,.18);border:1.5px solid #e8edf8;overflow:hidden;">

        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1.5px solid #f0f3fb;">
            <h2 style="font-size:16px;font-weight:800;color:#1e2a45;">Nova Categoria</h2>
            <button onclick="fecharModalCategoria()" style="width:30px;height:30px;border:none;background:#f0f3fb;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#7a85a3;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="produtos.php" style="padding:20px 22px;">
            <input type="hidden" name="acao" value="criar_categoria">
            <div class="form-group full">
                <label>Nome da Categoria</label>
                <input type="text" name="nome_categoria" placeholder="Ex: Revistas, Jornais…" required
                    style="padding:10px 13px;border-radius:10px;border:1.5px solid #dde3f0;background:#f6f8fe;font-family:'Nunito',sans-serif;font-size:14px;font-weight:600;color:#1e2a45;outline:none;width:100%;">
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
                <button type="button" onclick="fecharModalCategoria()" class="btn cancel">Cancelar</button>
                <button type="submit" class="btn primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    Criar Categoria
                </button>
            </div>
        </form>

    </div>
</div>
 
<script src="./js/main.js"></script>
 
<script>

    function abrirModalCategoria() {
        document.getElementById('modalCategoria').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function fecharModalCategoria() {
        document.getElementById('modalCategoria').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Fecha clicando fora
    document.getElementById('modalCategoria').addEventListener('click', function(e) {
        if (e.target === this) fecharModalCategoria();
    });

    <?php if (isset($_GET['cat'])): ?>
        // Reabre o modal de produto após criar categoria
        document.addEventListener('DOMContentLoaded', () => abrirModalProduto());
    <?php endif; ?>

    // Preview da imagem antes de enviar
    document.getElementById('fileInput').addEventListener('change', function() {
        if (!this.files.length) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
            document.getElementById('filePickerLabel').style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
    });
 
    function removerImagem() {
        document.getElementById('fileInput').value = '';
        document.getElementById('previewImg').src = '';
        document.getElementById('imgPreview').style.display = 'none';
        document.getElementById('filePickerLabel').style.display = 'flex';
    }
        <?php if (isset($_GET['cat']) && $_GET['cat'] === 'erro'): ?>
        document.addEventListener('DOMContentLoaded', () => {
            toastSucesso('Essa categoria já existe!', 'Atenção');
            abrirModalProduto();
        });
    <?php endif; ?>

    <?php if (isset($_GET['cat']) && $_GET['cat'] === '1'): ?>
        document.addEventListener('DOMContentLoaded', () => abrirModalProduto());
    <?php endif; ?>
</script>
 
</body>
</html>