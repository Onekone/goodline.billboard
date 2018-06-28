<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdRequest;
use DB;
use Illuminate\Support\Facades\Input;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Ad;
use Auth;

//use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $posts;

    public function __construct(Ad $posts)
    {
        $this->posts = $posts;
    }

    public function index()
    {
        $posts = $this->posts->latest()->paginate(4);
        return view('ads.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateAdRequest $request)
    {
        $p = $this->validate($request, [
            'title' => 'required|max:100',
            'content' => 'required|max:800|min:20',
            'contact' => 'required|max:100',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $userId = Auth::user()->id;

        if ($request->image_url) {

            try {

                $photoName = md5(time() ). '.' . $request->image_url->getClientOriginalExtension();
                $request->image_url->move(storage_path('app/public/images'), $photoName);
            }
            catch (\Exception $e) {dd($e->getTrace()[0]);}
            catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {$photoName = null;}
            catch (\Symfony\Component\HttpFoundation\File\Exception\IniSizeFileException $e) {$photoName = null;}
        } else {
            $photoName = null;
        }


        $ad = $this->posts->create([
            'title' => $request['title'],
            'content' => $request['content'],
            'contact' => $request['contact'],
            'user_id' => $userId,
            'image_url' => $photoName,
        ]);
        return view('ads.show')->withPost($ad)->withUsername($ad->user->name??'deleted');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Ad::find($id);
        if ($post)
        {
            $username = 'deleted';

            $user = $post->user;
            if ($user)
                $username = $user->name;

            return view('ads.show')->withPost($post)->withUsername($username);
        }
        else {
            abort(410);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts = $this->posts->findOrFail($id);
        return view('ads.create')->withPosts($posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdRequest $request, $id)
    {
        $asset = Ad::findOrFail($id);
        $photoName = $asset->image_url;

        if ($request->image_url && !$request->delete_image) {
            try {
                $photoName = md5(time() ). '.' . $request->image_url->getClientOriginalExtension();
                $request->image_url->move(storage_path('/app/public/images'), $photoName);
            }
            catch (\Exception $e) {dd($e->getTrace()[0]);}
            catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {$photoName = null;}
            catch (\Symfony\Component\HttpFoundation\File\Exception\IniSizeFileException $e) {$photoName = null;}
        }

        if ($request->delete_image) {
            $photoName = NULL;
        }

        $user = Auth::user();

        $this->posts->where('id', $id)->update([
            'title' => $request['title'],
            'content' => $request['content'],
            'contact' => $request['contact'],
            'image_url' => $photoName,
            'user_id' => $user->getAuthIdentifier(),
        ]);

        return view('ads.show')->withPost($asset)->withUsername($asset->user->name??'deleted');//redirect()->route('ad.show',$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Ad::findOrFail($id);
        $post->delete();
        return redirect()->route('ad.index');
    }
}
