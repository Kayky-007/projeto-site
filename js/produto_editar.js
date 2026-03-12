/**
 * produto_editar.js
 * JavaScript essencial: preview de imagem, loading no save,
 * contador de caracteres, estoque baixo e desconto calculado.
 */

/* ─────────────────────────────────────────────────────────────
   1. INICIALIZAÇÃO — Roda quando o DOM estiver pronto
───────────────────────────────────────────────────────────────*/
document.addEventListener('DOMContentLoaded', () => {
  initCharCounters();
  checkStock(document.getElementById('estoque'));
  calcDiscount();
  syncStatusStyles();
});

/* ─────────────────────────────────────────────────────────────
   2. PREVIEW DE IMAGEM
   Lê o arquivo selecionado e exibe no <img> sem page reload
───────────────────────────────────────────────────────────────*/
function handleImagePreview(event) {
  const file = event.target.files[0];
  if (!file) return;

  // Validação de tipo
  const allowed = ['image/jpeg', 'image/png', 'image/webp'];
  if (!allowed.includes(file.type)) {
    alert('Formato inválido. Use JPG, PNG ou WEBP.');
    return;
  }

  // Validação de tamanho (5 MB)
  const maxSize = 5 * 1024 * 1024;
  if (file.size > maxSize) {
    alert('Arquivo muito grande. Máximo: 5 MB.');
    return;
  }

  const reader = new FileReader();

  reader.onload = (e) => {
    const previewImg  = document.getElementById('previewImg');
    const previewZone = document.getElementById('uploadPreview');
    const emptyZone   = document.getElementById('uploadEmpty');

    // Exibe a imagem com fade suave
    previewImg.style.opacity = '0';
    previewImg.src = e.target.result;

    previewZone.style.display = 'block';
    emptyZone.style.display   = 'none';

    // Pequena animação de fade-in
    requestAnimationFrame(() => {
      previewImg.style.transition = 'opacity 0.35s ease';
      previewImg.style.opacity = '1';
    });
  };

  reader.readAsDataURL(file);
}

/* Suporte a drag-and-drop na upload-zone */
function handleDragOver(event) {
  event.preventDefault();
  event.currentTarget.classList.add('drag-over');
}

function handleDragLeave(event) {
  event.currentTarget.classList.remove('drag-over');
}

function handleDrop(event) {
  event.preventDefault();
  event.currentTarget.classList.remove('drag-over');

  const file = event.dataTransfer.files[0];
  if (!file) return;

  // Reutiliza o handler existente simulando um evento
  handleImagePreview({ target: { files: [file] } });
}

/* Remove a imagem atual */
function removeImage() {
  const previewImg  = document.getElementById('previewImg');
  const previewZone = document.getElementById('uploadPreview');
  const emptyZone   = document.getElementById('uploadEmpty');

  previewZone.style.display = 'none';
  emptyZone.style.display   = 'flex';
  previewImg.src = '';
  document.getElementById('imageInput').value = '';
}

/* ─────────────────────────────────────────────────────────────
   3. SAVE BUTTON — Animação de loading
───────────────────────────────────────────────────────────────*/
function handleSave() {
  const btn        = document.getElementById('saveBtn');
  const btnContent = document.getElementById('saveBtnContent');
  const btnLoading = document.getElementById('saveBtnLoading');
  const saveStatus = document.getElementById('saveStatus');

  // Inicia estado de loading
  btn.disabled = true;
  btnContent.hidden = true;
  btnLoading.hidden = false;

  // Atualiza o save-status para "salvando..."
  setStatusSaving(saveStatus);

  // Simula chamada de API (1.8s)
  setTimeout(() => {
    // Retorna ao estado normal
    btn.disabled = false;
    btnContent.hidden = false;
    btnLoading.hidden = true;

    // Atualiza save-status para "salvo agora"
    setStatusSaved(saveStatus);

    // Feedback visual leve no botão
    btn.classList.add('save-success');
    setTimeout(() => btn.classList.remove('save-success'), 1500);

  }, 1800);
}

