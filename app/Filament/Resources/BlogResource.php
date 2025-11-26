<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->required(),
                FileUpload::make('image')
                    ->directory('projects')
                    ->image()
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = $file->store('image', 'public');
                        return 'storage/' . $path;
                    }),
                    Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'slug')
                    ->preload()
                    ->searchable()
                    ->label('Categories'),
                

                Repeater::make('translations')
                    ->relationship()
                    ->schema([
                        Select::make('locale')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic'
                            ])
                            ->required(),
                        TextInput::make('name')->required(),
                        RichEditor::make('body')->required(),
                    ])
                    ->default([
                        [
                            'locale' => 'en',
                            'name' => '',
                            'body' => '',
                        ],
                        [
                            'locale' => 'ar',
                            'name' => '',
                            'body' => '',
                        ]
                    ])
                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Blog';
                    })
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('translations.name')
                    ->label('name')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->name ?? $ar->name ?? 'N/A';
                    })->searchable(),
                TextColumn::make('translations.body')
                    ->label('body')
                    ->formatStateUsing(function ($state, $record) {
                        $en = $record->translations->firstWhere('locale', 'en');
                        $ar = $record->translations->firstWhere('locale', 'ar');
                        return $en->name ?? $ar->name ?? 'N/A';
                    })->searchable(),
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


        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
