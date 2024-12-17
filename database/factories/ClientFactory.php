<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'prenom'=>$this->faker->firstName,
//            'nom'=>$this->faker->lastName,
//            'email'=>$this->faker->unique()->safeEmail,
//            'telephone'=>$this->faker->phoneNumber()
        ];
    }
}
