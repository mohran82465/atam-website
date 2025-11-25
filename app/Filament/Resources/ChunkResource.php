<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChunkResource\Pages;
use App\Filament\Resources\ChunkResource\RelationManagers;
use App\Models\Chunk;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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

class ChunkResource extends Resource
{
    protected static ?string $model = Chunk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('page')
                        ->maxLength(191),
                    TextInput::make('slug')
                        ->maxLength(191),
                ]),

                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('image')
                    ->nullable()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = $file->store('image', 'public');

                        return 'storage/' . $path;
                    })
                ,

                // Translations repeater
                Repeater::make('translations')->label('content')
                    ->relationship('translations')
                    ->schema([
                        Select::make('locale')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                            ])
                            ->required(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(191),
                        RichEditor::make('body')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ])
                    ->default([
                        [
                            'locale' => 'en',
                            'title' => '',
                            'body' => '',
                        ],
                        [
                            'locale' => 'ar',
                            'title' => '',
                            'body' => '',
                        ],
                    ])

                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Content';
                    })
                    ->columns(1)
                    ->collapsible()
                    ->createItemButtonLabel('Add translation')
                    ->columnSpanFull()

                ,
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('slug')->searchable()->sortable(),
                TextColumn::make('page')->searchable(),
                TextColumn::make('translations_count')
                    ->counts('translations')
                    ->label('Translations'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListChunks::route('/'),
            'create' => Pages\CreateChunk::route('/create'),
            'edit' => Pages\EditChunk::route('/{record}/edit'),
        ];
    }
}
