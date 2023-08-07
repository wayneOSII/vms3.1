<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester',
        'week',
        'reservation_id',
        'cleanarea',
        'student_id',
        'attendance_status',
        'attendance_time',
        'code',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     // "updating" 事件處理函式
    //     static::updating(function ($model) {
    //         $model->attendance_time = now(); // 使用 Laravel 的 now() 方法來取得當前時間
    //     });
    // }

    public function user()
    {
        //第一個student_id是reservation的第二個是user的
        return $this->belongsTo(User::class, 'student_id','student_id');
    }
}
