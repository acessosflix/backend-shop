<?php

namespace App\Http\Controllers;

use App\Services\LandingPageService;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        private readonly LandingPageService $landingPageService,
    ) {
    }

    public function index(): View
    {
        $metrics = $this->landingPageService->getDashboardSnapshot();
        $recentOrders = $this->landingPageService->getRecentOrders();
        $revenueTrend = $this->landingPageService->getRevenueTrend();
        $inventory = $this->landingPageService->getInventoryInsights();
        $orderStatus = $this->landingPageService->getOrderStatusBreakdown();
        $topProducts = $this->landingPageService->getTopProducts();
        $topCategories = $this->landingPageService->getCategoryPerformance();

        return view('landing', compact(
            'metrics',
            'recentOrders',
            'revenueTrend',
            'inventory',
            'orderStatus',
            'topProducts',
            'topCategories',
        ));
    }
}
