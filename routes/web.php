<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ExcelController;
use App\Filament\Resources\AttendanceResource\Pages\CheckAttendance;
use App\Http\Controllers\PdfController;

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

// Route::get('/', function () {
//     return view('welcome');
// })->name('/');

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
    // return redirect()->route('/');
    return redirect()->route('home');
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

Route::get('/generate-excel', [ExcelController::class, 'generateExcel'])->name('download.xlsx');
Route::get('/{record}/pdf/download',[PdfController::class, 'download'])->name('certification.pdf.download');

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/about-us', [SiteController::class, 'about'])->name('about-us');
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('by-category');
Route::get('/{post:slug}', [PostController::class, 'show'])->name('view');