<?php

namespace Database\Seeders;

use App\Models\Burger;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BurgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Burger::factory()->count(10)->create();
//        DB::table('burgers')->insert([
//            'nom'=>'Burger1',
//            'prix'=>1200,
//            'description'=>'wsdffmsdndclkdfddjkdffdlj',
//            'image' =>'burger1',
//            'etat'=>0
//        ]);
//        DB::table('burgers')->insert([
//            'nom'=>'Burger2',
//            'prix'=>1200,
//            'description'=>'wsdffmsdndclkdfddjkdffdlj',
//            'image' =>'burger2',
//            'etat'=>1
//        ]);
    }
}
