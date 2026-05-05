/* ========== DADOS DE EXEMPLO ========== */
const clientes = [
    {
        id: 1,
        razaoSocial: 'Ana Souza',
        tipo: 'pf',
        documento: '123.456.789-00',
        email: 'ana.souza@email.com',
        telefone: '(11) 98765-4321',
        cep: '01310-100',
        endereco: 'Av. Paulista, 1000 - São Paulo/SP',
        observacao: 'Cliente VIP, preferência por entrega expressa.',
        historico: [
            {
                id: '003', data: '30/04/2026', total: 149.90,
                status: 'entregue',
                produtos: [{ nome: 'Mouse Gamer RGB', qtd: 1, preco: 149.90 }]
            },
            {
                id: '009', data: '10/03/2026', total: 349.80,
                status: 'entregue',
                produtos: [{ nome: 'Tênis Air Max Pro', qtd: 2, preco: 174.90 }]
            }
        ]
    },
    {
        id: 2,
        razaoSocial: 'Tech Solutions Ltda',
        tipo: 'pj',
        documento: '12.345.678/0001-99',
        email: 'contato@techsolutions.com.br',
        telefone: '(11) 3333-4444',
        cep: '04538-133',
        endereco: 'Rua Funchal, 418 - São Paulo/SP',
        observacao: 'Pagamento via boleto. Prazo de 30 dias.',
        historico: [
            {
                id: '002', data: '01/05/2026', total: 899.00,
                status: 'andamento',
                produtos: [
                    { nome: 'Smartwatch Series X', qtd: 1, preco: 599.00 },
                    { nome: 'Fone Bluetooth', qtd: 1, preco: 300.00 }
                ]
            }
        ]
    },
    {
        id: 3,
        razaoSocial: 'Carlos Melo',
        tipo: 'pf',
        documento: '987.654.321-00',
        email: 'carlos.melo@gmail.com',
        telefone: '(21) 99887-6655',
        cep: '20040-020',
        endereco: 'Av. Rio Branco, 156 - Rio de Janeiro/RJ',
        observacao: '',
        historico: [
            {
                id: '006', data: '27/04/2026', total: 659.80,
                status: 'andamento',
                produtos: [
                    { nome: 'Monitor 24"', qtd: 1, preco: 459.90 },
                    { nome: 'Cabo HDMI 2m', qtd: 2, preco: 99.95 }
                ]
            }
        ]
    },
    {
        id: 4,
        razaoSocial: 'Fernanda Lima',
        tipo: 'pf',
        documento: '456.789.123-00',
        email: 'fernanda.lima@email.com',
        telefone: '(31) 97654-3210',
        cep: '30112-010',
        endereco: 'Av. Afonso Pena, 500 - Belo Horizonte/MG',
        observacao: 'Prefere contato por e-mail.',
        historico: []
    },
    {
        id: 5,
        razaoSocial: 'Distribuidora Norte S/A',
        tipo: 'pj',
        documento: '98.765.432/0001-10',
        email: 'compras@distrnorte.com.br',
        telefone: '(92) 3210-9876',
        cep: '69050-010',
        endereco: 'Av. Djalma Batista, 1200 - Manaus/AM',
        observacao: 'Pedidos acima de R$ 500 têm frete grátis.',
        historico: [
            {
                id: '004', data: '29/04/2026', total: 2399.00,
                status: 'cancelado',
                produtos: [{ nome: 'Notebook Ultra Slim', qtd: 1, preco: 2399.00 }]
            },
            {
                id: '005', data: '28/04/2026', total: 299.70,
                status: 'pendente',
                produtos: [{ nome: 'Teclado Mecânico', qtd: 3, preco: 99.90 }]
            }
        ]
    }
];

/* ========== ESTADO ========== */
let buscaAtual       = '';
let clienteParaDeletar = null;

/* ========== LABELS ========== */
const labelStatus = {
    pendente:  'Pendente',
    andamento: 'Em andamento',
    entregue:  'Entregue',
    cancelado: 'Cancelado'
};

const corStatus = {
    pendente:  '#f97316',
    andamento: '#3b82f6',
    entregue:  '#22c55e',
    cancelado: '#ef4444'
};

