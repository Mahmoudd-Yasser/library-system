<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class borrows extends Model
{
    use HasFactory;
    protected $fillable = ['book_id','student_id', 'borrow_date', 'return_date','borrow_status'];

    public function student()
    {
        return $this->belongsTo(students::class);
    }

    public function book()
    {
        return $this->belongsTo(books::class);
    }
}
