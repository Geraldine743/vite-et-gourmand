<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'nb_personne',
        'prix_par_personne',
        'condition',
        'stock',
        'regime_id',
        'theme_id',
    ];
    protected $with = ['regime', 'theme', 'plats'];

    public function regime()
    {
        return $this->belongsTo(Regime::class);
    }
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'plat_menus', 'menu_id', 'plat_id');
    }
}