function setStatusSaving(el) {
  const dot  = el.querySelector('.save-status__dot');
  const text = el.querySelector('.save-status__text');
  dot.className  = 'save-status__dot save-status__dot--saving';
  text.textContent = 'Salvando...';
}

function setStatusSaved(el) {
  const dot  = el.querySelector('.save-status__dot');
  const text = el.querySelector('.save-status__text');
  dot.className  = 'save-status__dot save-status__dot--ok';
  text.textContent = 'Salvo agora';
}

/* ─────────────────────────────────────────────────────────────
   4. CANCELAR
───────────────────────────────────────────────────────────────*/
function handleCancel() {
  // Em produção: redireciona para a listagem.
  // Por ora, confirmação simples.
  if (confirm('Descartar as alterações não salvas?')) {
    // window.location.href = 'produtos.php';
    console.log('Cancelado — redirecionar para listagem.');
  }
}

/* ─────────────────────────────────────────────────────────────
   5. ESTOQUE BAIXO
   Aplica classe de alerta âmbar quando qtd ≤ 10
───────────────────────────────────────────────────────────────*/
function checkStock(input) {
  const alert = document.getElementById('stockAlert');
  const value = parseInt(input.value, 10);
  const LOW_THRESHOLD = 10;

  if (!isNaN(value) && value <= LOW_THRESHOLD) {
    input.classList.add('input--stock-low');
    alert.classList.add('visible');
  } else {
    input.classList.remove('input--stock-low');
    alert.classList.remove('visible');
  }
}

/* ─────────────────────────────────────────────────────────────
   6. DESCONTO CALCULADO AUTOMATICAMENTE
───────────────────────────────────────────────────────────────*/
function calcDiscount() {
  const preco    = parseFloat(document.getElementById('preco')?.value)         || 0;
  const original = parseFloat(document.getElementById('precoOriginal')?.value) || 0;

  const badge  = document.getElementById('discountBadge');
  const saving = document.getElementById('discountSaving');

  if (original > preco && preco > 0) {
    const pct  = Math.round(((original - preco) / original) * 100);
    const save = (original - preco).toFixed(2).replace('.', ',');

    badge.textContent  = `−${pct}%`;
    saving.textContent = `Economia R$ ${save}`;
  } else {
    badge.textContent  = '−0%';
    saving.textContent = 'Sem desconto';
  }
}

/* ─────────────────────────────────────────────────────────────
   7. CONTADORES DE CARACTERES
───────────────────────────────────────────────────────────────*/
function initCharCounters() {
  // Nome do produto
  const nomeInput    = document.getElementById('nome');
  const nameCounter  = document.getElementById('nameCounter');

  if (nomeInput && nameCounter) {
    const update = () => {
      nameCounter.textContent = `${nomeInput.value.length}/${nomeInput.maxLength}`;
    };
    nomeInput.addEventListener('input', update);
    update();
  }

  // Descrição
  const descInput   = document.getElementById('descricao');
  const descCounter = document.getElementById('descCounter');

  if (descInput && descCounter) {
    const update = () => {
      descCounter.textContent = `${descInput.value.length} / ${descInput.maxLength}`;
    };
    descInput.addEventListener('input', update);
    update();
  }
}

/* ─────────────────────────────────────────────────────────────
   8. STATUS RADIO — Sincroniza estilos visuais
───────────────────────────────────────────────────────────────*/
function syncStatusStyles() {
  const radios = document.querySelectorAll('input[name="status"]');
  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      // O CSS já cuida dos estados via :checked,
      // mas você pode adicionar lógica extra aqui se necessário
      console.log('Status alterado para:', radio.value);
    });
  });
}

/* ─────────────────────────────────────────────────────────────
   9. SIDEBAR MOBILE — Abrir/fechar
───────────────────────────────────────────────────────────────*/
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  sidebar.classList.toggle('open');
  overlay.classList.toggle('visible');

  // Trava o scroll do body enquanto sidebar está aberta
  document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
}