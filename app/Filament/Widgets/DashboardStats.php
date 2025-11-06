<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalFaturamento = Invoice::where('status', InvoiceStatus::Paid)->sum('total');

        return [
            Stat::make('Total de Projetos', Project::count())
                ->description('Projetos cadastrados')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Total de Leads', Lead::count())
                ->description('Leads na base')
                ->icon('heroicon-o-phone-arrow-down-left'),
            Stat::make('Total de Clientes', Client::count())
                ->description('Clientes ativos')
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Total de Faturamento', Number::currency($totalFaturamento, 'BRL'))
                ->description('Soma de faturas pagas')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}