<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'jumlah_halaman',
        'penerbit',
    ];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
