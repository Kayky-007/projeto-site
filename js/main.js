console.log("main.js carregado ✅");

// MODAL DE SAIR DO SISTEMA
function abrirModalLogout() {
    document.getElementById("logoutModal").style.display = "flex";
}

function fecharModal() {
    document.getElementById("logoutModal").style.display = "none";
}

function sairSistema() {
    window.location.href = "login.php";
}


// MODAL DE + NOVO PRODUTO
function abrirModalProduto() {
    document.getElementById("productModal").style.display = "flex";
    document.body.style.overflow = "hidden";
}

function fecharModalProduto() {
    document.getElementById("productModal").style.display = "none";
    document.body.style.overflow = "auto";
}

/* Fecha clicando fora */
window.addEventListener("click", function(e){
    const modal = document.getElementById("productModal");
    if(e.target === modal){
        fecharModalProduto();
    }
});




// BUSCA DE PRODUTOS
function buscarProdutos() {

    let input = document.getElementById("searchProduct").value.toLowerCase();

    let cards = document.querySelectorAll(".product-card");

    cards.forEach(function(card){

        let nome = card.querySelector("h3").innerText.toLowerCase();
        let badge = card.querySelector(".badge").innerText.toLowerCase();

        if(nome.includes(input) || badge.includes(input)){
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }

    });

}



// MODAL DE EXCLUIR PRODUTO
let produtoSelecionado = null;

function abrirModalExcluir(botao){

    produtoSelecionado = botao.closest(".product-card");

    document.getElementById("deleteModal").style.display = "flex";

    document.body.style.overflow = "hidden";
}

function fecharModalExcluir(){

    document.getElementById("deleteModal").style.display = "none";

    document.body.style.overflow = "auto";
}

function confirmarExclusao(){

    if(produtoSelecionado){
        produtoSelecionado.remove();
    }

    fecharModalExcluir();
}


// ALERT PARA CRIAR NOVO PRODUTO
// ===== TOAST SaaS Premium (à prova de tudo) =====
(function initToastSystem() {
  // Cria container se não existir
  let container = document.getElementById("toastContainer");
  if (!container) {
    container = document.createElement("div");
    container.id = "toastContainer";
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  // Injeta CSS mínimo se não existir (para garantir que aparece)
  if (!document.getElementById("toastStyles")) {
    const style = document.createElement("style");
    style.id = "toastStyles";
    style.innerHTML = `
      .toast-container{
        position:fixed; top:24px; right:24px;
        display:flex; flex-direction:column; gap:12px;
        z-index:9999999;
        pointer-events:none;
      }
      .toast{
        pointer-events:auto;
        min-width:280px;
        max-width:360px;
        padding:14px 16px;
        border-radius:14px;
        display:flex; align-items:center; gap:10px;
        color:#fff; font: 500 14px/1.2 Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        box-shadow: 0 16px 40px rgba(0,0,0,.28);
        transform: translateX(30px);
        opacity:0;
        transition: all .35s ease;
        backdrop-filter: blur(10px);
      }
      .toast.show{ transform: translateX(0); opacity:1; }
      .toast-success{
        background: linear-gradient(135deg,#10b981,#22c55e);
      }
      .toast-icon{
        width:28px; height:28px; border-radius:10px;
        display:grid; place-items:center;
        background: rgba(255,255,255,.18);
        flex: 0 0 auto;
      }
      .toast-text{ display:flex; flex-direction:column; gap:2px; }
      .toast-title{ font-weight:700; }
      .toast-desc{ opacity:.9; font-weight:500; }
    `;
    document.head.appendChild(style);
  }

  // Função global: sucesso
  window.toastSucesso = function (mensagem, titulo = "Sucesso") {
    const c = document.getElementById("toastContainer") || container;

    const toast = document.createElement("div");
    toast.className = "toast toast-success";
    toast.innerHTML = `
      <div class="toast-icon">✓</div>
      <div class="toast-text">
        <div class="toast-title">${titulo}</div>
        <div class="toast-desc">${mensagem}</div>
      </div>
    `;

    c.appendChild(toast);

    // anima entrada
    requestAnimationFrame(() => toast.classList.add("show"));

    // sai e remove
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 350);
    }, 2800);
  };

  console.log("Toast System carregado ✅");
})();

window.salvarProduto = function () {
  toastSucesso("Produto salvo com sucesso!", "Produto cadastrado");
  fecharModalProduto(); // se você já tem essa função
};


// ALERT DE SALVAR ALTERAÇÕES
function salvarAlteracoes(){

    toastSucesso("Produto atualizado com sucesso!");

    setTimeout(() => {
        window.location.href = "produtos.php";
    }, 1500);

}











