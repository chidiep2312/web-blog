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

class HomeController extends Controller
{
  public function setPass()
  {
    return view('auth.set-pass');
  }

  public function home($userId)
  {
    //lay nguoi dung hien tai

    $user = User::findOrFail($userId);
    //lay tat ca nguoi theo doi
    $followUsers = $user->friends;
    //tao collection chua cac bai viet most likes
    $mostLikes = collect();
    foreach ($followUsers as $followUser) {
      $mostLike_posts = $followUser->posts()->orderBy('like_count', 'desc')->take(3)->get();
      $mostLikes = $mostLikes->merge($mostLike_posts);
    }
    $mostLikes = $mostLikes->sortByDesc('like_count')->take(3);

    //tao collection chua cac bai viet most views
    $mostViews = collect();
    foreach ($followUsers as $followUser) {
      $mostViews_posts = $followUser->posts()->orderBy('view_count', 'desc')->take(3)->get();
      $mostViews = $mostViews->merge($mostViews_posts);
    }
    $mostViews = $mostViews->sortByDesc('view_count')->take(3);

    //lay 5 bai viet gan day cua all follow
    $newest_posts = collect();
    foreach ($followUsers as $followUser) {
      $newest = $followUser->posts()->orderBy('created_at', 'desc')->take(5)->get();
      $newest_posts = $newest_posts->merge($newest);
    }
    $newest_posts = $newest_posts->sortByDesc('created_at')->take(5);

    return view('home', compact('mostLikes', 'mostViews', 'newest_posts'));
  }

  public function friendList($id)
  {
    //lay ng minh theo doi
    $friends = Friendship::where(function ($query) use ($id) {
      $query->where('user_id', $id)->orWhere(function ($subquery) use ($id) {
        $subquery->where('friend_id', $id)->where('status', 'accepted');
      });
    })->with('user', 'friend')->get();


    return view('friends.list', compact('friends', 'id'));
  }
  //bai viet cua minh
  public function myPosts($id)
  {
    $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();
    return view('user.my-post-home', compact('posts'));
  }
  //thong bao co follow moi
  public function followNotification($id)
  {
    // Lấy ID người dùng hiện tại
    $userId = $id;

    // Lấy danh sách những người theo dõi với trạng thái 'pending'
    $followUsers = Friendship::where('friend_id', $userId)
      ->where('status', 'pending')
      ->with('user')
      ->orderByDesc('created_at')
      ->get();

    if ($followUsers->isEmpty()) {
      return response()->json(['success' => false, 'message' => 'No new follow notifications!']);
    }

    return response()->json([
      'success' => true,
      'message' => 'Get all notifications successfully!',
      'users'   => $followUsers,
    ]);
  }
  public function followBack($userId, Request $request)
  {
    $friendId = $request->input('friendId');
    //lay ban ghi friendship có userId là friendId và friendId lầ user id
    $status = Friendship::where('user_id', $friendId)->where('friend_id', $userId)->first();
    $status->status = 'accepted';
    //update status==accepted
    $status->save();
    return response()->json(['success' => true, 'message' => 'Follow back successfully!']);
  }

  public function homeLoad($id)
  {
    $user = User::findOrFail($id);

    return response()->json(['success' => true, 'user' => $user]);
  }
}
