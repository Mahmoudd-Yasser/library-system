<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qr_logs extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'check_in','check_out'];

    public function student()
    {
        return $this->belongsTo(students::class);
    }
}
