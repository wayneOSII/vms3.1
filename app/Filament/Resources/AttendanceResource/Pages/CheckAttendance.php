<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use Livewire\Livewire;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\AttendanceResource;

class CheckAttendance extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected static string $view = 'filament.resources.attendance-resource.pages.check-attendance';
    
    // public function layout()
    // {
    //     return Filament::make('div')
    //         ->child(
    //             Livewire::mount('session-verification-code-generator')
    //         );
    // }
}
