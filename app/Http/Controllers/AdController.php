<?php

namespace App\Http\Controllers;

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
        //
        return view('ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, array(
            'title' => 'required|max:100',
            'content' => 'required|max:800|min:20',
            'contact' => 'required|max:100',
            'image_url' => 'nullable|image'));
        $userId = Auth::user()->id;

        if ($request->image_url) {
            $photoName = md5(time() . '.' . $request->image_url->getClientOriginalExtension());
            $request->image_url->move(public_path('images'), $photoName);
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
        return redirect()->route('ad.show', $ad->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Ad::find($id);
        $username = User::where('id', $post->user_id)->get()[0]->name;

        return view('ads.show')->withPost($post)->withUsername($username);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $auth = Auth::user();
//        $post = Ad::find($id);
//        $username = User::where('id', $post->user_id)->get()[0]->name;
//        if($auth && $auth->name == $username){
                $posts = $this->posts->find($id);
                return view('ads.edit')->withPost($posts);
//        } else
//                return redirect()->route('ad.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->image_url) {
            $photoName = md5(time() . '.' . $request->image_url->getClientOriginalExtension());
            $request->image_url->move(public_path('images'), $photoName);
        } else {
            $photoName = null;
        }

            $this->validate($request, array('title' => 'required|max:100', 'content' => 'required|max:800|min:20', 'contact' => 'required|max:100', 'images_url' => 'required|images|mimes:jpeg,bmp,png'));
            $user = Auth::user();
            $request = $request->all();
            $this->posts->where('id', $id)->update([
                'title' => $request['title'],
                'content' => $request['content'],
                'contact' => $request['contact'],
                'image_url' => $photoName,
                'user_id' => $user->getAuthIdentifier(),
            ]);
            return redirect()->route('ad.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->posts->find($id);
        $post->delete();
        return redirect()->route('ad.index');
    }
}
