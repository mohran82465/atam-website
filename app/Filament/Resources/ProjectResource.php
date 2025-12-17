<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z0-9\-]+$/')   // <- only lowercase, numbers, dashes
                    ->rule('lowercase'),

                FileUpload::make('image')
                    ->directory('projects')
                    ->image()
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = $file->store('image', 'public');
                        return 'storage/' . $path;
                    })
                    ,

                Repeater::make('translations')
                    ->relationship()
                    ->label('Translations')
                    ->schema([
                        Select::make('locale')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                            ])
                            ->required()
                        ,

                        TextInput::make('name')->required(),
                        Textarea::make('body')->nullable(),
                        Textarea::make('problem')->nullable(),
                        Textarea::make('solve')->nullable(),
                        TagsInput::make('tech')->nullable(),
                    ])
                    ->default([
                        [
                            'locale' => 'en',
                            'name' => '',
                            'body' => '',
                            'problem' => '',
                            'solve' => '',
                            'tech' => [],
                        ],
                        [
                            'locale' => 'ar',
                            'name' => '',
                            'body' => '',
                            'problem' => '',
                            'solve' => '',
                            'tech' => [],
                        ]
                    ])
                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Content';
                    })
                    ->columns(2)
                    ->columnSpanFull()
                    ->maxItems(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')->searchable(),
                ImageColumn::make('image'),
                TextColumn::make('translations.name')
                    ->label('Name')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->name ?? $ar->name ?? 'N/A';
                    }),
                TextColumn::make('translations.body')
                    ->label('Body')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->body ?? $ar->body ?? 'N/A';
                    }),
                TextColumn::make('translations.problem')
                    ->label('Problem')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->problem ?? $ar->problem ?? 'N/A';
                    }),

                TextColumn::make('translations.solve')
                    ->label('Solve')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->solve ?? $ar->solve ?? 'N/A';
                    }),
                TextColumn::make('translations.tech')
                    ->label('Tech')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');

                        $value = $en->tech ?? $ar->tech ?? [];

                        return is_array($value) ? implode(', ', $value) : $value;
                    }),


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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
