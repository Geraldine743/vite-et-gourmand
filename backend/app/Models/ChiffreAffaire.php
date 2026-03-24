<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ChiffreAffaire extends Model
{
    protected $connection = 'mongodb';
    
    protected $collection = 'chiffre_affaires';

    protected $fillable = [
        'date', 
        'montant_total', 
        'nombre_commandes',
        'details' 
    ];
}