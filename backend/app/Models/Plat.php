<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre_plat',
        'image',
        'type_plat_id',
    ];
    protected $with = ['allergenes', 'typePlat'];

    public function allergenes()
    {
        return $this->belongsToMany(Allergene::class, 'allergene_plats', 'plat_id', 'allergene_id');
    }
    public function typePlat()
    {
        return $this->belongsTo(TypePlat::class, 'type_plat_id');
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'plat_menus', 'plat_id', 'menu_id');
    }
}
