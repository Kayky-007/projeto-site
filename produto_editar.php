<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Produto</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
  <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Nunito', sans-serif;
      background: #f3ecec;
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 40px 16px;
    }

    .container { width: 100%; max-width: 620px; }

    /* Header */
    .header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
      color: #5b6684;
      background: white;
      border: 1.5px solid #dde3f0;
      border-radius: 10px;
      padding: 7px 14px;
      font-size: 13px;
      font-weight: 700;
      transition: background .15s, border-color .15s;
    }
    .btn-back:hover { background: #eef1fb; border-color: #c3cbe8; }
    .btn-back svg { width: 15px; height: 15px; }

    .header h1 { font-size: 20px; font-weight: 800; color: #1e2a45; }

    /* Card */
    .card {
      background: white;
      border-radius: 18px;
      padding: 32px 28px;
      box-shadow: 0 4px 24px rgba(90,110,180,.10);
      border: 1.5px solid #e8edf8;
    }

    /* Form */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }

    .field { display: flex; flex-direction: column; gap: 6px; }
    .field.full { grid-column: 1 / -1; }

    .field label {
      font-size: 12.5px;
      font-weight: 700;
      color: #7a85a3;
      letter-spacing: .4px;
      text-transform: uppercase;
    }

    .field input,
    .field select,
    .field textarea {
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
    .field input:focus,
    .field select:focus,
    .field textarea:focus {
      outline: none;
      border-color: #6c7ef8;
      background: white;
      box-shadow: 0 0 0 3px rgba(108,126,248,.13);
    }
    .field textarea { resize: vertical; min-height: 90px; }

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
    .file-label svg { width: 18px; height: 18px; flex-shrink: 0; }
    .file-label input[type="file"] { display: none; }

    /* preview */
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

    /* divider */
    .divider {
      border: none;
      border-top: 1.5px solid #eef1fb;
      margin: 26px 0 22px;
    }

    /* actions */
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 11px;
      font-family: 'Nunito', sans-serif;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: transform .15s, box-shadow .15s, background .15s;
    }
    .btn-cancel {
      background: #f0f3fb;
      color: #7a85a3;
      border: 1.5px solid #dde3f0;
    }
    .btn-cancel:hover { background: #e4e9f7; }
    .btn-save {
      background: #6c7ef8;
      color: white;
      box-shadow: 0 4px 14px rgba(108,126,248,.35);
    }
    .btn-save:hover { background: #5668f0; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(108,126,248,.40); }
    .btn svg { width: 15px; height: 15px; }

    /* toast */
    .toast {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #22c55e;
      color: white;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 700;
      box-shadow: 0 6px 20px rgba(34,197,94,.35);
      display: flex;
      align-items: center;
      gap: 8px;
      transform: translateY(80px);
      opacity: 0;
      transition: transform .3s, opacity .3s;
      pointer-events: none;
    }
    .toast.show { transform: translateY(0); opacity: 1; }

    
  </style>
</head>
<body>

<div class="container">

  <div class="header">
    <a href="produtos.php" class="btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      Voltar
    </a>
    <h1 class="title">Editar Produto</h1>
  </div>

  <div class="card">
    <form id="editForm">
      <div class="form-grid">

        <div class="field">
          <label>Nome do Produto</label>
          <input type="text" value="Tênis Air Max Pro" placeholder="Ex: Tênis Air Max Pro" />
        </div>

        <div class="field">
          <label>Categoria</label>
          <select>
            <option>Calçados</option>
            <option>Eletrônicos</option>
            <option>Acessórios</option>
          </select>
        </div>

        <div class="field">
          <label>Preço (R$)</label>
          <input type="number" step="0.01" value="349.90" placeholder="0,00" />
        </div>

        <div class="field">
          <label>Estoque</label>
          <input type="number" value="7" placeholder="0" />
        </div>

        <div class="field full">
          <label>Descrição</label>
          <textarea>Tênis de alta performance para uso diário e esportes.</textarea>
        </div>

        <div class="field full">
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

          <!-- Input file -->
          <label class="file-label" id="filePickerLabel">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="3"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <path d="M21 15l-5-5L5 21"/>
            </svg>
            <span id="fileLabel">Clique para escolher uma imagem…</span>
            <input type="file" accept="image/*" id="fileInput" />
          </label>
        </div>

      </div>

      <hr class="divider" />

      <div class="form-actions">
        <button type="submit" class="btn btn-save" >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
          Salvar Alterações
        </button>
      </div>

    </form>
  </div>

</div>

<div class="toast" id="toast">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M20 6L9 17l-5-5"/>
  </svg>
  Produto salvo com sucesso!
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function () {
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
  const toast = document.getElementById('toast');
  document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2800);
    
  });



</script>

</body>
</html>