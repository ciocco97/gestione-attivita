<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company();
        $email_prefix = preg_replace("/[^a-zA-Z]+/", "", $name);
        return [
            'nome' => $name,
            'email' => $email_prefix.'@gmail.com',
            'rapportino_cliente' => random_int(0, 1)
        ];
    }
}
