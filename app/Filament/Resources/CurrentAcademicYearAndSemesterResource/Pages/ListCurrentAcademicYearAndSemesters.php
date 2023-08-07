<?php

namespace App\Filament\Resources\CurrentAcademicYearAndSemesterResource\Pages;

use App\Filament\Resources\CurrentAcademicYearAndSemesterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurrentAcademicYearAndSemesters extends ListRecords
{
    protected static string $resource = CurrentAcademicYearAndSemesterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
