<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AdController;
use Auth;
use App\User;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // act
        $response = $this->get('/');

        // assert
        $response->assertStatus(200);
    }

    public function test_Unauth_AccessCreate_Redirected()
    {
        // act
        $response = $this->get('/ad/create');

        // assert
        $response->assertStatus(302);
    }

    public function test_AuthUnverified_AccessCreate_Redirected()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 0;
        $user->save();

        // act
        $response = $this->actingAs($user)->get('/ad/create');

        // assert
        $response->assertStatus(302);
    }

    public function test_AuthVerified_AccessCreate_Redirected()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();

        // act
        $response = $this->actingAs($user)->get('/ad/create');

        // assert
        $response->assertStatus(200);
    }

    public function test_AuthUnverified_Store_RedirectBack()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 0;
        $user->save();
        $this->be($user);

        // act
        $response = $this->call('POST',route('ad.store'),['title'=>'ABCDEABCDE','content'=>'ABCDEABCDEABCDEABCDE','contact'=>'ABCDEABCDE']);

        // assert
        $response->assertStatus(302);
        // из /app/Http/Middleware/CheckValidated.php
        // если  if(Auth::Check() && Auth::user()->verified) -> fail
        // то       return redirect()->back();
    }

    public function test_AuthVerified_Store_Success()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();
        $this->be($user);

        // act
        $response = $this->call('POST',route('ad.store'),['title'=>'ABCDEABCDE','content'=>'ABCDEABCDEABCDEABCDE','contact'=>'ABCDEABCDE']);

        // assert
        $response->assertStatus(200);
    }
}
