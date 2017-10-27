<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Post;
use App\Postvote;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('post.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $this->validate($request, [
            'title' => 'required|max:200',
            'url' => 'required|url|max:255',
            'description' => 'required',
        ]);
        $postData = $request->input();
        $postData['user_id'] = Auth::user()->id;  //to get current user id
        
        //store post data into post table
        $post = Post::create($postData);
        
        if(!$post)
            return response()->json(array('status' => 'error','message' => 'Something went wrong.'));
        else
            return response()->json(array('status' => 'success','message' => 'Post added succefully.'));
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $post = Post::getPostData($id);
        return view('post.show',['post'=> $post]);
    }
    
    /**
     * Increment up post count.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upPostCount(Request $request,$id)
    {    
        $userId = Auth::user()->id;
        $voteFlag = 1;
        $checkUserPostVote = Postvote::checkUserPostVote($id, $userId, $voteFlag);
        
        
        if(!empty($checkUserPostVote)) {
           return response()->json(array('status' => 'error','message' => 'Sorry! you have already submitted the vote.'));
        } 
        
        /*if($checkUserPostVote) {
            return response()->json(array('status' => 'error','message' => 'Sorry! you have already submitted the vote.'));
        } */       
        
        //update vote
        $updatedData = Post::upPostCount($id);        
        
        if(!$updatedData)
           return response()->json(array('status' => 'error','message' => 'Something went wrong'));
        else
           return response()->json(array('status' => 'success','message' => 'Thanks, for your vote!'));
    }
    
    /**
     * Increment down post count.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downPostCount(Request $request,$id)
    {      
        $userId = Auth::user()->id;
        $voteFlag = 0;
        $checkUserPostVote = Postvote::checkUserPostVote($id, $userId, $voteFlag);
        
        if(!empty($checkUserPostVote)) {
           return response()->json(array('status' => 'error','message' => 'Sorry! you have already submitted the vote.'));
        }
        
        /*if($checkUserPostVote) {
            return response()->json(array('status' => 'error','message' => 'Sorry! you have already vote to this post.'));
        } */       
        
        //update vote
        $updatedData = Post::downPostCount($id);
        
        if(!$updatedData)
           return response()->json(array('status' => 'error','message' => 'Something went wrong'));
        else
           return response()->json(array('status' => 'success','message' => 'Thanks, for your vote!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Show the post data in table
     *
     * @return \Illuminate\Http\Response
     */
    public function posts(Request $request){
        return Post::data($request);
    }
}
