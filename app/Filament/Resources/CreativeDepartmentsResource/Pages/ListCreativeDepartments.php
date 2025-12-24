<?php

namespace App\Filament\Resources\CreativeDepartmentsResource\Pages;

use App\Filament\Resources\CreativeDepartmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreativeDepartments extends ListRecords
{
    protected static string $resource = CreativeDepartmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
