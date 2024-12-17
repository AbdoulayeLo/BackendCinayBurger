<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable=[
        'dateCommande',
        'etat',
        'montantTotal',
        'client_id',
        'burger_id',
        'quantite',
    ];

    public function burger()
    {
        return $this->belongsTo(Burger::class, 'burger_id'); // Assurez-vous que 'burger_id' est bien le nom de la colonne
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id'); // Assurez-vous que 'client_id' est bien le nom de la colonne
    }
}
