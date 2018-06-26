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
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_Unauth_AccessCreate_Redirected()
    {
        $response = $this->get('/ad/create');
        $response->assertStatus(302);
    }

    public function test_AuthUnverified_AccessCreate_Redirected()
    {
        $user = factory(User::class)->create();
        $user->verified = 0;
        $user->save();
        $response = $this->actingAs($user)->get('/ad/create');
        $response->assertStatus(302);
    }

    public function test_AuthVerified_AccessCreate_Redirected()
    {
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();
        $response = $this->actingAs($user)->get('/ad/create');
        $response->assertStatus(200);
    }

    public function test_Auth_Store_Success()
    {
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();

        $response = $this->withoutMiddleware()->actingAs($user)->call('POST',route('ad.store'),['title'=>'ABCDEABCDE','content'=>'ABCDEABCDEABCDEABCDE','contact'=>'ABCDEABCDE']);

        $response->assertStatus(200);
    }
}
