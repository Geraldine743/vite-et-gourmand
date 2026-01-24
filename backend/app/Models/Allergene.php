<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Allergene extends Model
{
    protected $fillable = ['libelle'];
}
