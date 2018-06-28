<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/28/18
 * Time: 1:51 PM
 */

namespace Tests\Feature;

use App\EmailVerify;
use App\SocialProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Auth;
use App\User;
use App\Http\Controllers\ProfileController;

class ProfileControllerTest extends TestCase {

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
    var $mailVerifyRecord = null;

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

        $this->mailVerifyRecord = new \App\EmailVerify;
        $this->mailVerifyRecord->user_id = $this->notVerifiedUser->id;
        $this->mailVerifyRecord->verify_token = str_random(60);
        $this->mailVerifyRecord->save();

        $this->otherUserAds = factory(\App\Ad::class,5)->create(['user_id'=>$this->otherUser->id]);
    }

    public function test_WhileUnauth_SendAnotherVerify_Fail() {
        // arrange
        $user1 = $this->notVerifiedUser;
        $user2 = $this->verifiedUser;

        // act
        $response1 = $this->get(route('user.verify',$user1->id));
        $response2 = $this->get(route('user.verify',$user2->id));

        // assert
        $response1->assertStatus(302);
        $response2->assertStatus(302);
    }
    public function test_AuthVerified_SendAnotherVerify_Fail() {
        // arrange
        $user = $this->verifiedUser;
        // act
        $response = $this->get(route('user.verify',$user->id));
        // assert
        $response->assertStatus(302);

    }
    public function test_AuthUnverified_SendAnotherVerify_Success() {
        // arrange
        $user = $this->notVerifiedUser;
        // act
        $response = $this->actingAs($user)->get(route('user.verify',$user->id));
        // assert
        $response->assertStatus(200);
    }

    public function test_WhileUnauth_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileUnauth_UnknownID_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->get(route('user.clear',-1));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->notVerifiedUser)->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_NotSelf_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->notVerifiedUser)->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_UnknownID_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->notVerifiedUser)->get(route('user.clear',-1));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuth_NukeAds_Success() {
        // arrange
        $user = $this->verifiedUser;
        // act
        $response = $this->actingAs($user)->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(200);
    }
    public function test_WhileAuth_NotSelf_NukeAds_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->verifiedUser)->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuth_UnknownID_NukeAds_Success() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->verifiedUser)->get(route('user.clear',$user->id));
        // assert
        $response->assertStatus(302);
    }

    public function test_WhileUnauth_NukeUser_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->get(route('user.destroy',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileUnauth_UnknownID_NukeUser_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->get(route('user.destroy',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_NukeUser_RedirectBack() {
        // arrange
        $user = $this->notVerifiedUser;
        // act
        $response = $this->actingAs($user)->get(route('user.destroy',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_NotSelf_NukeUser_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->notVerifiedUser)->get(route('user.destroy',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuthUnverified_UnknownID_NukeUser_RedirectBack() {
        // arrange
        $user = $this->verifiedUser;
        // act
        $response = $this->actingAs($user)->get(route('user.destroy',-1));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuth_NukeUser_Success() {
        // arrange
        $user = factory(\App\User::class)->create(['verified'=>1]);
        // act
        $response = $this->actingAs($user)->get(route('user.destroy',$user->id));
        // assert
        $response->assertRedirect(route('ad.index'));
    }
    public function test_WhileAuth_NotSelf_NukeUser_RedirectBack() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->verifiedUser)->get(route('user.destroy',$user->id));
        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('root'));
    }
    public function test_WhileAuth_UnknownID_NukeUser_Success() {
        // arrange
        $user = $this->otherUser;
        // act
        $response = $this->actingAs($this->verifiedUser)->get(route('user.destroy',-1));
        // assert
        $response->assertStatus(302);
        $response->assertRedirect(route('root'));
    }

    public function test_WhileUnauth_UnbindVK_Redirect() {
        // arrange
        $user = $this->otherUser;
        $userBind = SocialProvider::firstOrCreate(['user_id'=>$user->id,'social_id'=>1234567,'social_provider'=>0]);
        // act
        $response = $this->get(route('user.unbindVK',$user->id));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuth_Unbound_UnbindVK() {
        // arrange
        $user = $this->otherUser;
        SocialProvider::where('user_id',$user->id)->delete();
        // act
        $response = $this->actingAs($user)->get(route('user.unbindVK',$user->id));
        // assert
        $response->assertStatus(200);
    }
    public function test_WhileAuth_Bound_UnbindVK_Success() {
        // arrange
        $user = $this->verifiedUser;
        $userBind = SocialProvider::firstOrCreate(['user_id'=>$user->id,'social_id'=>1234567,'social_provider'=>0]);
        $userBind->save();
        // act
        $response = $this->actingAs($this->verifiedUser)->get(route('user.unbindVK',$user->id));
        // assert
        $response->assertStatus(200);
    }

    public function test_WhileUnauth_Verify_Success() {
        // arrange
        $user = factory(\App\User::class)->create(['verified'=>0]);
        $userVerifyToken = EmailVerify::firstOrCreate(['user_id'=>$user->id,'verify_token'=>str_random(60)]);
        $userVerifyToken->save();

        // act
        $response = $this->get(route('verify',$userVerifyToken->verify_token));
        // assert
        $response->assertStatus(200);

    }
    public function test_WhileUnauth_UnknownToken_Verify_Failure() {
        // arrange

        // act
        $response = $this->get(route('verify','invalid_token'));
        // assert
        $response->assertStatus(302);
    }
    public function test_WhileAuth_Verify_Success() {
        // arrange
        $user = factory(\App\User::class)->create(['verified'=>0]);
        $userVerifyToken = EmailVerify::firstOrCreate(['user_id'=>$user->id,'verify_token'=>str_random(60)]);
        $userVerifyToken->save();

        // act
        $response = $this->actingAs($user)->get(route('verify',$userVerifyToken->verify_token));
        // assert
        $response->assertStatus(200);
    }
    public function test_WhileAuth_UnknownToken_Verify_Failure() {
        // arrange
        $user = factory(\App\User::class)->create(['verified'=>0]);
        // act
        $response = $this->actingAs($user)->get(route('verify','invalid_token'));
        // assert
        $response->assertStatus(302);
    }
}
