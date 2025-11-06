<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EcommerceStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $activeProducts = Product::where('status', 'active')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        
        // Faturamento do mês atual
        $monthRevenue = Order::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        // Total de produtos vendidos
        $totalProductsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->sum('order_items.quantity');
        
        // Ticket médio
        $averageTicket = Order::where('status', 'paid')->avg('total_amount');

        return [
            Stat::make('Total de Pedidos', $totalOrders)
                ->description('Todos os pedidos registrados')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success')
                ->chart($this->getOrdersChartData()),
            Stat::make('Faturamento Total', '$' . number_format($totalRevenue, 2))
                ->description('Faturamento do mês: $' . number_format($monthRevenue, 2))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($this->getRevenueChartData()),
            Stat::make('Produtos Ativos', $activeProducts)
                ->description('Disponíveis para venda')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Pedidos Pendentes', $pendingOrders)
                ->description('Aguardando pagamento')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Pedidos Entregues', $deliveredOrders)
                ->description('Concluídos com sucesso')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Produtos Vendidos', number_format($totalProductsSold))
                ->description('Total de unidades vendidas')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
            Stat::make('Ticket Médio', '$' . number_format($averageTicket ?? 0, 2))
                ->description('Valor médio por pedido')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }

    protected function getOrdersChartData(): array
    {
        $orders = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return $orders->pluck('count')->toArray();
    }

    protected function getRevenueChartData(): array
    {
        $revenue = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', 'paid')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return $revenue->pluck('total')->map(fn($value) => (float) $value)->toArray();
    }
}
