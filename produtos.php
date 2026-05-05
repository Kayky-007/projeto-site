<?php
 
require_once "model/Produto.php";
 
$produto  = new Produto();
$produtos = $produto->listarTodos();
$categorias = $produto->listarCategorias();
 
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
  .main {
    margin-left: 240px; /* empurra pra não cobrir a sidebar */
}

    /* demo trigger */
    .open-btn {
      padding: 11px 24px;
      background: #6c7ef8;
      color: white;
      border: none;
      border-radius: 12px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(108,126,248,.35);
      transition: background .15s, transform .15s;
    }
    .open-btn:hover { background: #5668f0; transform: translateY(-1px); }

    /* ── Overlay ── */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(30, 42, 69, 0.35);
      backdrop-filter: blur(3px);
      z-index: 999;
      align-items: center;
      justify-content: center;
      padding: 16px;
      animation: fadeIn .2s ease;
    }

    .modal.open { display: flex; }

    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }

    /* ── Card ── */
    .modal-product {
      background: white;
      border-radius: 20px;
      width: 100%;
      max-width: 560px;
      box-shadow: 0 20px 60px rgba(30, 42, 69, 0.18);
      border: 1.5px solid #e8edf8;
      animation: slideUp .25s ease;
      overflow: hidden;
    }

    @keyframes slideUp {
      from { transform: translateY(24px); opacity: 0; }
      to   { transform: translateY(0);    opacity: 1; }
    }

    /* ── Header ── */
    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 22px 26px 18px;
      border-bottom: 1.5px solid #f0f3fb;
    }
    

    .modal-header-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header-icon {
      width: 36px;
      height: 36px;
      background: #eef0fd;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-icon svg { width: 18px; height: 18px; color: #6c7ef8; }

    .modal-header h2 {
      font-size: 17px;
      font-weight: 800;
      color: #1e2a45;
    }

    .btn-close {
      width: 32px;
      height: 32px;
      border: none;
      background: #f0f3fb;
      border-radius: 8px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #7a85a3;
      transition: background .15s, color .15s;
    }
    .btn-close:hover { background: #fee2e2; color: #ef4444; }
    .btn-close svg { width: 16px; height: 16px; }

    /* ── Body ── */
    .modal-body { padding: 24px 26px; }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group.full { grid-column: 1 / -1; }

    .form-group label {
      font-size: 12px;
      font-weight: 700;
      color: #7a85a3;
      letter-spacing: .4px;
      text-transform: uppercase;
    }

    a {
    text-decoration: none;
}

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 10px 13px;
      border-radius: 10px;
      border: 1.5px solid #dde3f0;
      background: #f6f8fe;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 600;
      color: #1e2a45;
      transition: border-color .18s, box-shadow .18s, background .18s;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder { color: #b0bad4; font-weight: 600; }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #6c7ef8;
      background: white;
      box-shadow: 0 0 0 3px rgba(108,126,248,.13);
    }

    .form-group textarea { resize: none; }

    /* file input */
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
    .file-label:hover { border-color: #6c7ef8; background: #eef0fd; color: #4f5de4; }
    .file-label svg { width: 17px; height: 17px; flex-shrink: 0; }
    .file-label input[type="file"] { display: none; }

    /* preview de imagem */
    .img-preview {
      position: relative;
      width: 291px;
      height: 180px;
      border-radius: 10px;
      overflow: hidden;
      border: 1.5px solid #dde3f0;
      background: #f6f8fe;
    }

    .img-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .img-remove {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 28px;
      height: 28px;
      background: rgba(255,255,255,.9);
      border: none;
      border-radius: 7px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ef4444;
      box-shadow: 0 2px 6px rgba(0,0,0,.12);
      transition: background .15s, transform .15s;
    }
    .img-remove:hover { background: #fee2e2; transform: scale(1.08); }
    .img-remove svg { width: 14px; height: 14px; }

    /* ── Footer ── */
    .modal-footer {
      padding: 16px 26px 22px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      border-top: 1.5px solid #f0f3fb;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 11px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: transform .15s, background .15s, box-shadow .15s;
    }

    .btn.cancel {
      background: #f0f3fb;
      color: #7a85a3;
      border: 1.5px solid #dde3f0;
    }
    .btn.cancel:hover { background: #e4e9f7; }

    .btn.primary {
      background: #6c7ef8;
      color: white;
      box-shadow: 0 4px 14px rgba(108,126,248,.35);
    }
    .btn.primary:hover { background: #5668f0; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(108,126,248,.40); }

    .btn svg { width: 15px; height: 15px; }
    
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
 
                        <?php if ($p['estoque_produto'] > 0): ?>
                            <span class="stock in-stock">Em estoque (<?= $p['estoque_produto'] ?>)</span>
                        <?php else: ?>
                            <span class="stock out-stock">Esgotado</span>
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
                        <select name="id_categoria" required>
                            <option value="" disabled selected>Selecionar…</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>">
                                    <?= htmlspecialchars($cat['nome_categoria']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
 
<script src="./js/main.js"></script>
 
<script>
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
</script>
 
</body>
</html>