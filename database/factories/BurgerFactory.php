<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BurgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom'=>$this->faker->randomElement(['burger1','burger2 ','burger3']),
            'prix'=>$this->faker->randomFloat(2,1000,2000),
            'description'=>$this->faker->text(200),
//            'image' => $this->faker->image,
            'image' => $this->faker->imageUrl(640, 480, 'food', true, 'Faker', true), // Ajout de l'extension

        ];
    }
}
