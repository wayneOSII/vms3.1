<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester',
        'student_id',
        'name',
        'area_id',
    ];

    public function area()
    {   
        //join的欄位預設是id
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function user()
    {
        //第一個student_id是reservation的第二個是user的
        return $this->belongsTo(User::class, 'student_id','student_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
