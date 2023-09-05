<?php

namespace App\Filament\Resources\CertificationResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CertificationResource;

class ListCertifications extends ListRecords
{
    protected static string $resource = CertificationResource::class;

    protected function getActions(): array
    {
        $isAdmin = Auth::user()->hasRole('admin');

        return $isAdmin ?[
            Actions\ButtonAction::make('輸出xlsx檔')
                ->url(fn()=> route('download.xlsx'))
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ]:[
            Actions\CreateAction::make(),
        ];
    }
}
