<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Area;
use Filament\Tables;
use App\Models\Reservation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AreaResource\Pages;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AreaResource\RelationManagers;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    protected static ?string $navigationGroup = '預約';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('cleanarea')
                            ->required(),
                        TextInput::make('weekday')
                            ->required(),
                        TextInput::make('period')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        // TextInput::make('max_peoples')
                        //     ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {   
        $isAdmin = Auth::user()->hasRole('admin');
        // $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        // $current_people = Reservation::where('semester', $currentAcademicYearAndSemester)->pluck('area_id');

        return $table
            ->columns([
                // TextColumn::make('id')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('cleanarea')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('weekday')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('period')
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('max_peoples'),
                // TextColumn::make('current_people')
                //     ->default(getSemesterReservationCount(1)),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
                ButtonAction::make('reserve')
                    ->url('/admin/reservations/create'),
            ])
            ->bulkActions($isAdmin ? [
                Tables\Actions\DeleteBulkAction::make(),
            ]:[]);
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
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }    
}

function getSemesterReservationCount($id)
{
    // 假設你使用的是Eloquent模型Reservation
    // 在這裡你可以依據你的實際情況，將Reservation替換成適當的模型名稱
    $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();

    // 使用Eloquent模型方法取得指定學期的數量
    $count = Reservation::where('semester', $currentAcademicYearAndSemester)
                        ->where('area_id', $id)
                        ->count();

    return $count;
}