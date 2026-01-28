<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'statut_commande_id',
        'numero_commande',
        'date_commande',
        'date_prestation',
        'date_livraison',
        'heure_livraison',
        'adresse_livraison',
        'ville_livraison',
        'code_postal_livraison',
        'prix_livraison',
        'total_commande',
        'pret_materiel',
        'retour_materiel',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_prestation' => 'date',
        'date_livraison' => 'date',
        'pret_materiel' => 'boolean',
        'retour_materiel' => 'boolean',
    ];

    protected $with = ['menus', 'statutCommande'];

    protected static function booted ()
    {
        static::creating(function ($commande) {
            if (empty($commande->numero_commande)) {
                $commande->numero_commande = 'CMD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'commande_menus')
                ->withPivot('nb_personnes', 'prix_unitaire_fixe', 'prix_total_ligne')
                ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function statutCommande()
    {
        return $this->belongsTo(StatutCommande::class);
    }
}
