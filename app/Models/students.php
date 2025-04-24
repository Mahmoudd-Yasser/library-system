<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class students extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    protected $fillable = ['name', 'student_id', 'image'];
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        // إزالة storage/ من بداية المسار إذا كانت موجودة
        $path = str_replace('storage/', '', $this->image);
        return asset('storage/' . $path);
    }

    public function borrows()
    {
        return $this->hasMany(borrows::class, 'student_id');
    }

    public function qrLogs()
    {
        return $this->hasMany(qr_logs::class);
    }

    public function books()
    {
        return $this->belongsToMany(books::class, 'book_student', 'student_id', 'book_id');
    }
}
