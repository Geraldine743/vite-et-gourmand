<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'note',
        'publie',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