/* ========== UTILITÁRIOS ========== */
function iniciais(nome) {
    return nome.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();
}

function formatarMoeda(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

/* ========== RENDERIZAR TABELA ========== */
function renderizarTabela() {
    const corpo        = document.getElementById('corpoTabela');
    const semResultados = document.getElementById('semResultados');

    const lista = clientes.filter(c =>
        c.razaoSocial.toLowerCase().includes(buscaAtual) ||
        c.documento.includes(buscaAtual) ||
        c.email.toLowerCase().includes(buscaAtual)
    );

    document.getElementById('contador-clientes').textContent =
        `${lista.length} cliente${lista.length !== 1 ? 's' : ''}`;

    atualizarResumo(lista);

    if (lista.length === 0) {
        corpo.innerHTML = '';
        semResultados.style.display = 'block';
        return;
    }

    semResultados.style.display = 'none';

    corpo.innerHTML = lista.map(c => `
        <tr>
            <td>
                <div class="celula-cliente">
                    <div class="avatar-cliente">${iniciais(c.razaoSocial)}</div>
                    <div>
                        <div class="nome-cliente">${c.razaoSocial}</div>
                        <div class="doc-cliente">${c.documento}</div>
                    </div>
                </div>
            </td>
            <td><span class="badge-tipo tipo-${c.tipo}">${c.tipo === 'pf' ? 'Pessoa Física' : 'Pessoa Jurídica'}</span></td>
            <td>${c.email}</td>
            <td>${c.telefone}</td>
            <td>${c.historico.length} pedido${c.historico.length !== 1 ? 's' : ''}</td>
            <td>
                <div class="acoes-tabela">
                    <button class="btn-acao btn-ver" onclick="abrirModalVer(${c.id})" title="Ver cadastro">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <button class="btn-acao btn-historico" onclick="abrirModalHistorico(${c.id})" title="Histórico de compras">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                    <button class="btn-acao btn-deletar" onclick="abrirModalDeletar(${c.id})" title="Deletar cliente">
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
    `).join('');
}

/* ========== RESUMO ========== */
function atualizarResumo(lista) {
    const total = lista.length;
    const pf    = lista.filter(c => c.tipo === 'pf').length;
    const pj    = lista.filter(c => c.tipo === 'pj').length;
    const comPedidos = lista.filter(c => c.historico.length > 0).length;

    document.getElementById('resumo-total').textContent      = total;
    document.getElementById('resumo-pf').textContent         = pf;
    document.getElementById('resumo-pj').textContent         = pj;
    document.getElementById('resumo-compras').textContent    = comPedidos;
}

/* ========== BUSCAR ========== */
function filtrarTabela() {
    buscaAtual = document.getElementById('campoBusca').value.toLowerCase();
    renderizarTabela();
}

/* ========== MODAL CADASTRO / VER ========== */
function abrirModalCadastro() {
    limparFormulario();
    document.getElementById('modalCadastroTitulo').textContent = 'Novo Cliente';
    document.getElementById('modalCadastro').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

function abrirModalVer(id) {
    const c = clientes.find(c => c.id === id);
    if (!c) return;

    document.getElementById('modalCadastroTitulo').textContent = 'Dados do Cliente';
    document.getElementById('campo-razao').value      = c.razaoSocial;
    document.getElementById('campo-tipo').value       = c.tipo;
    document.getElementById('campo-documento').value  = c.documento;
    document.getElementById('campo-email').value      = c.email;
    document.getElementById('campo-telefone').value   = c.telefone;
    document.getElementById('campo-cep').value        = c.cep;
    document.getElementById('campo-endereco').value   = c.endereco;
    document.getElementById('campo-obs').value        = c.observacao;

    document.getElementById('modalCadastro').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

function fecharModalCadastro() {
    document.getElementById('modalCadastro').classList.remove('aberto');
    document.body.style.overflow = '';
}

function limparFormulario() {
    ['campo-razao','campo-tipo','campo-documento','campo-email',
     'campo-telefone','campo-cep','campo-endereco','campo-obs']
    .forEach(id => document.getElementById(id).value = '');
}

function salvarCliente() {
    // Futuramente: envio ao PHP/banco de dados
    fecharModalCadastro();
    mostrarToast('Cliente salvo com sucesso!', '#22c55e');
}

/* ========== MODAL HISTÓRICO ========== */
function abrirModalHistorico(id) {
    const c = clientes.find(c => c.id === id);
    if (!c) return;

    document.getElementById('historico-avatar-texto').textContent = iniciais(c.razaoSocial);
    document.getElementById('historico-nome').textContent         = c.razaoSocial;
    document.getElementById('historico-doc').textContent          = c.documento;

    // Stats
    const totalPedidos = c.historico.length;
    const totalGasto   = c.historico.reduce((s, p) => s + p.total, 0);
    const ultimaCompra = totalPedidos > 0 ? c.historico[0].data : '—';

    document.getElementById('stat-pedidos').textContent    = totalPedidos;
    document.getElementById('stat-total-gasto').textContent = formatarMoeda(totalGasto);
    document.getElementById('stat-ultima').textContent     = ultimaCompra;

    // Lista de pedidos
    const lista = document.getElementById('listaHistorico');

    if (c.historico.length === 0) {
        lista.innerHTML = `
            <div style="text-align:center;padding:30px;color:var(--muted);font-size:14px;font-weight:700;">
                Nenhuma compra registrada ainda.
            </div>`;
    } else {
        lista.innerHTML = c.historico.map(p => `
            <article class="item-historico">
                <div class="item-historico-topo">
                    <span class="item-historico-id">Pedido #${p.id}</span>
                    <span class="item-historico-data">${p.data}</span>
                </div>
                <div class="item-historico-produtos">
                    ${p.produtos.map(i => `
                        <div class="item-historico-produto">
                            ${i.nome} × ${i.qtd} — ${formatarMoeda(i.preco)}
                        </div>
                    `).join('')}
                </div>
                <div class="item-historico-rodape">
                    <span class="badge-status" style="background:${corStatus[p.status]}18;color:${corStatus[p.status]};display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                        ${labelStatus[p.status]}
                    </span>
                    <span class="item-historico-total">${formatarMoeda(p.total)}</span>
                </div>
            </article>
        `).join('');
    }

    document.getElementById('modalHistorico').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

function fecharModalHistorico() {
    document.getElementById('modalHistorico').classList.remove('aberto');
    document.body.style.overflow = '';
}

/* ========== MODAL DELETAR ========== */
function abrirModalDeletar(id) {
    const c = clientes.find(c => c.id === id);
    if (!c) return;

    clienteParaDeletar = id;
    document.getElementById('confirmar-nome').textContent = c.razaoSocial;
    document.getElementById('modalDeletar').classList.add('aberto');
    document.body.style.overflow = 'hidden';
}

function fecharModalDeletar() {
    document.getElementById('modalDeletar').classList.remove('aberto');
    document.body.style.overflow = '';
    clienteParaDeletar = null;
}

function confirmarDeletar() {
    if (clienteParaDeletar === null) return;

    const idx = clientes.findIndex(c => c.id === clienteParaDeletar);
    if (idx !== -1) clientes.splice(idx, 1);

    fecharModalDeletar();
    renderizarTabela();
    mostrarToast('Cliente removido com sucesso!', '#ef4444');
}

/* ========== TOAST ========== */
function mostrarToast(mensagem, cor = '#22c55e') {
    const toast = document.getElementById('toast');
    toast.textContent = mensagem;
    toast.style.background = cor;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2800);
}

/* ========== FECHAR AO CLICAR FORA ========== */
['modalCadastro', 'modalHistorico', 'modalDeletar'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            if (id === 'modalCadastro')  fecharModalCadastro();
            if (id === 'modalHistorico') fecharModalHistorico();
            if (id === 'modalDeletar')   fecharModalDeletar();
        }
    });
});

/* ========== ESC FECHA MODAIS ========== */
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    fecharModalCadastro();
    fecharModalHistorico();
    fecharModalDeletar();
});

/* ========== INIT ========== */
renderizarTabela();