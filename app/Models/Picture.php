<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;
    protected $fillable = ['reader_id','name', 'quran'];

    public function reader() {
        return $this->belongsTo(Reader::class,'reader_id');
    }

}
