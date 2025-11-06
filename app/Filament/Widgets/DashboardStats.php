<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Projetos', Project::count())
                ->description('Projetos cadastrados')
                ->icon('heroicon-o-briefcase'),
            Stat::make('Total de Clientes', Client::count())
                ->description('Clientes ativos')
                ->icon('heroicon-o-building-office-2'),
        ];
    }
}