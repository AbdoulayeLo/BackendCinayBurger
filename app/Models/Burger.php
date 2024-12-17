<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    use HasFactory;
    protected $fillable=[
        'nom',
        'prix',
        'description',
        'image',
    ];


    public function commande()
    {
        return $this->hasMany(Commande::class, 'burger_id'); // Relation inverse
    }
}
