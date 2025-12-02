<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                ->unique(ignoreRecord: true)
                ->required()
                    ->regex('/^[a-z0-9\-]+$/')   // <- only lowercase, numbers, dashes
                    ->rule('lowercase')
                    ,
                TextInput::make('icon')
                    ->required(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('image')
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = $file->store('image', 'public');

                        return 'storage/' . $path;
                    }),
                Repeater::make('translations')
                    ->relationship()
                    ->schema([
                        Select::make('locale')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic'
                            ])
                            ->required(),
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('short_description')
                            ->required(),
                        TextInput::make('long_description')
                            ->required(),
                        TagsInput::make('features')
                            ->required(),
                    ])
                    ->default([
                        [
                            'locale' => 'en',
                            'title' => '',
                            'short_description' => '',
                            'long_description' => '',
                            'features' => [],
                        ],
                        [
                            'locale' => 'ar',
                            'title' => '',
                            'short_description' => '',
                            'long_description' => '',
                            'features' => [],
                        ],
                    ])
                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Our Service Name';
                    })
                    ->collapsible()
                    ->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->sortable(),
                TextColumn::make('icon')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
