<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class VideoListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id = 1;
        \App\Models\Video::factory()->create([
            'video_list_id' => $id
        ]);
        return [
            'id' => $id,
            'user_id' => 'testUserId',
            'thumbnail' => Str::random(10),
            'first_video' => Str::random(10),
        ];
    }
}
