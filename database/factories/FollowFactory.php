<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->account_name;
            },
            'follower' => function () {
                return \App\Models\User::factory()->create()->account_name;
            },
            'notice_id' => 1,
        ];
    }
}
