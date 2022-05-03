<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // \App\Models\Notice::factory()->create()
        return [
            // 'user_id' => function () {
            //     return \App\Models\User::factory()->create()->account_name;
            // },
            // 'notice_id' => function () {
            //     return \App\Models\Notice::factory()->create(['type' => 'good'])->notice_id;
            // },
            // 'video_id' => function () {
            //     return \App\Models\Video::factory()->create()->code;
            // },
        ];
    }
}
