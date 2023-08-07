<?php

namespace App\Filament\Resources;

use DateTime;
use Filament\Forms;
use App\Models\Area;
use Filament\Tables;
use App\Models\Attendance;
use App\Models\Reservation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Faker\Provider\ar_EG\Text;
use App\Rules\UniqueAttendance;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use App\Rules\VerificationCodeMatch;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Models\CurrentAcademicYearAndSemester;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $currentAcademicYearAndSemester = CurrentAcademicYearAndSemester::pluck('CurrentAcademicYearAndSemester')->first();
        $currentWeek = CurrentAcademicYearAndSemester::pluck('week')->first();
        $currentWeekNumber = intval(preg_replace('/\D/', '', $currentWeek));
        $isAdmin = Auth::user()->hasRole('admin');
        $reservation_id = Reservation::where('student_id', $user->student_id)->where('semester', $currentAcademicYearAndSemester)->value('id');
        $area_id = Reservation::where('id', $reservation_id)->value('area_id');
        $cleanarea = Area::where('id', $area_id)->value('cleanarea');

        // Get the options for 'week' select
        $weekOptions = [
            '第1週' => '第1週','第2週' => '第2週','第3週' => '第3週',
            '第4週' => '第4週','第5週' => '第5週','第6週' => '第6週',
            '第7週' => '第7週','第8週' => '第8週','第9週' => '第9週',
            '第10週' => '第10週','第11週' => '第11週','第12週' => '第12週',
            '第13週' => '第13週','第14週' => '第14週','第15週' => '第15週',
            '第16週' => '第16週','第17週' => '第17週','第18週' => '第18週',
        ];

        // Filter week options based on the current academic year and semester
        $filteredWeekOptions = [];
        foreach ($weekOptions as $weekKey => $weekValue) {
            $weekNumber = intval(preg_replace('/\D/', '', $weekValue));

            if ($weekNumber >= $currentWeekNumber) {
                $filteredWeekOptions[$weekKey] = $weekValue;
            }
        }

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('semester')
                            ->default($currentAcademicYearAndSemester)
                            ->disabled(! $isAdmin)
                            ->required(),
                        TextInput::make('student_id')
                            ->default($user->student_id)
                            ->disabled(! $isAdmin)
                            // ->unique(ignoreRecord:true)
                            ->required(),
                        TextInput::make('reservation_id')
                            ->default($reservation_id)
                            ->disabled(),
                        TextInput::make('cleanarea')
                            ->default($cleanarea)
                            ->disabled(),
                        Select::make('week')
                            ->options($filteredWeekOptions)
                            ->searchable()
                            ->rules([new UniqueAttendance])
                            ->required(),
                        Select::make('attendance_status')
                            ->options($isAdmin ?[
                                //第一個為key 第二個為value
                                '出席' => '出席',
                                '缺席' => '缺席',
                                '請假' => '請假',
                            ]:[
                                '請假' => '請假',
                            ])
                            // ->default('出席')
                            // ->disablePlaceholderSelection()
                            // ->disabled(! $isAdmin)
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
                TextColumn::make('week')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cleanarea')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('student_id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('attendance_status')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('attendance_time')
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
                DeleteAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
            'check' => Pages\CheckAttendance::route('/check'),
            'attend' => Pages\Attend::route('/attend'),
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
