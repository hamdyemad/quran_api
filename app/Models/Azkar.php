<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Azkar extends Model
{
    use HasFactory;
    protected $table = 'azkars';
    protected $fillable = ['azkar_category_id', 'elzekr', 'about'];

    public function category() {
        return $this->belongsTo(AzkarCategory::class, 'azkar_category_id');
    }
}
