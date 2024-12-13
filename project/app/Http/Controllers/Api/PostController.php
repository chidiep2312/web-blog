<?php

namespace App\Http\Controllers\Api;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
    //
    public function createPost(){
        $tags=Tag::all();
        return view ('post.create',compact('tags'));
    }

    public function save(Request $request){
       
        $validator= Validator::make($request->all(),[
            'title'=>'string|required',
           
            'content' => 'required|string',
            'tag_id' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],422);
        }
        $post=new Post();
        if($request->hasFile('image')){
            $imgPath=$request->file('image')->store('images/post','public');
        }else{
            $imgPath='';
        }

        $post->user_id=Auth::user()->id;
        $post->title=$request->title;
        $post->tag_id=$request->tag_id;
        $post->content=$request->content;
        $post->image=$imgPath;
      
        $post->save();
        return response()->json(['id'=>$post->id,'success'=>true,'message'=>'Created new post successfully'],201);
    }

    public function detail($id){
        $post=Post::findOrFail($id);
        $comments=Comment::where('post_id',$id)->get();
        $post->increment('view_count');
        $likesCount = Like::where('post_id', $id)->count();
      
        return view('post.detail',compact('post','likesCount','comments'));
    }



public function likePost(Request $request, $postId)
{
    $userId = Auth::user()->id;
    $existingLike = Like::where('user_id', $userId)->where('post_id', $postId)->first();

    if ($existingLike) {
        return response()->json(['message' => 'You already liked this post'], 400); // Đã like
    }

    // Tạo like mới
    Like::create([
        'user_id' => $userId,
        'post_id' => $postId,
    ]);

    return response()->json(['message' => 'Post liked successfully!'], 200); // Like thành công
}
public  function edit($id){
    $post=Post::findOrFail($id);
    $tags=Tag::all();
    return view('post.edit',compact('post','tags'));
}
public function update(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'title' => 'string|required',
        'content' => 'nullable|string',
        'tag_id' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if($validator->fails()){
        return response()->json(['error' => $validator->errors()], 422);
    }

    $post = Post::findOrFail($id);

    if($request->hasFile('image')){
        $imgPath = $request->file('image')->store('images/post', 'public');
    } else {
       
        $imgPath = $post->image;
    }

    $post->title = $request->title;
    $post->content = $request->content ?? $post->content; 
    $post->tag_id = $request->tag_id ?? $post->tag_id;
    $post->image = $imgPath;
    $post->user_id = Auth::user()->id; 
    $post->save();
    return response()->json([
        'id' => $post->id,
        'success' => true,
        'message' => 'Update successfully!'
    ], 200);
}
     public function deletePost($id){
        //lay bai viet
        $post=Post::findOrFail($id);
        //kiem tra nguoi dung có là nguoi viet bai ko
        $user_id=Auth::user()->id;
        if($user_id!=$post->user_id){
            return response()->json(['success'=>false,'message'=>'You can not delete this post'],403);
        }
        $post->delete();

        return response()->json(['success'=>true,'message'=>'You delete post successfully'],200);
     }

     public function getTagPost($id)
{
    $posts = Post::where('tag_id', $id)->with('tag')->get();

    if ($posts->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No posts found for the selected tag.'
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Get posts successfully!',
        'posts' => $posts
    ]);
}

      public function createComment(Request $request, $userId,$postId){
        $validator=Validator::make($request->all(),[
            'content'=>'nullable|string',
        ]);
        if($validator->fails()){
            return response()->json(['success'=>false,'message'=>'Wrong input!']);
        }
        $comment=new Comment();
        $comment->post_id=$postId;
        $comment->user_id=$userId;
        $comment->content=$request->input('content');
        $comment->save();
        return response()->json(['success'=>true,'message'=>'Comment successfully!']);
    }

}
