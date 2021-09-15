<?php

namespace Database\Factories;

use App\Models\Attivita;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class AttivitaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attivita::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date_after = clone $date_time = $this->faker->dateTimeBetween;
        $date_after->add(new \DateInterval('P1D'))->setTime(0, 0);
        $timestamp_middle = rand($date_time->getTimestamp(), $date_after->getTimestamp());

        $date = $date_time->format('Y-m-d');
        $start_time = $date_time->format('H:i:s');
        $end_time = date('H:i:s', $timestamp_middle);
        $duration = date('H:i:s', $date_after->getTimestamp() - $timestamp_middle);

        return [
            'data' => $date,
            'ora_inizio' => $start_time,
            'ora_fine' => $end_time,
            'durata' => $duration,
            'luogo' => $this->faker->city,
            'descrizione_attivita' => $this->faker->sentence(rand(2, 5)),
            'note_interne' => $this->faker->sentence(rand(0, 10)),
            'rapportino_attivita' => rand(0, 1)
        ];
    }
}
