<?php

namespace App\Filament\Resources\GeneralDashboardResource\Widgets;

use App\Models\Blog;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Blogs', Blog::count())
            ->description('All blogs in database')
            ->descriptionIcon('heroicon-m-document-text', IconPosition::Before)
            ->color('info'),

        Stat::make('Published Blogs', Blog::where('status', true)->count())
            ->description('Visible to public')
            ->descriptionIcon('heroicon-m-check-badge', IconPosition::Before)
            ->chart([7, 2, 10, 3, 15, 4, 17]) // Optional: visual trend line
            ->color('success'),        ];
    }
}
