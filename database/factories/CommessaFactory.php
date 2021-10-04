<?php

namespace Database\Factories;

use App\Models\Commessa;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommessaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commessa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'descrizione_commessa' => $this->faker->sentence(rand(2, 5)),
//            'stato_fatturazione_dafault_id' => rand(1, 3),
            'rapportino_commessa' => 1
        ];
    }
}
