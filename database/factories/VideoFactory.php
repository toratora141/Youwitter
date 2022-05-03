<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => Str::random(10),
            // 'video_list_id' => function () {
            //     return \App\Models\VideoList::factory()->create()->id;
            // },
            'thumbnail' => Str::random(10),
            'title' => Str::random(10),
        ];
    }
}
