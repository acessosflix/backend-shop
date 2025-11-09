<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class LandingPageService
{
    public function getDashboardSnapshot(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = (float) Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $activeClients = UserClient::count();
        $ordersCompleted = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->count();

        return [
            'totalOrders' => $totalOrders,
            'totalRevenue' => round($totalRevenue, 2),
            'averageTicket' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0.0,
            'activeClients' => $activeClients,
            'conversionRate' => $totalOrders > 0 ? round(($ordersCompleted / $totalOrders) * 100, 1) : 0.0,
            'ordersInProgress' => Order::whereIn('status', ['pending', 'processing', 'paid'])->count(),
        ];
    }

    public function getRecentOrders(int $limit = 5): Collection
    {
        return Order::with(['userClient.user', 'orderItems.product'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getRevenueTrend(int $months = 6): array
    {
        $months = max($months, 1);
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $orders = Order::where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->get();

        $grouped = $orders->groupBy(fn (Order $order) => $order->created_at->format('Y-m'));

        $labels = [];
        $values = [];

        foreach (range(0, $months - 1) as $offset) {
            $date = (clone $startDate)->addMonths($offset);
            $key = $date->format('Y-m');

            $labels[] = $date->locale(app()->getLocale())->translatedFormat('M Y');
            $values[] = isset($grouped[$key]) ? round($grouped[$key]->sum('total_amount'), 2) : 0.0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getInventoryInsights(): array
    {
        $totalProducts = Product::count();

        return [
            'totalProducts' => $totalProducts,
            'lowStock' => Product::where('stock', '<', 10)->count(),
            'outOfStock' => Product::where('stock', '<=', 0)->count(),
            'averagePrice' => $totalProducts > 0 ? round((float) Product::avg('price'), 2) : 0.0,
        ];
    }

    public function getOrderStatusBreakdown(): array
    {
        $statuses = Order::selectRaw('status as label, COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $statuses->pluck('label')->map(fn (string $status) => ucfirst($status))->all(),
            'values' => $statuses->pluck('total')->all(),
        ];
    }

    public function getTopProducts(int $limit = 3): Collection
    {
        return OrderItem::selectRaw('product_id, SUM(quantity) as total_quantity, SUM(quantity * price) as total_revenue')
            ->whereHas('order', fn ($query) => $query->where('status', '!=', 'cancelled'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take($limit)
            ->get();
    }

    public function getCategoryPerformance(int $limit = 3): Collection
    {
        return Category::withCount('products')
            ->withSum('products as stock_sum', 'stock')
            ->orderByDesc('stock_sum')
            ->take($limit)
            ->get();
    }
}
