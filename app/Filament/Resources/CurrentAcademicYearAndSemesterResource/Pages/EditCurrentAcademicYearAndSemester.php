<?php

namespace App\Filament\Resources\CurrentAcademicYearAndSemesterResource\Pages;

use App\Filament\Resources\CurrentAcademicYearAndSemesterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurrentAcademicYearAndSemester extends EditRecord
{
    protected static string $resource = CurrentAcademicYearAndSemesterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
