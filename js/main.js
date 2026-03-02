

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