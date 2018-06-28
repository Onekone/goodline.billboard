<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AdController;
use Auth;
use App\User;

class AdControllerTest extends TestCase
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
        $response->assertStatus(201);
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
        $user = $this->verifiedUser;
        $ad = factory(\App\Ad::class)->create(['user_id'=>$user->id]);

        // act
        $responseEdit = $this->actingAs($user)->get(route('ad.edit',$ad->id));
        $responseUpdate = $this->actingAs($user)->put( route('ad.update',$ad->id) , $this->postExample2 );

        // assert
        $responseEdit->assertStatus(200);
        $responseUpdate->assertStatus(302);
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

    public function test_WhileUnauth_Delete_RedirectBack() {
        // arrange
        $user = $this->verifiedUser;
        $ad = $this->otherUserAds->random();

        // act
        $responseDelete = $this->delete(route('ad.destroy',$ad->id));

        // assert
        $responseDelete->assertStatus(302);
    }
    public function test_WhileAuth_Delete_Success() {
        // arrange
        $user = $this->verifiedUser;
        $ad = factory(\App\Ad::class)->create(['user_id'=>$user->id]);

        // act
        $responseDelete = $this->actingAs($user)->delete(route('ad.destroy',$ad->id));

        // assert
        $responseDelete->assertStatus(302);
        $responseDelete->assertRedirect(route('ad.index'));
    }
    public function test_WhileAuth_UnownedDelete_Redirect() {
        // arrange
        $user = $this->verifiedUser;
        $ad = $this->otherUserAds->random();

        // act
        $responseDelete = $this->actingAs($user)->delete(route('ad.destroy',$ad->id));

        // assert
        $responseDelete->assertStatus(302);
        $responseDelete->assertRedirect(route('ad.index'));
    }
    public function test_WhileAuth_NotExistingDelete_404() {
        // arrange
        $user = $this->verifiedUser;

        // act
        $responseDelete = $this->actingAs($user)->delete(route('ad.destroy',-1));

        // assert
        $responseDelete->assertStatus(404);
    }

}