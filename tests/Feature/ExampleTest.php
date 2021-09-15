<?php

namespace Tests\Feature;

use App\DataLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $dl = new DataLayer();

        echo $dl->listActivityStateForTech();

        $response->assertStatus(200);
    }
}
