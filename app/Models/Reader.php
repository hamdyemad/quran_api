<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'avatar'];

    public function pictures() {
        return $this->hasMany(Picture::class, 'reader_id');
    }
}
