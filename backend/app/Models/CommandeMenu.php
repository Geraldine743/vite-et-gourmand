<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeMenu extends Model
{
    protected $fillable = [
        'commande_id',
        'menu_id',
        'nb_personnes',
        'prix_unitaire_fixe',
        'prix_total_ligne',
    ];
}
