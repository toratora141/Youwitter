<?php

use App\Models\User;

it('redirects to user profile', function () {

    $user = User::factory()->create();
    $param = ['accountName' => $user->account_name];
    $temp = actingAs($user)->json('GET', route('profile', $param))
        ->assertJson([
            'result' => true,
            'user' => [
                'account_name' => $user->account_name,
                'display_name' => $user->display_name,
            ]
        ]);
});
