<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CurrentAcademicYearAndSemesterResource\Pages;
use App\Filament\Resources\CurrentAcademicYearAndSemesterResource\RelationManagers;

class CurrentAcademicYearAndSemesterResource extends Resource
{
    protected static ?string $model = CurrentAcademicYearAndSemester::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '系統時間管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('CurrentAcademicYearAndSemester'),
                        Select::make('week')
                            ->options([
                                '第1週' => '第1週','第2週' => '第2週','第3週' => '第3週',
                                '第4週' => '第4週','第5週' => '第5週','第6週' => '第6週',
                                '第7週' => '第7週','第8週' => '第8週','第9週' => '第9週',
                                '第10週' => '第10週','第11週' => '第11週','第12週' => '第12週',
                                '第13週' => '第13週','第14週' => '第14週','第15週' => '第15週',
                                '第16週' => '第16週','第17週' => '第17週','第18週' => '第18週',
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('CurrentAcademicYearAndSemester'),
                TextColumn::make('week'),
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
            'index' => Pages\ListCurrentAcademicYearAndSemesters::route('/'),
            'create' => Pages\CreateCurrentAcademicYearAndSemester::route('/create'),
            'edit' => Pages\EditCurrentAcademicYearAndSemester::route('/{record}/edit'),
        ];
    }    
}
