<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreativeDepartmentsResource\Pages;
use App\Filament\Resources\CreativeDepartmentsResource\RelationManagers;
use App\Models\CreativeDepartments;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\File;

class CreativeDepartmentsResource extends Resource
{
    protected static ?string $model = CreativeDepartments::class;

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
                FileUpload::make('image')
                    ->directory('image')
                    ->image()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = public_path('image');

                        if (!File::exists($path)) {
                            File::makeDirectory($path, 0755, true);
                        }

                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $target = $path . DIRECTORY_SEPARATOR . $filename;

                        File::copy($file->getRealPath(), $target);

                        return 'image/' . $filename;
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

                        TextInput::make('name')->required(),
                        TextInput::make('description')->required()
                    ])->default([
                            [
                                'local' => 'en',
                                'name' => '',
                                'description' => ''
                            ],
                            [
                                'local' => 'ar',
                                'name' => '',
                                'description' => ''
                            ],
                        ])
                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Our Creative Department';
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextInput::make('name'),
                TextInput::make( 'slug'),
                ImageColumn::make('image')
                    ->square()
                    ->state(
                        fn($record) =>
                        $record->image
                        ? asset($record->image)
                        : null
                    )
                ,
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
            'index' => Pages\ListCreativeDepartments::route('/'),
            'create' => Pages\CreateCreativeDepartments::route('/create'),
            'edit' => Pages\EditCreativeDepartments::route('/{record}/edit'),
        ];
    }
}
