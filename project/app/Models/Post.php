<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // use HasFactory;

    protected $fillable = ['user_id','title', 'content', 'tag_id', 'image'];

    /**
     * Các bình luận của bài viết.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id','id');
    }

    /**
     * Các tag của bài viết.
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class,'tag_id','id');
    }
    /**
     * Người dùng đã đăng bài viết.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function likes()
{
    return $this->hasMany(Like::class,'post_id','id');
}
public function views()
{
    return $this->hasMany(Like::class,'post_id','id');
}
}
