<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\AdController;
use Auth;
use App\User;
use App\Ad;

class AdControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;

    var $postExample1 = [
        'title'=>'Test 1',
        'content'=>'Testing Content #001',
        'contact'=>'Contact info 1'
    ];

    var $postExample2 = [
        'title'=>'Test 2',
        'content'=>'Testing Content #002',
        'contact'=>'Contact info 2'
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
        $this->assertDatabaseHas('ads', $this->postExample1);

    }

    public function test_WhileAuthVerified_StoreOverload_RedirectBackOnCreateStore() {
        // arrange
        $user = $this->verifiedUser;
        $lastOne = [
            'title' => $this->postExample2['title'],
            'user_id' => $user->id,
            'content' => $this->postExample2['content'],
            'contact' => $this->postExample2['contact'],
        ];

        // act
        for($i = 0;$i < 5;$i++)
            $this->actingAs($user)->call('POST',route('ad.store'),$this->postExample1);

        $responseCreate = $this->get(route('ad.create'));
        $responseStore = $this->call('POST',route('ad.store'),$this->postExample2);
        // assert

        $responseCreate->assertStatus(302);
        $responseStore->assertStatus(302);
        $this->assertDatabaseMissing('ads',['title'=>$this->postExample2['title']]);


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
        $this->assertDatabaseHas('ads',$this->postExample2);
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
        $this->assertDatabaseMissing('ads',$this->postExample2);
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
        $this->assertDatabaseMissing('ads',$this->postExample2);
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
        $ad = factory(\App\Ad::class)->create(['title'=>'test','user_id'=>$user->id]);
        $adAsArray = [
            'title'  => $ad->title,
            'content'=>$ad->content,
            'contact'=>$ad->contact,
            'user_id'=>$ad->user_id,
        ];
        $thisAd = Ad::where('user_id',$ad->user_id)->where('content',$ad->content)->where('contact',$ad->contact)->get();
        $this->assertCount(1,$thisAd);

        // act
        $responseDelete = $this->actingAs($user)->delete(route('ad.destroy',$ad->id));

        // assert
        $responseDelete->assertStatus(302);
        $thisAd = Ad::where('user_id',$ad->user_id)->where('content',$ad->content)->where('contact',$ad->contact)->get();

        $this->assertEmpty($thisAd);
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

    public function test_WhileAuth_CreateAdsImage_Success() {
        // arrange
        $user = $this->verifiedUser;

        $image = UploadedFile::fake()->image('avatar.jpg', 1000, 1000);
        $post = factory(Ad::class)->make();

        // act
        $response = $this->actingAs($user)->call('POST',route('ad.store'),[
            'title'     => 'CreateAdsImage',
            'content'   => 'Testing Testing Testing Testing',
            'contact'   => 'Testing Testing Testing',
            'image_url' =>  $image]);

        // assert

        $thisAd = \App\Ad::where('title','CreateAdsImage')->get();
        foreach($thisAd as $ad) {
            $this->assertFileExists(storage_path('/app/public/images/').$ad->image_url);
        }
        $response->assertStatus(201);
    }

    public function test_WhileAuth_TooBig_CreateAdsImage_Redirect() {
        // arrange
        Storage::fake('avatars');
        $user = $this->verifiedUser;
        $image = UploadedFile::fake()->image('avatar.jpg', 2000, 2000)->size(4000);

        // act
        $response = $this->actingAs($user)->call('POST',route('ad.store'),[
            'title'     => 'CreateAdsImage',
            'content'   => 'Testing Testing Testing Testing',
            'contact'   => 'Testing Testing Testing',
            'image_url' =>  $image
        ]);
        // assert
        $response->assertSessionHasErrors();
        $this->assertTrue(session('errors')->has('image_url'));
        $response->assertStatus(302);
    }

    public function test_WhileAuth_NotAFile_CreateAdsFile_Redirect() {
        // arrange
        Storage::fake('text');
        $user = $this->verifiedUser;
        $file = UploadedFile::fake()->create('document.txt', 900);

        // act
        $post = factory(Ad::class)->make();
        $response = $this->actingAs($user)->call('POST',route('ad.store'),['title'=>$post->title,'content'=>$post->content,'contact'=>$post->contact,'image_url'=> $file]);

        // assert
        $response->assertSessionHasErrors();
        $this->assertTrue(session('errors')->has('image_url'));
        $response->assertStatus(302);
    }

    public function test_WhileAuth_ShowAds_Success() {
        // arrange
        $user = factory(User::class)->create();
        $user->verified = 1;
        $user->save();
        $this->be($user);
        $p = factory(\App\Ad::class)->create(['user_id'=>$user->id]);
        $response = $this->get(route('ad.show',$p->id));
        $response->assertStatus(200);
    }
}