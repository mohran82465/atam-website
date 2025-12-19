<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Filament\Resources\TeamMemberResource\RelationManagers;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\File;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->directory('image')
                    ->image()
                    ->saveUploadedFileUsing(function ($file) {
                        $path = public_path('image');

                        if (! File::exists($path)) {
                            File::makeDirectory($path, 0755, true);
                        }
                
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $target = $path . DIRECTORY_SEPARATOR . $filename;
                
                        File::copy($file->getRealPath(), $target);
                
                        return 'image/' . $filename;
                    })
                    ->required(),

                Repeater::make('translations')
                    ->label('Translations')
                    ->relationship()
                    ->schema([

                        Select::make('locale')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                            ])
                            ->required(),

                        TextInput::make('name')
                            ->required(),

                        TextInput::make('title')
                            ->required(),
                    ])
                    ->default([
                        [
                            'locale' => 'en',
                            'title' => '',
                            'name' => '',
                        ],
                        [
                            'locale' => 'ar',
                            'title' => '',
                            'name' => '',
                        ],
                    ])
                    ->itemLabel(function ($state) {
                        return ($state['locale'] == "ar" ? 'Arabic' : 'English') . ' Content';
                    })
                    ->columns(3)
                    ->createItemButtonLabel('Add translation')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('english_name')
                    ->label('Name')
                    ->getStateUsing(function ($record) {
                        return optional(
                            $record->translations->firstWhere('locale', 'en')
                        )->name ?? 'N/A';
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('english_title')
                    ->label('Title')
                    ->getStateUsing(function ($record) {
                        return optional(
                            $record->translations->firstWhere('locale', 'en')
                        )->title ?? 'N/A';
                    })
                    ->sortable()
                    ->searchable(),
                ImageColumn::make(name: 'image')
                    ->square()
                    ->state(fn ($record) =>
                    $record->image
                        ? asset($record->image)
                        : null
                )



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
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
