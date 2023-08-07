<?php

namespace App\Filament\Resources\SemesterStartAndEndResource\Pages;

use App\Filament\Resources\SemesterStartAndEndResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSemesterStartAndEnd extends EditRecord
{
    protected static string $resource = SemesterStartAndEndResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
