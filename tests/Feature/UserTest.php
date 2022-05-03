<?php

use App\Models\User;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertEquals;

test('success to register user ', function () {

    $param = [
        'account_name' => 'account_name',
        'display_name' => 'display_name',
        'password' => 'passpass',
        'password_confirm' => 'passpass'
    ];
    $checkParam = [
        'id' => 1,
        'account_name' => 'account_name',
        'display_name' => 'display_name',
    ];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertOk();
    $response->assertJson([
        'result' => true,
        'user' => $checkParam
    ]);
    $this->assertDatabaseHas('users', $checkParam);
})->group('register');

test('failed to register user because value dont have', function () {
    $param = [];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertStatus(422);
    $response->assertJson([
        'errors' => [
            'account_name' => ['入力されていません。'],
            'display_name' => ['入力されていません。'],
            'password' => ['入力されていません。'],
            'password_confirm' => ['確認のためパスワードを再入力してください。'],
        ]
    ]);
})->group('register');

test('failed to register user because account_name is uniqe', function () {
    //同じaccount_nameを渡すために、先にレコードの作成
    $user = User::factory()->create();
    $param = [
        'account_name' => $user->account_name,
        'display_name' => 'display_name',
        'password' => 'passpass',
        'password_confirm' => 'passpass'
    ];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertStatus(422);
    $response->assertJson([
        'errors' => [
            'account_name' => ['既に使用されているIDです。']
        ]
    ]);
})->group('register');

test('failed to register user because length is too short', function () {
    $param = [
        'account_name' => 'a',
        'display_name' => 'a',
        'password' => 'a',
        'password_confirm' => 'a'
    ];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertStatus(422);
    $response->assertJson([
        'errors' => [
            'account_name' => ['4文字以上で入力してください。'],
            'password' => ['6文字以上で入力してください。'],
        ]
    ]);
})->group('register');

test('failed to register user because length is too long', function () {
    $param = [
        'account_name' => Str::random(33),
        'display_name' => 'a',
        'password' => Str::random(17),
        'password_confirm' => Str::random(17)
    ];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertStatus(422);
    $response->assertJson([
        'errors' => [
            'account_name' => ['32文字以下で入力してください。'],
            'password' => ['16文字以下で入力してください。'],
        ]
    ]);
})->group('register');

test('failed to register user because password dont match', function () {
    $param = [
        'account_name' => 'account_name',
        'display_name' => 'display_name',
        'password' => Str::random(17),
        'password_confirm' => Str::random(17)
    ];

    $response = $this->json('POST', route('user.register', $param));

    $response->assertStatus(422);
    $response->assertJson([
        'errors' => [
            'password_confirm' => ['パスワードが一致しません。'],
        ]
    ]);
})->group('register');

test('fetch my profile', function () {
    $user = User::factory()->create();
    $param = profileParam($user->account_name, true);
    $userForPrepareResponse = [
        'account_name' => $user->account_name,
        'display_name' => $user->display_name,
        'icon' => $user->icon,
    ];

    $response = actingAs($user)->json('GET', route('user.profile', $param));
    $expectValue = User::prepareResponseForFetch(true, $userForPrepareResponse, null, false);

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('myProfile');

test('fetch my profile with relations', function () {
    $user = User::factory()->create();
    $user->each(function ($user) {
        $videoList = App\Models\VideoList::factory()->create(['user_id' => $user->account_name]);
        App\Models\Good::factory()->create([
            'notice_id' => 1,
            'video_id' => $videoList->first_video,
            'user_id' => $videoList->user_id
        ]);
    });

    $param = profileParam($accountName = $user->account_name, 'true');
    $userForPrepareResponse = User::prepareUserForResponse($accountName, $user->display_name, $user->icon);

    $fetchUserData =  User::fetchUserWithRelation($accountName);
    $response = actingAs($user)->json('GET', route('user.profile', $param));
    $expectValue = User::prepareResponseForFetch(true, $userForPrepareResponse, $fetchUserData[0]->videoLists, false);
    //返り値をjson型に変換しているため、期待する値をjsonにし戻す
    $expectValue =  json_decode(json_encode($expectValue), true);

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('myProfile');

test('fetch following user profile', function () {
    $follow = App\Models\Follow::factory()->create();

    $followed = User::where('account_name', $follow->user_id)->first();
    $follower = User::where('account_name', $follow->follower)->first();
    $param = profileParam($followed->account_name, 'false');
    $userForPrepareResponse = User::prepareUserForResponse($followed->account_name, $followed->display_name, $followed->icon);

    $response = actingAs($follower)->json('GET', route('user.profile', $param));
    $expectValue = User::prepareResponseForFetch(true, $userForPrepareResponse, null, true);

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('otherProfile');

test('search user', function () {
    $user = User::factory()->create();
    $apiParam = [
        'searchKeyword' => $user->account_name
    ];
    $expectValue = [
        'users' => [
            json_decode(json_encode($user), true)
        ]
    ];

    $response = actingAs($user)->json('GET', route('user.search', $apiParam));

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('search');

test('search user nothing', function () {
    $user = User::factory()->create();
    $apiParam = [
        'searchKeyword' => 'test'
    ];
    $expectValue = [
        'users' => null
    ];

    $response = actingAs($user)->json('GET', route('user.search', $apiParam));

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('search');

test('search all user', function () {
    $user = User::factory(3)->create();
    $apiParam = [
        'searchKeyword' => ''
    ];
    $expectValue = [
        'users' => json_decode(json_encode($user), true)
    ];

    $response = actingAs($user[0])->json('GET', route('user.search', $apiParam));

    $response->assertStatus(200);
    $response->assertJson($expectValue);
})->group('search');

function profileParam($accountName, $isMyProfile)
{
    return [
        'accountName' => $accountName,
        'isMyProfile' => $isMyProfile,
    ];
}

// it('success to fetch profile of user', function () {
//     $user = User::factory()->create();
//     $param = ['accountName' => $user->account_name];
//     $temp = actingAs($user)->json('GET', route('profile', $param))
//         ->assertJson([
//             'result' => true,
//             'user' => [
//                 'account_name' => $user->account_name,
//                 'display_name' => $user->display_name,
//             ]
//         ]);
// });
