<?php

namespace App\Http\Controllers\Api;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Like;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MessageController extends Controller
{
    

public  function chat($senderId,$receiverId){
  // lay thong tin nguoi nhan
  $receiver=User::where('id',$receiverId)->first();
  $sender=User::where('id',$senderId)->first();
  if (!$receiver) {
    return redirect()->back()->with('error', 'Receiver not found!');
}
  //lay tin nhan
  $messages = Message::where(function ($query) use ($senderId, $receiverId) {
    $query->where('sender_id', $senderId)
          ->where('receiver_id', $receiverId);
})->orWhere(function ($query) use ($senderId, $receiverId) {
    $query->where('sender_id', $receiverId)
          ->where('receiver_id', $senderId);
})->orderBy('created_at','asc')->get();

  return view('friends.message',compact('receiver','sender','messages'));
}
   
public function sendMess(Request $request, $sender,$receiver){


$validate=Validator::make($request->all(),[
    "content"=>'required|string',
]);
if($validate->fails()){
    return response()->json(["success"=>false,"message"=>"Your message can not send right now!"]);
}
$sendUser=User::where('id',$sender)->first();
$receiveUser=User::where('id',$receiver)->first();
$message=new Message();
$message->content=$request->input('content');
$message->sender_id=$sender;
$message->receiver_id=$receiver;
$message->save();
return response()->json(["success"=>true,"message"=>$message,"sender"=>$sendUser,"receiver"=>$receiveUser]);

}

}