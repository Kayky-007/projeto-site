/* ========== DADOS DE EXEMPLO ========== */
const pedidos = [
    {
        id: '001', cliente: 'Ana Souza', data: '02/05/2026',
        status: 'pendente', total: 349.80,
        produtos: [
            { nome: 'Tênis Air Max Pro', qtd: 2, preco: 174.90, img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=120&h=120&fit=crop' }
        ]
    },
    {
        id: '002', cliente: 'Carlos Melo', data: '01/05/2026',
        status: 'andamento', total: 899.00,
        produtos: [
            { nome: 'Smartwatch Series X', qtd: 1, preco: 599.00, img: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&h=120&fit=crop' },
            { nome: 'Fone Bluetooth', qtd: 1, preco: 300.00, img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=120&h=120&fit=crop' }
        ]
    },
    {
        id: '003', cliente: 'Fernanda Lima', data: '30/04/2026',
        status: 'entregue', total: 149.90,
        produtos: [
            { nome: 'Mouse Gamer RGB', qtd: 1, preco: 149.90, img: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=120&h=120&fit=crop' }
        ]
    },
    {
        id: '004', cliente: 'Bruno Alves', data: '29/04/2026',
        status: 'cancelado', total: 2399.00,
        produtos: [
            { nome: 'Notebook Ultra Slim', qtd: 1, preco: 2399.00, img: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=120&h=120&fit=crop' }
        ]
    },
    {
        id: '005', cliente: 'Juliana Costa', data: '28/04/2026',
        status: 'pendente', total: 299.70,
        produtos: [
            { nome: 'Teclado Mecânico', qtd: 3, preco: 99.90, img: 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=120&h=120&fit=crop' }
        ]
    },
    {
        id: '006', cliente: 'Rafael Torres', data: '27/04/2026',
        status: 'andamento', total: 659.80,
        produtos: [
            { nome: 'Monitor 24"', qtd: 1, preco: 459.90, img: 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=120&h=120&fit=crop' },
            { nome: 'Cabo HDMI 2m', qtd: 2, preco: 99.95, img: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=120&h=120&fit=crop' }
        ]
    }
];

/* ========== LABELS DE STATUS ========== */
const labelStatus = {
    pendente:  'Pendente',
    andamento: 'Em andamento',
    entregue:  'Entregue',
    cancelado: 'Cancelado'
};

/* ========== ESTADO ========== */
let filtroAtual = 'todos';
let buscaAtual  = '';

/* ========== RENDERIZAR TABELA ========== */
function renderizarTabela() {
    const corpo        = document.getElementById('corpoTabela');
    const semResultados = document.getElementById('semResultados');

    const lista = pedidos.filter(p => {
        const passaStatus = filtroAtual === 'todos' || p.status === filtroAtual;
        const passaBusca  = p.cliente.toLowerCase().includes(buscaAtual) || p.id.includes(buscaAtual);
        return passaStatus && passaBusca;
    });

    // Atualiza contador
    document.getElementById('contador-pedidos').textContent =
        `${lista.length} pedido${lista.length !== 1 ? 's' : ''}`;

    if (lista.length === 0) {
        corpo.innerHTML = '';
        semResultados.style.display = 'block';
        return;
    }

    semResultados.style.display = 'none';

    corpo.innerHTML = lista.map(p => {
        const totalItens = p.produtos.reduce((s, i) => s + i.qtd, 0);
        const iniciais   = p.cliente.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();

        return `
            <tr onclick="abrirModal('${p.id}')">
                <td><span class="id-pedido">#${p.id}</span></td>
                <td>
                    <div class="celula-cliente">
                        <div class="avatar-cliente">${iniciais}</div>
                        <span class="nome-cliente">${p.cliente}</span>
                    </div>
                </td>
                <td>${p.data}</td>
                <td>${totalItens} ${totalItens === 1 ? 'item' : 'itens'}</td>
                <td><strong>R$ ${p.total.toFixed(2).replace('.', ',')}</strong></td>
                <td><span class="badge-status status-${p.status}">${labelStatus[p.status]}</span></td>
                <td>
                    <div class="icone-ver">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

/* ========== FILTRAR POR STATUS ========== */
function filtrarStatus(status, botao) {
    filtroAtual = status;
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('ativo'));
    botao.classList.add('ativo');
    renderizarTabela();
}

/* ========== BUSCAR ========== */
function filtrarTabela() {
    buscaAtual = document.getElementById('campoBusca').value.toLowerCase();
    renderizarTabela();
}

/* ========== ABRIR MODAL ========== */
function abrirModal(id) {
    const pedido = pedidos.find(p => p.id === id);
    if (!pedido) return;

    const totalItens = pedido.produtos.reduce((s, i) => s + i.qtd, 0);

    document.getElementById('modalTitulo').textContent    = `Pedido #${pedido.id}`;
    document.getElementById('modalSubtitulo').textContent = `Realizado em ${pedido.data}`;
    document.getElementById('modalCliente').textContent   = pedido.cliente;
    document.getElementById('modalData').textContent      = pedido.data;
    document.getElementById('modalQtdItens').textContent  = `${totalItens} ${totalItens === 1 ? 'item' : 'itens'}`;
    document.getElementById('modalTotal').textContent     = `R$ ${pedido.total.toFixed(2).replace('.', ',')}`;

    // Badge de status
    document.getElementById('modalStatus').innerHTML =
        `<span class="badge-status status-${pedido.status}">${labelStatus[pedido.status]}</span>`;

    // Lista de produtos
    document.getElementById('modalProdutos').innerHTML = pedido.produtos.map(item => `
        <li class="item-produto">
            <img src="${item.img}" alt="${item.nome}" onerror="this.src='https://via.placeholder.com/52'">
            <div class="item-produto-info">
                <p class="nome">${item.nome}</p>
                <p class="qtd">Quantidade: ${item.qtd}</p>
            </div>
            <span class="preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
        </li>
    `).join('');

    document.getElementById('modalOverlay').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

/* ========== FECHAR MODAL ========== */
function fecharModal() {
    document.getElementById('modalOverlay').classList.remove('aberto');
    document.body.style.overflow = '';
}

// Fecha ao clicar fora do modal
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) fecharModal();
});

// Fecha com tecla ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') fecharModal();
});

/* ========== ATUALIZAR CONTADORES DE RESUMO ========== */
function atualizarResumo() {
    ['pendente', 'andamento', 'entregue', 'cancelado'].forEach(s => {
        document.getElementById(`resumo-${s}`).textContent =
            pedidos.filter(p => p.status === s).length;
    });
    document.getElementById('resumo-total').textContent = pedidos.length;
}

/* ========== MODAL CRIAR PEDIDO ========== */
let contadorItem = 0;

function abrirModalCriar() {
    document.getElementById('criar-cliente').value = '';
    document.getElementById('criar-status').value  = 'pendente';
    document.getElementById('criar-obs').value     = '';
    document.getElementById('criar-data').value    = new Date().toISOString().split('T')[0];
    document.getElementById('listaItensCriar').innerHTML = '';
    document.getElementById('criar-total-valor').textContent = 'R$ 0,00';
    contadorItem = 0;
    adicionarItemCriar(); // já começa com 1 item

    document.getElementById('modalCriarPedido').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

function fecharModalCriar() {
    document.getElementById('modalCriarPedido').classList.remove('aberto');
    document.body.style.overflow = '';
}

function adicionarItemCriar() {
    contadorItem++;
    const id   = `item-criar-${contadorItem}`;
    const lista = document.getElementById('listaItensCriar');

    const div = document.createElement('div');
    div.className = 'item-pedido-form';
    div.id = id;
    div.innerHTML = `
        <input type="text"   placeholder="Nome do produto" />
        <input type="number" placeholder="Qtd" min="1" value="1" oninput="recalcularTotal()" />
        <input type="number" placeholder="R$ 0,00" min="0" step="0.01" oninput="recalcularTotal()" />
        <button class="btn-remover-item" onclick="removerItemCriar('${id}')" title="Remover">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    `;
    lista.appendChild(div);
}

function removerItemCriar(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
    recalcularTotal();
}

function recalcularTotal() {
    const itens = document.querySelectorAll('#listaItensCriar .item-pedido-form');
    let total = 0;
    itens.forEach(item => {
        const qtd   = parseFloat(item.querySelectorAll('input')[1].value) || 0;
        const preco = parseFloat(item.querySelectorAll('input')[2].value) || 0;
        total += qtd * preco;
    });
    document.getElementById('criar-total-valor').textContent =
        'R$ ' + total.toFixed(2).replace('.', ',');
}

function salvarNovoPedido() {
    // Futuramente: envio ao PHP/banco de dados
    fecharModalCriar();
    mostrarToast('Pedido criado com sucesso!');
}

function mostrarToast(mensagem) {
    const toast = document.getElementById('toast');
    toast.textContent = mensagem;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2800);
}

// Fecha ao clicar fora
document.getElementById('modalCriarPedido').addEventListener('click', function(e) {
    if (e.target === this) fecharModalCriar();
});

/* ========== INICIALIZAÇÃO ========== */
atualizarResumo();
renderizarTabela();

