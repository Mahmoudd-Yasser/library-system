<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        // إزالة storage/ من بداية المسار إذا كانت موجودة
        $path = str_replace('storage/', '', $this->image);
        return asset('storage/' . $path);
    }

    public function books()
    {
        return $this->hasMany(books::class, 'category_id');
    }
}
