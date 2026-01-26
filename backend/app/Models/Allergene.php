<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Allergene extends Model
{
    protected $fillable = ['libelle'];
    
    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'allergene_plats', 'allergene_id', 'plat_id');
    }
}

