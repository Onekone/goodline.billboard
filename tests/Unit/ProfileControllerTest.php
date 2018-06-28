<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/28/18
 * Time: 1:51 PM
 */

namespace Tests\Feature;

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

    public function test_WhileUnauth_SendAnotherVerify_Fail() {
        // arrange

        // act

        // assert
    }
    public function test_AuthVerified_SendAnotherVerify_Fail() {
        // arrange

        // act

        // assert
    }
    public function test_AuthUnverified_SendAnotherVerify_Success() {
        // arrange

        // act

        // assert
    }

    public function test_WhileUnauth_NukeAds_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileUnauth_UnknownID_NukeAds_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_NukeAds_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_NotSelf_NukeAds_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_UnknownID_NukeAds_Success() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_NukeAds_Success() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_NotSelf_NukeAds_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_UnknownID_NukeAds_Success() {
        // arrange

        // act

        // assert

    }

    public function test_WhileUnauth_NukeUser_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileUnauth_UnknownID_NukeUser_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_NukeUser_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_NotSelf_NukeUser_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuthUnverified_UnknownID_NukeUser_Success() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_NukeUser_Success() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_NotSelf_NukeUser_RedirectBack() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_UnknownID_NukeUser_Success() {
        // arrange

        // act

        // assert

    }

    public function test_WhileUnauth_UnbindVK() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_Unbound_UnbindVK() {
        // arrange

        // act

        // assert

    }
    public function test_WhileAuth_Bound_UnbindVK() {
        // arrange

        // act

        // assert

    }

    public function test_WhileUnauth_Verify_Failure() {
    }
    public function test_WhileUnauth_UnknownToken_Verify_Failure() {
    }
    public function test_WhileAuth_Verify_Failure() {
    }
    public function test_WhileAuth_UnknownToken_Verify_Failure() {
    }
}
