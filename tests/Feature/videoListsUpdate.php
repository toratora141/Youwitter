<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Console\VideoListUpdateDaily;

class videoListsUpdate extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        $test = new VideoListUpdateDaily;
        var_dump($test());
        $response->assertStatus(200);
    }
}
