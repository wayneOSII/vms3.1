<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\AttendanceResource\Pages\CheckAttendance;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', function () {
        return redirect('/admin');
    })->name('dashboard');
});

Route::get('/admin/login', function () {
    return redirect()->route('/');
})->name('filament.auth.login');

// Route::get('/admin/attendances/check', CheckAttendance::class)->name('check-attendance');

// Route::get('/admin/attendances/check',function () {
//     // 這是一個被限制訪問的頁面
//     return redirect()->route('/');
// })->middleware('userCheckPermission');

Route::middleware(['userCheckPermission'])->group(function () {
    // 定義您的自訂頁面的路由
    Route::get('/admin/attendances/check', CheckAttendance::class);
});