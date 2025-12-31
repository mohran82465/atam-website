<?php

namespace App\Filament\Resources\GeneralDashboardResource\Widgets;

use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnreadContacts extends BaseWidget
{
    protected function getStats(): array
    {
        $count = ContactMessage::where('is_read', false)->count();

    return [
        Stat::make('Unread Messages', $count)
            ->description('Needs attention')
            ->descriptionIcon('heroicon-m-exclamation-circle')
            ->icon('heroicon-o-envelope')
            ->color($count > 0 ? 'danger' : 'success'),
    ];
    }
}
