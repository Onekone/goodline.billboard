<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ad;

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
        $posts = $this->posts->paginate(5);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
       // $this->validate($request,array('title' => 'required|max:100','content'=>'required|max:800|min:20','contact'=>'required|max:100'));

        $this->posts->create([
            'title' => $request['title'],
            'content' => $request['content'],
        ]);
        //title = $request['title'];
        //$this->posts->content = $request['content'];
        //$this->posts->contact = $request->contact;

        $user = Auth::user();

//        if ($user)
//        {
//            $this->posts->author = $user->id;
//            $post->save();
//        }
//        else
//        {
//            return redirect()->route('ads.index');
//        }
        return redirect()->route('ads.show');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Ad::find($id);

        return view('ads.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts = $this->posts->find($id);
        return view('ads.edit')->withPost($posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request = $request->all();
        $this->posts->where('id', $id)->update([
            'title' => $request['title'],
            'content' => $request['content'],
        ]);
        return redirect()->route('ad.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->posts->find($id);
        $post->delete();
        return redirect()->route('ad.index');
    }
}
