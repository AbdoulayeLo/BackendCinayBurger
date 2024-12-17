<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable=[
        'prenom',
        'nom',
        'email',
        'telephone'
    ];
    public function commande(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Commande::class, 'client_id'); // Relation inverse
    }
}
