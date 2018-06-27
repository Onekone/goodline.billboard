<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AdController;
use Auth;
use App\User;
use App\Ad;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;

    var $postExample1 = [
        'title'=>'ABCDEABCDE',
        'content'=>'ABCDEABCDEABCDEABCDE',
        'contact'=>'ABCDEABCDE'
    ];

    var $postExample2 = [
        'title'=>'ABCDEABCDE',
        'content'=>'ABCDEABCDEABCDEABCDE',
        'contact'=>'ABCDEABCDE'
    ];

    var $otherUser = null;
    var $verifiedUser = null;
    var $notVerifiedUser = null;
    var $otherUserAds = null;

    public function setUp() {
        parent::setUp();

        $this->otherUser = factory(User::class)->create();
        $this->otherUser->verified = 1;
        $this->otherUser->save();

        $this->verifiedUser = factory(User::class)->create();
        $this->verifiedUser->verified = 1;
        $this->verifiedUser->save();

        $this->notVerifiedUser = factory(User::class)->create();
        $this->notVerifiedUser->verified = 0;
        $this->notVerifiedUser->save();

        $this->otherUserAds = factory(\App\Ad::class,5)->create(['user_id'=>$this->otherUser->id]);
    }

    public function testBasicTest()
    {
        // act
        $response = $this->get('/');

        // assert
        $response->assertStatus(200);
    }

    public function test_WhileUnauth_AccessCreate_Redirected()
    {
        // act
        $response = $this->get('/ad/create');

        // assert
        $response->assertStatus(302);
    }

    public function test_WhileAuthUnverified_AccessCreate_Redirected()
    {
        // arrange
        $user = $this->notVerifiedUser;

        // act
        $response = $this->actingAs($user)->get('/ad/create');

        // assert
        $response->assertStatus(302);
    }

    public function test_WhileAuthVerified_AccessCreate_Success()
    {
        // arrange
        $user = $this->verifiedUser;

        // act
        $response = $this->actingAs($user)->get('/ad/create');

        // assert
        $response->assertStatus(200);
    }

    public function test_WhileAuthUnverified_Store_RedirectBack()
    {
        // arrange
        $user = $this->notVerifiedUser;

        // act
        $response = $this->actingAs($user)->call('POST',route('ad.store'),$this->postExample1);

        // assert
        $response->assertStatus(302);
        // из /app/Http/Middleware/CheckValidated.php
        // если  if(Auth::Check() && Auth::user()->verified) -> fail
        // то       return redirect()->back();
    }

    public function test_WhileAuthVerified_Store_Success() {
        // arrange
        $user = $this->verifiedUser;

        // act
        $response = $this->actingAs($user)->call('POST',route('ad.store'),$this->postExample1);

        // assert
        $response->assertStatus(200);
    }

    public function test_WhileAuthVerified_StoreOverload_RedirectBackOnCreateStore() {
        // arrange
        $user = $this->verifiedUser;

        // act
        for($i = 0;$i < 5;$i++)
            $this->actingAs($user)->call('POST',route('ad.store'),$this->postExample1);

        $responseCreate = $this->get(route('ad.create'));
        $responseStore = $this->call('POST',route('ad.store'),$this->postExample1);
        // assert

        $responseCreate->assertStatus(302);
        $responseStore->assertStatus(302);
        $this->assertLessThanOrEqual(5,\App\Ad::where('user_id',$user->id)->count());

        // из /app/Http/Middleware/CheckAdsMiddleware.php
        // если  if($posts<5) -> fail
        // то       return redirect()->back();
    }

    public function test_WhileUnauth_EditUpdate_RedirectBack() {
        // arrange
        $p = factory(\App\Ad::class)->create(['user_id'=>$this->verifiedUser]);

        // act
        $responseEdit = $this->get(route('ad.edit',$p->id));
        $responseUpdate = $this->put(route('ad.update',$p->id),$this->postExample2);

        // assert
        $responseEdit->assertStatus(302);
        $responseUpdate->assertStatus(302);
    }

    public function test_WhileAuthUnverified_EditUpdate_RedirectBack() {
        // arrange
        $p = factory(\App\Ad::class)->create(['user_id'=>$this->notVerifiedUser]);

        // act
        $responseEdit = $this->actingAs($this->notVerifiedUser)->get(route('ad.edit',$p->id));
        $responseUpdate = $this->actingAs($this->notVerifiedUser)->put(route('ad.update',$p->id),$this->postExample2);

        // assert
        $responseEdit->assertStatus(302);
        $responseUpdate->assertStatus(302);
    }

    public function test_WhileAuth_EditUpdate_Success() {
        // arrange
        $p = factory(\App\Ad::class)->create(['user_id'=>$this->verifiedUser]);

        // act
        $responseEdit = $this->actingAs($this->verifiedUser)->get(route('ad.edit',$p->id));
        $responseUpdate = $this->actingAs($this->verifiedUser)->put(route('ad.update',$p->id),$this->postExample2);

        // assert
        $responseEdit->assertStatus(200);
        $responseUpdate->assertStatus(200);
    }

    public function test_WhileAuth_Unowned_UpdateEdit_Redirect() {
        // arrange
        $ad = $this->otherUserAds->random();
        $user = $this->verifiedUser;

        // act
        $responseEdit = $this->actingAs($user)->get(route('ad.edit',$ad->id));
        $responseUpdate = $this->actingAs($user)->put(route('ad.update',$ad->id),$this->postExample2);

        // assert
        $responseEdit->assertStatus(302);
        $responseUpdate->assertStatus(302);
    }

    public function test_WhileAuth_NotExisting_UpdateEdit_404() {
        // arrange
        $user = $this->verifiedUser;

        // act
        $responseEdit = $this->actingAs($user)->get(route('ad.edit',-1));
        $responseUpdate = $this->actingAs($user)->put(route('ad.update',-1),$this->postExample2);

        // assert
        $responseEdit->assertStatus(404);
        $responseUpdate->assertStatus(404);
    }

    public function test_AuthVerified_Delete_Success()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();
        $this->be($user);

        // act
        $post = factory(Ad::class)->create(['user_id'=>$user->id]);
        $response1 = $this->call('POST',route('ad.store'),['title'=>$post->title,'content'=>$post->content,'contact'=>$post->contact]);
        $response2 = $this->call('DELETE',route('ad.destroy',$post->id));

        // assert
        $response1->assertStatus(200);
        $response2->assertStatus(302);
        $response2->assertRedirect(route('ad.index'));
    }


    public function test_AuthUnverified_Delete_Success()
    {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 0;
        $user->save();
        $this->be($user);

        // act
        $post = factory(Ad::class)->create(['user_id'=>$user->id]);
        $response = $this->call('DELETE',route('ad.destroy',$post->id));
        $response->assertStatus(302);
        //$response->assertRedirect(route('ad.index'));
        // assert
    }

    public function test_WhileAuth_Delete_Success() {}
    public function test_WhileAuth_UnownedDelete_Redirect() {}
    public function test_WhileAuth_NotExistingDelete_404() {}
}