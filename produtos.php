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

    <div class="products-grid">

    <!-- CARD 1 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSv4mEsKj7jOlaoKR33qlkkgKtkVmb2TuHeHUg8Mi1My2v5Ii5GvsBI5EVR_FxX2xuqd6MdMpgFZPPsShhWJBCGZpkWVjUp-jajtvezet2lc21ji3Yeyy6RBg&usqp=CAc" alt="Produto">
            <div class="badge">ID #001 • Eletrônicos</div>
        </div>

        <div class="product-content">
            <h3>Mouse Gamer RGB</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Mouse ergonômico com iluminação RGB personalizável e alta precisão para jogos.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <a href="produto_editar.php?id=001" class="btn primary">Editar</a>
                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>


    <!-- CARD 2 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8" alt="Produto">
            <div class="badge">ID #002 • Informática</div>
        </div>

        <div class="product-content">
            <h3>Notebook Ultra Slim</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Notebook leve e potente com SSD NVMe e tela Full HD ideal para produtividade.</p>

            <span class="stock out-stock">Esgotado</span>

            <div class="card-actions">
                <a href="produto_editar.php?id=002" class="btn primary">Editar</a>
                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>


    <!-- CARD 3 -->
    <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <a href="produto_editar.php?id=003" class="btn primary">Editar</a>
                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <a href="produto_editar.php?id=003" class="btn primary">Editar</a>
                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
                <a href="produto_editar.php?id=003" class="btn primary">Editar</a>
                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>

        <div class="product-card">
        <div class="product-image">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" alt="Produto">
            <div class="badge">ID #003 • Acessórios</div>
        </div>

        <div class="product-content">
            <h3>Smartwatch Series X</h3>
            <span class="price"><i>R$ 149,90</i></span>
            <p>Relógio inteligente com monitoramento cardíaco, notificações e bateria de longa duração.</p>

            <span class="stock in-stock">Em estoque</span>

            <div class="card-actions">
<a href="produto_editar.php?id=001" class="btn primary">
    Editar
</a>                <button class="btn danger" onclick="abrirModalExcluir(this)">Remover</button>
            </div>
        </div>
    </div>

</div>

</div>




<!-- MODAL DE + NOVO PRODUTO -->
<!-- Modal -->
  <div id="productModal" class="modal">
    <div class="modal-product">

      <!-- Header -->
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

      <!-- Body -->
      <div class="modal-body">
        <form class="product-form" id="productForm">
          <div class="form-grid">

            <div class="form-group">
              <label>Nome do Produto</label>
              <input type="text" required placeholder="Ex: Tênis Air Max"/>
            </div>

            <div class="form-group">
              <label>Preço (R$)</label>
              <input type="number" required placeholder="0,00" step="0.01"/>
            </div>

            <div class="form-group">
              <label>Categoria</label>
              <select required>
                <option value="" disabled selected>Selecionar…</option>
                <option>Eletrônicos</option>
                <option>Informática</option>
                <option>Acessórios</option>
              </select>
            </div>

            <div class="form-group">
              <label>Estoque</label>
              <input type="number" required placeholder="0"/>
            </div>

            <div class="form-group full">
              <label>Descrição</label>
              <textarea rows="3" required placeholder="Descrição curta do produto…"></textarea>
            </div>

            <div class="form-group full">
              <label>Imagem do Produto</label>

              <!-- Preview (oculto até ter imagem) -->
              <div class="img-preview" id="imgPreview" style="display:none;">
                <img id="previewImg" src="" alt="Preview"/>
                <button type="button" class="img-remove" onclick="removerImagem()" title="Remover imagem">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                  </svg>
                </button>
              </div>

              <!-- Input file (oculto quando há imagem) -->
              <label class="file-label" id="filePickerLabel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="3" width="18" height="18" rx="3"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <path d="M21 15l-5-5L5 21"/>
                </svg>
                <span id="fileLabel">Clique para escolher uma imagem…</span>
                <input type="file" accept="image/*" required id="fileInput"/>
              </label>
            </div>

          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
                <button type="submit" class="btn primary" onclick="salvarProduto()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
          Salvar Produto
        </button>

      </div>

    </div>
  </div>



<div id="toastContainer" class="toast-container"></div>
<?php include("components/modalExcluirProduto.php"); ?>
<?php include("components/modalSair.php"); ?>
<script src="./js/main.js"></script>



<script>
       // nome do arquivo + preview
    document.getElementById('fileInput').addEventListener('change', function() {
      if (!this.files.length) return;
      const file = this.files[0];
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imgPreview').style.display = 'block';
        document.getElementById('filePickerLabel').style.display = 'none';
      };
      reader.readAsDataURL(file);
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