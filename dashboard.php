<?php require_once "auth.php"; ?>
<?php

require_once "model/Dashboard.php";

$dash          = new Dashboard();
$resumo        = $dash->resumo();
$pedidosMes    = $dash->pedidosPorMes();
$faturamentoMes= $dash->faturamentoPorMes();
$estoqueBaixo  = $dash->estoqueBaixo();
$pedidosStatus = $dash->pedidosPorStatus();
$topProdutos   = $dash->topProdutos();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/modalSair.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include("components/sidebar.php"); ?>
<?php include("components/modalSair.php"); ?>

<main class="principal">

    <!-- Topbar -->
    <header class="topbar">
        <div>
            <h1>Dashboard</h1>
            <p class="topbar-sub">Bem-vindo de volta! Aqui está o resumo do sistema.</p>
        </div>
        <span class="data-hoje"><?= date('d/m/Y') ?></span>
    </header>

    <!-- Cards de resumo -->
    <section class="resumo-grid" aria-label="Resumo geral">


                <article class="resumo-card resumo-card-destaque">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:rgba(255,255,255,.2)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>
                <span class="resumo-rotulo" style="color:rgba(255,255,255,.8)">Faturamento</span>
            </div>
            <span class="resumo-numero">R$ <?= number_format($resumo['faturamento_total'], 2, ',', '.') ?></span>
            <span class="resumo-link" style="color:rgba(255,255,255,.7)">Pedidos entregues</span>
        </article>

        <article class="resumo-card">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:#eef0fd">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#6c7ef8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <span class="resumo-rotulo">Clientes</span>
            </div>
            <span class="resumo-numero"><?= $resumo['total_clientes'] ?></span>
            <a href="clientes.php" class="resumo-link">Ver todos →</a>
        </article>

        <article class="resumo-card">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:#fff7ed">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <span class="resumo-rotulo">Produtos</span>
            </div>
            <span class="resumo-numero"><?= $resumo['total_produtos'] ?></span>
            <a href="produtos.php" class="resumo-link">Ver todos →</a>
        </article>

        <article class="resumo-card">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:#f0fdf4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                </div>
                <span class="resumo-rotulo">Pedidos</span>
            </div>
            <span class="resumo-numero"><?= $resumo['total_pedidos'] ?></span>
            <a href="pedidos.php" class="resumo-link">Ver todos →</a>
        </article>



        <article class="resumo-card">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:#fff7ed">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <span class="resumo-rotulo">Pendentes</span>
            </div>
            <span class="resumo-numero"><?= $resumo['pedidos_pendentes'] ?></span>
            <a href="pedidos.php" class="resumo-link">Ver pedidos →</a>
        </article>

        <article class="resumo-card <?= $resumo['estoque_baixo'] > 0 ? 'resumo-card-alerta' : '' ?>">
            <div class="resumo-card-topo">
                <div class="resumo-icone" style="background:#fff1f2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <span class="resumo-rotulo">Estoque Baixo</span>
            </div>
            <span class="resumo-numero"><?= $resumo['estoque_baixo'] ?></span>
            <a href="produtos.php" class="resumo-link">Ver produtos →</a>
        </article>

    </section>

    <!-- Gráficos -->
    <section class="graficos-grid" aria-label="Gráficos">

        <div class="grafico-card">
            <div class="grafico-cabecalho">
                <h2>Pedidos por Mês</h2>
                <span>Últimos 6 meses</span>
            </div>
            <canvas id="graficoPedidos" height="100"></canvas>
        </div>

        <div class="grafico-card">
            <div class="grafico-cabecalho">
                <h2>Faturamento por Mês</h2>
                <span>Últimos 6 meses</span>
            </div>
            <canvas id="graficoFaturamento" height="100"></canvas>
        </div>

    </section>

    <!-- Linha inferior: status + top produtos + estoque baixo -->
    <section class="inferior-grid" aria-label="Detalhes">

        <!-- Pedidos por status -->
        <div class="grafico-card">
            <div class="grafico-cabecalho">
                <h2>Pedidos por Status</h2>
            </div>
            <div class="status-lista">
                <?php foreach ($pedidosStatus as $s): ?>
                    <?php
                        $cores = [
                            'Pendente'     => ['bg' => '#fff7ed', 'cor' => '#f97316'],
                            'Em andamento' => ['bg' => '#eff6ff', 'cor' => '#3b82f6'],
                            'Entregue'     => ['bg' => '#f0fdf4', 'cor' => '#22c55e'],
                            'Cancelado'    => ['bg' => '#fff1f2', 'cor' => '#ef4444'],
                        ];
                        $c = $cores[$s['nome_status']] ?? ['bg' => '#f0f3fb', 'cor' => '#6c7ef8'];
                        $pct = $resumo['total_pedidos'] > 0
                            ? round(($s['total'] / $resumo['total_pedidos']) * 100)
                            : 0;
                    ?>
                    <div class="status-item">
                        <div class="status-item-esquerda">
                            <span class="status-bolinha" style="background:<?= $c['cor'] ?>"></span>
                            <span class="status-nome"><?= $s['nome_status'] ?></span>
                        </div>
                        <div class="status-item-direita">
                            <div class="status-barra-fundo">
                                <div class="status-barra-preenchimento"
                                    style="width:<?= $pct ?>%;background:<?= $c['cor'] ?>"></div>
                            </div>
                            <span class="status-total" style="color:<?= $c['cor'] ?>"><?= $s['total'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top 5 produtos -->
        <div class="grafico-card">
            <div class="grafico-cabecalho">
                <h2>Top Produtos Vendidos</h2>
                <span>Excluindo cancelados</span>
            </div>
            <?php if (empty($topProdutos)): ?>
                <p style="color:#7a85a3;font-weight:700;padding:20px;text-align:center;">Nenhuma venda registrada.</p>
            <?php else: ?>
                <div class="top-produtos-lista">
                    <?php foreach ($topProdutos as $i => $p): ?>
                        <div class="top-produto-item">
                            <span class="top-produto-rank"><?= $i + 1 ?></span>
                            <img src="<?= $p['imagem_produto'] ? 'img/' . $p['imagem_produto'] : 'assets/img/sem-imagem.png' ?>"
                                alt="<?= htmlspecialchars($p['nome_produto']) ?>">
                            <div class="top-produto-info">
                                <span class="top-produto-nome"><?= htmlspecialchars($p['nome_produto']) ?></span>
                                <span class="top-produto-qtd"><?= $p['total_vendido'] ?> vendido<?= $p['total_vendido'] != 1 ? 's' : '' ?></span>
                            </div>
                            <span class="top-produto-total">
                                R$ <?= number_format($p['total_faturado'], 2, ',', '.') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Estoque baixo -->
        <div class="grafico-card">
            <div class="grafico-cabecalho">
                <h2>Estoque Baixo</h2>
                <span>≤ 10 unidades</span>
            </div>
            <?php if (empty($estoqueBaixo)): ?>
                <p style="color:#22c55e;font-weight:700;padding:20px;text-align:center;">✓ Estoque em dia!</p>
            <?php else: ?>
                <div class="estoque-lista">
                    <?php foreach ($estoqueBaixo as $p): ?>
                        <div class="estoque-item">
                            <img src="<?= $p['imagem_produto'] ? 'img/' . $p['imagem_produto'] : 'assets/img/sem-imagem.png' ?>"
                                alt="<?= htmlspecialchars($p['nome_produto']) ?>">
                            <div class="estoque-info">
                                <span class="estoque-nome"><?= htmlspecialchars($p['nome_produto']) ?></span>
                                <span class="estoque-categoria"><?= htmlspecialchars($p['nome_categoria'] ?? '—') ?></span>
                            </div>
                            <span class="estoque-qtd <?= $p['estoque_produto'] == 0 ? 'estoque-zero' : 'estoque-baixo' ?>">
                                <?= $p['estoque_produto'] ?> un.
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </section>

</main>

<script src="./js/main.js"></script>
<script>

    // ── Gráfico de Pedidos por Mês (linha)
    const pedidosMes    = <?= json_encode(array_column($pedidosMes, 'mes')) ?>;
    const pedidosTotal  = <?= json_encode(array_column($pedidosMes, 'total')) ?>;

    new Chart(document.getElementById('graficoPedidos'), {
        type: 'line',
        data: {
            labels: pedidosMes,
            datasets: [{
                label: 'Pedidos',
                data: pedidosTotal,
                borderColor: '#6c7ef8',
                backgroundColor: 'rgba(108,126,248,.12)',
                borderWidth: 2.5,
                pointBackgroundColor: '#6c7ef8',
                pointRadius: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { family: 'Nunito', weight: '700' } },
                    grid: { color: '#f0f3fb' }
                },
                x: {
                    ticks: { font: { family: 'Nunito', weight: '700' } },
                    grid: { display: false }
                }
            }
        }
    });

    // ── Gráfico de Faturamento por Mês (barra)
    const faturamentoMes   = <?= json_encode(array_column($faturamentoMes, 'mes')) ?>;
    const faturamentoTotal = <?= json_encode(array_column($faturamentoMes, 'total')) ?>;

    new Chart(document.getElementById('graficoFaturamento'), {
        type: 'bar',
        data: {
            labels: faturamentoMes,
            datasets: [{
                label: 'Faturamento (R$)',
                data: faturamentoTotal,
                backgroundColor: 'rgba(34,197,94,.20)',
                borderColor: '#22c55e',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { family: 'Nunito', weight: '700' },
                        callback: v => 'R$ ' + v.toLocaleString('pt-BR')
                    },
                    grid: { color: '#f0f3fb' }
                },
                x: {
                    ticks: { font: { family: 'Nunito', weight: '700' } },
                    grid: { display: false }
                }
            }
        }
    });

</script>

</body>
</html>