<?php

namespace App\Http\Controllers\Api;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function show($userId){
       $user=User::findOrFail($userId);
       $tags=Tag::all();
       $newest_posts= Post::where('user_id', $userId)->latest()->take(3)->get();
       $famous_posts= Post::where('user_id', $userId)->orderBy('view_count','desc')->take(3)->get(); 
      
       return view('user.personal',compact('user','tags','newest_posts','famous_posts'));
    }
    public function tagPosts($id,$userId){
        $tag_posts=Post::where('tag_id',$id)->where('user_id',$userId)->get();
        return response()->json(["success"=>true,"posts"=>$tag_posts]);
    }
    public function showFriendPage($authId,$id){
        $user=User::findOrFail($id);
        $authUser=User::findOrFail($authId);
        $tags=Tag::all();
        $newest_posts= Post::where('user_id', $id)->latest()->take(3)->get();
        $famous_posts= Post::where('user_id', $id)->orderBy('view_count','desc')->take(3)->get(); 
         return view('user.friend-page',compact('user','authUser','tags','newest_posts','famous_posts'));
    }

    //tim kiem nguoi dung theo id,email
    public function search(Request $request){
        $searchInput=$request->input('friend');
        $user=User::where('id',$searchInput)->orWhere('email',$searchInput)->first();

        if($user){
            return response()->json(['success'=>true,'id'=>$user->id,'user'=>$user]);
        }
        return response()->json(['success'=>false,'message'=>'Can not find user']);
    }
    public function  searchUser($id,$authId)
{
    $user=User::findOrFail($id);
    $authUser=User::findOrFail($authId);
   
    return view('user.search',compact('user','authUser'));
}

public function follow(Request $request){
    $followId=$request->input('friendId');
    //kiem tra da follow chua
    $userId=Auth::user()->id;
    if($followId==$userId){
        return response()->json(['success'=>false,'message'=>"You can not follow your sefl!"]);
    }
    $followed=Friendship::where('user_id',$userId)->where('friend_id',$followId)->first();
    if($followed){
        if($followed->status=='pending'){
            return response()->json(['success'=>false,'message'=>"You have followed!"]);
        }
        if($followed->status=='accepted'){
            return response()->json(['success'=>false,'message'=>"You guys already friend!"]);
        }
        if($followed->status=='declined'){
            return response()->json(['success'=>false,'message'=>"You have been blocked!"]);
        }

    }
    $friendShip=new Friendship();
    $friendShip->user_id=$userId;
    $friendShip->friend_id= $followId;
    $friendShip->status='pending';
    $friendShip->save();
     return response()->json(['success'=>true,'message'=>'Follow successfully']);
}
    public function updateAvatar(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }
        $user=User::findOrFail($id);
        $imgPath = $user->avatar;
        if($request->hasFile('avatar')){
            $imgPath=$request->file('avatar')->store('images/user','public');
        }else{
            return response()->json(["success"=>false,"mesage"=>"Update avatar unsuccessfully!"]);
        }
        $user->avatar=$imgPath;
        $user->save();
        return response()->json(["success"=>true,"message"=>"Update avatar successfully!"]);
    }
}
