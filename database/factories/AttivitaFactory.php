<?php

namespace Database\Factories;

use App\Models\Attivita;
use Carbon\Carbon;
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
     * @throws \Exception
     */
    public function definition()
    {
        $selector = random_int(1, 2);
        if ($selector == 1) {
            $now = Carbon::now();
            $start_date = Carbon::now()->startOfMonth()->subDay();
            $end_date = clone $start_date;
            $start_date = $start_date->startOfMonth();

            $start_week = $now->diffInWeeks($start_date);
            $end_week = $now->diffInWeeks($end_date);
            $random_date = $this->faker->dateTimeBetween('-' . $start_week . ' week', '-' . $end_week . ' week');
            $random_state = 4;
//            Log::debug("last month", ['start_date' => $start_date, 'end_date' => $end_date, 'random_date' => $random_date]);
            $billed = 2;
        } else {
            $random_date = $this->faker->dateTimeThisMonth();
            $random_date = Carbon::parse($random_date);
            while ($random_date->month != Carbon::now()->month) {
                $random_date->addDay();
            }
            $random_state = random_int(1, 4);
//            Log::debug("current month", ['random_date' => $random_date]);
            $billed = 1;
        }

        $start_time = Carbon::parse($random_date)->startOfDay();
        $end_time = clone $start_time;
        $end_time = $end_time->endOfDay()->sub('hour', random_int(5, 13));
        $start_time = $start_time->add('hour', random_int(8, 10));

        $diff_min = $end_time->diffInMinutes($start_time);
        $duration = random_int(1, $diff_min);
//        Log::debug("Time", ['start_time' => $start_time, 'end_time' => $end_time, 'diff_min' => $diff_min, 'duration' => $duration]);
        $temp_time = clone $start_time;
        $end_time = $temp_time->addMinutes($duration);
//        Log::debug("Time", ['start_time' => $start_time, 'end_time' => $end_time, 'diff_min' => $diff_min, 'duration' => $duration]);

        $date = $random_date->format('Y-m-d');
        $temp_time = clone $start_time;
        $start_time = $start_time->format('H:i');
        $end_time = $end_time->format('H:i');
        $duration = gmdate('H:i', $temp_time->diffInSeconds($end_time));

        return [
            'data' => $date,
            'ora_inizio' => $start_time,
            'ora_fine' => $end_time,
            'durata' => $duration,
            'luogo' => $this->faker->city,
            'descrizione_attivita' => $this->faker->sentence(rand(2, 5)),
            'note_interne' => $this->faker->sentence(rand(0, 10)),
            'rapportino_attivita' => 0,
            'contabilizzata' => $billed,
            'stato_attivita_id' => $random_state,
        ];
    }

}
