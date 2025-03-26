<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class students extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'student_id'];

    public function borrows()
    {
        return $this->hasMany(borrows::class);
    }

    public function qrLogs()
    {
        return $this->hasMany(qr_logs::class);
    }

    public function books()
    {
        return $this->belongsToMany(books::class, 'book_student');
    }
}
