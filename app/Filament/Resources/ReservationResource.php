<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Area;
use Filament\Tables;
use App\Models\Reservation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Rules\UniqueReservation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use App\Rules\AreaIdOccurrenceLimit;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '預約';

    public static function form(Form $form): Form
    {

        $user = Auth::user();
        // $currentAcademicYear = getCurrentAcademicYearAndSemester();
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $values = Reservation::where('semester', $currentAcademicYearAndSemester)->pluck('area_id');
        $semesters = Reservation::where('semester', $currentAcademicYearAndSemester)->pluck('semester');
        // $values = Reservation::pluck('area_id');
        // $semesters = Reservation::pluck('semester');
        
        $allOptions = Area::all()->pluck('name', 'id'); // Get all options

        $filteredOptions = [];

        foreach ($allOptions as $areaId => $name) {
            // Check the validation result from AreaIdOccurrenceLimit rule
            $validationResult = app(AreaIdOccurrenceLimit::class, ['areaIds' => $values, 'semesters' => $semesters])->passes('area_id', $areaId);

            // If the validation result is null, it means the area_id hasn't reached the limit
            if ($validationResult) {
                $filteredOptions[$areaId] = $name; // Add the option to $filteredOptions
            }
        }
        
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('semester')
                            ->default($currentAcademicYearAndSemester)
                            ->disabled()
                            ->required(),
                        TextInput::make('student_id')
                            ->default($user->student_id)
                            ->disabled()
                            // ->unique(ignoreRecord:true)
                            ->required(),
                        TextInput::make('name')
                            ->default($user->name)
                            ->disabled()
                            ->required(),
                        Select::make('area_id')
                            ->label('Area')
                            ->relationship('area', 'name')
                            ->options($filteredOptions)
                            ->searchable()
                            // ->rules([new AreaIdOccurrenceLimit($values,$semesters)])
                            ->rules([new UniqueReservation])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $isAdmin = Auth::user()->hasRole('admin');

        return $table
            ->columns([
                TextColumn::make('semester')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('student_id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('area.name')
                    ->label('Area')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        return auth()->user()->hasRole('admin')
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->whereHas(
                'user',
                fn(Builder $query) => $query->where('name','=',$user->name)
            );
    }
}

// function getCurrentAcademicYearAndSemester() {
//     // 取得當前的年份和月份
//     $currentYear = (int)date('Y');
//     $currentMonth = (int)date('n');

//     // 判斷學年度和學期
//     if ($currentMonth >= 8 && $currentMonth <= 12) {
//         // 如果當前月份是 8 月到 12 月，表示為下學期的一部分
//         // 學年度即為當前年份 - 1911，學期為 2
//         $academicYear = $currentYear - 1911;
//         $semester = 1;
//     } elseif($currentMonth == 1) {
//         $academicYear = $currentYear - 1912;
//         $semester = 1;
//     } else {
//         // 其他月份表示為上學期或假期
//         // 學年度即為當前年份 - 1912，學期為 1
//         $academicYear = $currentYear - 1912;
//         $semester = 2;
//     }

//     // 組成字串
//     $result = $academicYear .'-'. $semester;

//     return $result;
// }
