<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EcommerceStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $activeProducts = Product::where('status', 'active')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return [
            Stat::make('Total de Pedidos', $totalOrders)
                ->description('Todos os pedidos')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
            Stat::make('Faturamento Total', '$' . number_format($totalRevenue, 2))
                ->description('Pedidos pagos')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Produtos Ativos', $activeProducts)
                ->description('Disponíveis para venda')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Pedidos Pendentes', $pendingOrders)
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
