<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNFit Performance | Plataforma de Gestão Integrada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #101f3a;
            --bg-card: rgba(15, 23, 42, 0.75);
            --bg-card-alt: rgba(30, 41, 59, 0.95);
            --accent: #38bdf8;
            --accent-strong: #0ea5e9;
            --accent-light: rgba(56, 189, 248, 0.18);
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
            --success: #22c55e;
            --warning: #fbbf24;
            --danger: #ef4444;
            --shadow-lg: 0 30px 60px rgba(15, 23, 42, 0.55);
            --radius: 18px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, rgba(56, 189, 248, 0.15), transparent 45%),
                        radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.12), transparent 40%),
                        var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(16px);
            background: rgba(15, 23, 42, 0.65);
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        }

        .navbar {
            margin: 0 auto;
            max-width: 1200px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-weight: 700;
            letter-spacing: 0.04em;
            font-size: 1.1rem;
            color: #f8fafc;
        }

        .brand span {
            color: var(--accent);
        }

        nav ul {
            display: flex;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav a {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        nav a:hover {
            color: #f8fafc;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #0f172a;
            box-shadow: 0 12px 24px rgba(14, 165, 233, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(14, 165, 233, 0.4);
        }

        .btn-outline {
            border-color: rgba(148, 163, 184, 0.2);
            color: #f8fafc;
            background: transparent;
        }

        .btn-outline:hover {
            border-color: rgba(148, 163, 184, 0.4);
            transform: translateY(-2px);
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px 96px;
        }

        section {
            margin-top: 96px;
        }

        .hero {
            margin-top: 72px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 48px;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3rem;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -0.03em;
        }

        .hero-content p {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .hero-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            background: rgba(56, 189, 248, 0.16);
            border-radius: 999px;
            font-size: 0.9rem;
            color: #f0f9ff;
        }

        .hero-dashboard {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.86));
            border-radius: 26px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(148, 163, 184, 0.12);
            position: relative;
            overflow: hidden;
        }

        .hero-dashboard::after {
            content: '';
            position: absolute;
            inset: -120px auto auto -120px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.25), transparent 65%);
            z-index: 0;
        }

        .dashboard-metric-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 18px;
        }

        .metric-card {
            background: rgba(15, 23, 42, 0.6);
            border-radius: 20px;
            padding: 18px;
            border: 1px solid rgba(148, 163, 184, 0.12);
        }

        .metric-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .metric-sub {
            font-size: 0.85rem;
            color: rgba(148, 163, 184, 0.75);
            margin-top: 8px;
        }

        .metric-trend {
            margin-top: 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        .section-title {
            font-size: 2.2rem;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .section-description {
            font-size: 1rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            max-width: 660px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1.4fr;
            gap: 24px;
        }

        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 28px;
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.35);
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 1.25rem;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 12px;
        }

        .order-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-id {
            font-weight: 600;
        }

        .order-client {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .order-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-end;
            text-align: right;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .status-pending { background: rgba(250, 204, 21, 0.18); color: #facc15; }
        .status-paid { background: rgba(52, 211, 153, 0.18); color: #34d399; }
        .status-processing { background: rgba(96, 165, 250, 0.18); color: #60a5fa; }
        .status-shipped { background: rgba(96, 165, 250, 0.18); color: #60a5fa; }
        .status-delivered { background: rgba(34, 197, 94, 0.18); color: #22c55e; }
        .status-cancelled { background: rgba(239, 68, 68, 0.18); color: #ef4444; }

        .order-value {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .order-date {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .inventory-card {
            background: rgba(15, 23, 42, 0.55);
            padding: 18px;
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .inventory-card span {
            display: block;
        }

        .inventory-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .inventory-value {
            font-size: 1.4rem;
            font-weight: 600;
        }

        .status-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .status-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .status-bar {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.15);
            overflow: hidden;
        }

        .status-bar span {
            display: block;
            height: 100%;
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.9), rgba(14, 165, 233, 0.9));
        }

        .stack-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .stack-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 24px;
            border: 1px solid rgba(148, 163, 184, 0.12);
        }

        .stack-card h4 {
            margin: 0 0 12px;
            font-size: 1.1rem;
        }

        .stack-card p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .module-card {
            padding: 24px;
            border-radius: var(--radius);
            background: linear-gradient(160deg, var(--bg-card), rgba(30, 41, 59, 0.88));
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.35);
        }

        .module-card h4 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }

        .module-card ul {
            margin: 0;
            padding-left: 16px;
            color: var(--text-muted);
            font-size: 0.95rem;
            display: grid;
            gap: 10px;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .highlight {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .highlight strong {
            font-size: 2rem;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.35), transparent);
            margin: 80px 0;
        }

        .cta {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.16), rgba(14, 165, 233, 0.12));
            border-radius: 28px;
            padding: 48px;
            text-align: center;
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: var(--shadow-lg);
        }

        .cta h2 {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 2.1rem;
        }

        .cta p {
            margin: 0 auto 28px;
            max-width: 620px;
            color: var(--text-muted);
            font-size: 1rem;
        }

        footer {
            margin-top: 96px;
            padding: 32px 24px;
            border-top: 1px solid rgba(148, 163, 184, 0.10);
            background: rgba(15, 23, 42, 0.75);
        }

        footer .inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 18px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 960px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .order-row {
                grid-template-columns: 1fr;
            }

            .order-meta {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            nav {
                display: none;
            }
        }

        @media (max-width: 600px) {
            main {
                padding: 0 18px 64px;
            }

            .hero {
                margin-top: 48px;
            }

            .hero-content h1 {
                font-size: 2.3rem;
            }

            .navbar {
                padding: 16px 18px;
            }

            .hero-dashboard {
                padding: 24px;
            }

            section {
                margin-top: 72px;
            }
        }
    </style>
</head>
<body>
    @php
        $formatCurrency = fn ($value) => 'R$ ' . number_format((float) $value, 2, ',', '.');
        $statusLabels = [
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'processing' => 'Em processamento',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];
        $statusCss = [
            'pending' => 'status-pending',
            'paid' => 'status-paid',
            'processing' => 'status-processing',
            'shipped' => 'status-shipped',
            'delivered' => 'status-delivered',
            'cancelled' => 'status-cancelled',
        ];
        $orderStatusTotal = array_sum($orderStatus['values'] ?? []);
        $maxProductRevenue = max($topProducts->map(fn ($item) => (float) $item->total_revenue)->all() ?: [1]);
    @endphp

    <header>
        <div class="navbar">
            <a class="brand" href="#top">CNFit <span>Performance</span></a>
            <nav>
                <ul>
                    <li><a href="#gestao">Gestão integrada</a></li>
                    <li><a href="#modulos">Módulos</a></li>
                    <li><a href="#analytics">Performance</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
            </nav>
            <div class="actions">
                <a class="btn btn-outline" href="mailto:contato@cnfitperformance.com">Agendar demo</a>
                <a class="btn btn-primary" href="{{ route('filament.admin.auth.login') }}">Acessar painel</a>
            </div>
        </div>
    </header>

    <main id="top">
        <section class="hero">
            <div class="hero-content">
                <h1>Landing page poderosa com sistema de gestão integrado</h1>
                <p>Centralize vendas, finanças, estoque e relacionamento com clientes em uma única plataforma. Automatize a operação, acompanhe resultados em tempo real e ofereça uma experiência impecável do pedido à entrega.</p>
                <div class="actions">
                    <a class="btn btn-primary" href="{{ route('filament.admin.auth.login') }}">Comece agora</a>
                    <a class="btn btn-outline" href="#gestao">Ver dashboards</a>
                </div>
                <div class="hero-badges">
                    <span class="hero-badge">✓ Automação completa de pedidos</span>
                    <span class="hero-badge">✓ Estoque inteligente com alertas</span>
                    <span class="hero-badge">✓ Relatórios financeiros consolidados</span>
                </div>
            </div>
            <aside class="hero-dashboard">
                <div class="dashboard-metric-grid">
                    <div class="metric-card">
                        <span class="metric-label">Pedidos processados</span>
                        <span class="metric-value">{{ $metrics['totalOrders'] }}</span>
                        <span class="metric-sub">Gestão ponta a ponta do ciclo de venda</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-label">Receita consolidada</span>
                        <span class="metric-value">{{ $formatCurrency($metrics['totalRevenue']) }}</span>
                        <span class="metric-trend trend-up">▲ Conversão média {{ number_format($metrics['conversionRate'], 1, ',', '.') }}%</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-label">Clientes ativos</span>
                        <span class="metric-value">{{ $metrics['activeClients'] }}</span>
                        <span class="metric-sub">Engajamento contínuo no ecossistema CNFit</span>
                    </div>
                    <div class="metric-card">
                        <span class="metric-label">Ticket médio</span>
                        <span class="metric-value">{{ $formatCurrency($metrics['averageTicket']) }}</span>
                        <span class="metric-sub">Pedidos em andamento: {{ $metrics['ordersInProgress'] }}</span>
                    </div>
                </div>
            </aside>
        </section>

        <div class="divider"></div>

        <section id="gestao">
            <h2 class="section-title">Dashboard operacional em tempo real</h2>
            <p class="section-description">Tenha visibilidade total do fluxo de vendas, acompanhe indicadores-chave por status de pedido, monitore saúde do estoque e projete receita com previsibilidade. O painel unifica informações do e-commerce e do backoffice CNFit Performance.</p>

            <div class="dashboard-grid">
                <div class="card">
                    <h3>Receita recorrente</h3>
                    <p class="section-description">Acompanhe a evolução da receita mensal com projeções atualizadas automaticamente a partir dos pedidos confirmados.</p>
                    <canvas id="revenueChart" height="220"></canvas>
                </div>

                <div class="card">
                    <h3>Status dos pedidos</h3>
                    <div class="status-list">
                        @forelse(($orderStatus['labels'] ?? []) as $index => $label)
                            @php
                                $value = $orderStatus['values'][$index] ?? 0;
                                $percent = $orderStatusTotal > 0 ? round(($value / $orderStatusTotal) * 100) : 0;
                            @endphp
                            <div class="status-item">
                                <div class="status-header">
                                    <span>{{ $label }}</span>
                                    <span>{{ $value }} pedidos ({{ $percent }}%)</span>
                                </div>
                                <div class="status-bar">
                                    <span style="width: {{ $percent }}%;"></span>
                                </div>
                            </div>
                        @empty
                            <p class="section-description">Ainda não há pedidos cadastrados para exibir.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="dashboard-grid" style="margin-top: 28px;">
                <div class="card">
                    <h3>Pedidos recentes</h3>
                    <div class="orders-list">
                        @forelse($recentOrders as $order)
                            @php
                                $clientName = optional($order->userClient?->user)->name ?? 'Cliente sem cadastro';
                                $statusKey = $order->status;
                                $statusClass = $statusCss[$statusKey] ?? 'status-processing';
                                $statusLabel = $statusLabels[$statusKey] ?? ucfirst($statusKey);
                            @endphp
                            <div class="order-row">
                                <div class="order-info">
                                    <span class="order-id">Pedido #{{ $order->id }}</span>
                                    <span class="order-client">{{ $clientName }}</span>
                                </div>
                                <div class="order-meta">
                                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                    <span class="order-value">{{ $formatCurrency($order->total_amount) }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="section-description">Nenhum pedido foi registrado ainda. Assim que novas vendas chegarem, elas aparecem aqui.</p>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <h3>Saúde do estoque</h3>
                    <p class="section-description">Controle níveis de inventário, alertas de ruptura e ticket médio em um único painel.</p>
                    <div class="inventory-grid">
                        <div class="inventory-card">
                            <span class="inventory-label">Produtos ativos</span>
                            <span class="inventory-value">{{ $inventory['totalProducts'] }}</span>
                        </div>
                        <div class="inventory-card">
                            <span class="inventory-label">Baixo estoque</span>
                            <span class="inventory-value">{{ $inventory['lowStock'] }}</span>
                        </div>
                        <div class="inventory-card">
                            <span class="inventory-label">Sem estoque</span>
                            <span class="inventory-value">{{ $inventory['outOfStock'] }}</span>
                        </div>
                        <div class="inventory-card">
                            <span class="inventory-label">Preço médio</span>
                            <span class="inventory-value">{{ $formatCurrency($inventory['averagePrice']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section id="analytics">
            <h2 class="section-title">Insights prontos para decisões estratégicas</h2>
            <p class="section-description">Identifique produtos campeões, entenda o giro por categoria e antecipe demandas com relatórios que mostram o retrato real do seu negócio.</p>

            <div class="analytics-grid">
                <div class="card">
                    <h3>Top produtos</h3>
                    <div class="status-list">
                        @forelse($topProducts as $item)
                            @php
                                $product = $item->product;
                                $name = $product?->name ?? 'Produto removido';
                                $revenue = (float) $item->total_revenue;
                                $quantity = (int) $item->total_quantity;
                                $percent = $maxProductRevenue > 0 ? round(($revenue / $maxProductRevenue) * 100) : 0;
                            @endphp
                            <div class="status-item">
                                <div class="status-header">
                                    <span>{{ $name }}</span>
                                    <span>{{ $quantity }} un · {{ $formatCurrency($revenue) }}</span>
                                </div>
                                <div class="status-bar">
                                    <span style="width: {{ $percent }}%;"></span>
                                </div>
                            </div>
                        @empty
                            <p class="section-description">Não há vendas suficientes para gerar o ranking de produtos.</p>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <h3>Performance por categoria</h3>
                    <div class="status-list">
                        @forelse($topCategories as $category)
                            @php
                                $stock = (int) $category->stock_sum;
                                $productsCount = (int) $category->products_count;
                            @endphp
                            <div class="status-item">
                                <div class="status-header">
                                    <span>{{ $category->name }}</span>
                                    <span>{{ $productsCount }} produtos</span>
                                </div>
                                <p class="section-description" style="margin: 0;">Estoque disponível: {{ $stock }} unidades</p>
                            </div>
                        @empty
                            <p class="section-description">Cadastre categorias para visualizar sua composição de portfólio.</p>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <h3>Experiência omnichannel</h3>
                    <div class="highlight">
                        <strong>+45%</strong>
                        <p class="section-description">Clientes CNFit que combinam loja online, academias e consultores têm aumento médio de 45% na retenção, graças ao conjunto de automações, CRM de leads e fluxos de cobrança integrados.</p>
                        <a class="btn btn-primary" href="mailto:contato@cnfitperformance.com">Quero replicar esse resultado</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section id="modulos">
            <h2 class="section-title">Todos os módulos que você precisa na mesma plataforma</h2>
            <p class="section-description">O sistema de gestão integrado CNFit Performance conecta marketing, vendas, operações e financeiro em uma jornada única. Veja como cada módulo trabalha junto com a sua landing page de alta conversão.</p>

            <div class="modules-grid">
                <div class="module-card">
                    <h4>CRM & relacionamento</h4>
                    <ul>
                        <li>Pipeline de leads com status configuráveis e follow-up automático.</li>
                        <li>Histórico unificado de pedidos, tickets e pagamentos por cliente.</li>
                        <li>Automação de nutrição e upsell integrada à landing page.</li>
                    </ul>
                </div>
                <div class="module-card">
                    <h4>Gestão de vendas</h4>
                    <ul>
                        <li>Checkout otimizado com múltiplos meios de pagamento (crypto, Zelle, cartão).</li>
                        <li>Monitoramento de pedidos em tempo real com notificações inteligentes.</li>
                        <li>Controle de cupons, promoções e banners dinâmicos pela equipe de marketing.</li>
                    </ul>
                </div>
                <div class="module-card">
                    <h4>Operações & logística</h4>
                    <ul>
                        <li>Controle de estoque com alertas de ruptura e previsão de reposição.</li>
                        <li>Tracking integrado e comunicação automatizada de status ao cliente.</li>
                        <li>Dashboard de fulfillment com indicadores de SLA e prioridades.</li>
                    </ul>
                </div>
                <div class="module-card">
                    <h4>Finanças & auditoria</h4>
                    <ul>
                        <li>Relatórios de receita, ticket médio e DRE simplificada.</li>
                        <li>Geração de faturas e recibos com envio automático por e-mail.</li>
                        <li>Conciliação de pagamentos e logs detalhados para auditoria.</li>
                    </ul>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section id="contato">
            <div class="cta">
                <h2>Pronto para transformar sua operação digital?</h2>
                <p>Unifique marketing, vendas, logística e financeiro em um único ecossistema. Nossa equipe acompanha cada etapa da implantação para que você tenha uma landing page de alta performance totalmente conectada ao sistema de gestão.</p>
                <div class="actions" style="justify-content: center;">
                    <a class="btn btn-primary" href="mailto:contato@cnfitperformance.com">Falar com especialista</a>
                    <a class="btn btn-outline" href="{{ route('filament.admin.auth.login') }}">Acessar painel administrativo</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="inner">
            <span>© {{ date('Y') }} CNFit Performance. Gestão integrada para operações fitness e e-commerce.</span>
            <span>Plataforma conectada ao painel administrativo Filament em /admin.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" integrity="sha384-RPyd4InENcy+XUG6uHgnIY7Mkgkm/taVZHcd1fDYWKSmW9jl+1DdRNUQXFXMZk13" crossorigin="anonymous"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const revenueData = @json($revenueTrend);
            const chartColors = {
                background: 'rgba(56, 189, 248, 0.25)',
                border: 'rgba(56, 189, 248, 0.9)',
                grid: 'rgba(148, 163, 184, 0.12)',
                ticks: '#94a3b8'
            };

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueData.labels,
                    datasets: [{
                        label: 'Receita',
                        data: revenueData.values,
                        fill: true,
                        tension: 0.45,
                        backgroundColor: chartColors.background,
                        borderColor: chartColors.border,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: chartColors.border,
                        pointBorderColor: chartColors.border,
                        pointHoverRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context => {
                                    const formatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
                                    return formatter.format(context.parsed.y || 0);
                                }
                            },
                            displayColors: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            borderColor: 'rgba(56, 189, 248, 0.35)',
                            borderWidth: 1,
                            padding: 12,
                            titleColor: '#e2e8f0',
                            bodyColor: '#f8fafc',
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: chartColors.grid,
                                tickLength: 6
                            },
                            ticks: {
                                color: chartColors.ticks
                            }
                        },
                        y: {
                            grid: {
                                color: chartColors.grid
                            },
                            ticks: {
                                color: chartColors.ticks,
                                callback: value => {
                                    const formatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
                                    return formatter.format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
