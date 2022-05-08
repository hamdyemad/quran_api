<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AzkarCategory extends Model
{
    use HasFactory;
    protected $table = 'azkar_categories';
    protected $fillable = ['name', 'photo'];

    public function azkars() {
        return $this->hasMany(Azkar::class);
    }
}
