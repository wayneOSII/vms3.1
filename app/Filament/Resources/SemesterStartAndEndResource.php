<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\SemesterStartAndEnd;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SemesterStartAndEndResource\Pages;
use App\Filament\Resources\SemesterStartAndEndResource\RelationManagers;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class SemesterStartAndEndResource extends Resource
{
    protected static ?string $model = SemesterStartAndEnd::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '系統時間管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        DatePicker::make('semester_start'),
                        DatePicker::make('semester_end'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('semester_start'),
                TextColumn::make('semester_end'),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSemesterStartAndEnds::route('/'),
            'create' => Pages\CreateSemesterStartAndEnd::route('/create'),
            'edit' => Pages\EditSemesterStartAndEnd::route('/{record}/edit'),
        ];
    }    
}
