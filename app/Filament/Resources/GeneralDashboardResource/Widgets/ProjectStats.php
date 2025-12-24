<?php

namespace App\Filament\Resources\GeneralDashboardResource\Widgets;

use App\Models\Project;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::count())
                ->description('All projects in portfolio')
                ->descriptionIcon('heroicon-m-briefcase', IconPosition::Before)
                ->color('info'),

            Stat::make('Published Projects', Project::where('status', true)->count())
                ->description('Active on website')
                ->descriptionIcon('heroicon-m-eye', IconPosition::Before)
                ->color('success'),
        ];
    }
}
