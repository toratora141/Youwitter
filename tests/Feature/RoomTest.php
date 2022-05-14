<?php

use App\Models\User;
use App\Models\Room;
use App\Models\UserList;

test('fetch rooms', function () {
    $user = User::factory()->createMany([
        ['account_name' => 'user1', 'display_name' => 'user1', 'icon' => 'icon1'],
        ['account_name' => 'user2', 'display_name' => 'user2', 'icon' => 'icon2'],
        ['account_name' => 'user3', 'display_name' => 'user3', 'icon' => 'icon3'],
    ]);
    $rooms = Room::factory()->createMany([
        ['type' => 'message'],
        ['type' => 'message'],
        ['type' => 'message'],
    ]);
    $userLists = UserList::factory()->createMany([
        ['room_id' => 1, 'user_id' => 'user1'],
        ['room_id' => 1, 'user_id' => 'user2'],
        ['room_id' => 2, 'user_id' => 'user1'],
        ['room_id' => 2, 'user_id' => 'user2'],
        ['room_id' => 2, 'user_id' => 'user3'],
        ['room_id' => 3, 'user_id' => 'user2'],
        ['room_id' => 3, 'user_id' => 'user3'],
    ]);
    $expectValue = [
        [
            'room_id' => 1,
            'type' => 'message',
            'user_list' => [
                [
                    'room_id' => '1',
                    'user_id' => 'user1',
                    'user' => [
                        'account_name' => 'user1',
                        'display_name' => 'user1',
                        'icon' => 'icon1'
                    ],
                ],
                [
                    'room_id' => '1',
                    'user_id' => 'user2',
                    'user' => [
                        'account_name' => 'user2',
                        'display_name' => 'user2',
                        'icon' => 'icon2'
                    ],
                ],
            ],
        ],
        [
            'room_id' => 2,
            'type' => 'message',
            'user_list' => [
                [
                    'room_id' => '2',
                    'user_id' => 'user1',
                    'user' => [
                        'account_name' => 'user1',
                        'display_name' => 'user1',
                        'icon' => 'icon1'
                    ],
                ],
                [
                    'room_id' => '2',
                    'user_id' => 'user2',
                    'user' => [
                        'account_name' => 'user2',
                        'display_name' => 'user2',
                        'icon' => 'icon2'
                    ],
                ],
                [
                    'room_id' => '2',
                    'user_id' => 'user3',
                    'user' => [
                        'account_name' => 'user3',
                        'display_name' => 'user3',
                        'icon' => 'icon3'
                    ],
                ],
            ],
        ]
    ];

    $response = actingAs($user[0])->json('GET', route('room.fetch'));
    $response->assertStatus(200);
    $response->assertJson(['rooms' => json_decode(json_encode($expectValue), true)]);
})->group('fetch');

test('create new room', function () {
    $users = User::factory()->createMany([
        ['account_name' => 'user1', 'display_name' => 'user1', 'icon' => 'icon'],
        ['account_name' => 'user2', 'display_name' => 'user2', 'icon' => 'icon'],
        ['account_name' => 'user3', 'display_name' => 'user3', 'icon' => 'icon'],
    ]);
    $rooms = Room::factory()->createMany([
        ['type' => 'message'],
    ]);
    $userLists = UserList::factory()->createMany([
        ['room_id' => 1, 'user_id' => 'user1'],
        ['room_id' => 1, 'user_id' => 'user3'],
    ]);
    $param = [
        'users' => [
            'user1', 'user2',
        ]
    ];
    $expectValue = [
        'room' => [
            'room_id' => 2,
            'type' => 'message',
            'user_list' => [
                [
                    'user_id' => 'user1',
                    'user' => [
                        'account_name' => 'user1',
                        'display_name' => 'user1',
                    ]
                ],
                [
                    'user_id' => 'user2',
                    'user' => [
                        'account_name' => 'user2',
                        'display_name' => 'user2',
                    ]
                ],
            ]
        ]
    ];


    $response = actingAs($users[0])->json('POST', route('room.create'), $param);
    $response->assertStatus(200);
    $response->assertJson(json_decode(json_encode($expectValue), true));
})->group('create');

test('create new room but room already exist', function () {
    $users = User::factory()->createMany([
        ['account_name' => 'user1', 'display_name' => 'user1', 'icon' => 'icon'],
        ['account_name' => 'user2', 'display_name' => 'user2', 'icon' => 'icon'],
        ['account_name' => 'user3', 'display_name' => 'user3', 'icon' => 'icon'],
    ]);
    $rooms = Room::factory()->createMany([
        ['type' => 'message'],
    ]);
    $userLists = UserList::factory()->createMany([
        ['room_id' => 1, 'user_id' => 'user1'],
        ['room_id' => 1, 'user_id' => 'user2'],
        ['room_id' => 1, 'user_id' => 'user3'],
    ]);
    $param = [
        'users' => [
            'user1', 'user2', 'user3'
        ]
    ];
    $expectValue = [
        'room' => [
            'room_id' => 1,
            'type' => 'message',
            'user_list' => [
                [
                    'user_id' => 'user1',
                    'user' => [
                        'account_name' => 'user1',
                        'display_name' => 'user1',
                    ]
                ],
                [
                    'user_id' => 'user2',
                    'user' => [
                        'account_name' => 'user2',
                        'display_name' => 'user2',
                    ]
                ],
                [
                    'user_id' => 'user3',
                    'user' => [
                        'account_name' => 'user3',
                        'display_name' => 'user3',
                    ]
                ],
            ]
        ]
    ];


    $response = actingAs($users[0])->json('POST', route('room.create'), $param);
    $response->assertStatus(200);
    $response->assertJson(json_decode(json_encode($expectValue), true));
})->group('create')->skip();
