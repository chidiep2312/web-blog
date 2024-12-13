@extends('layout.blog')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/detail-post.css') }}">
<div class="main-panel">
    <div class="content-wrapper">

        <div class="post-detail">
            <h1 class="post-title" style="color:#367517;">{{$post->title}} </h1>

            <div class="dropdown" style="float:right;">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" style="background-color:transparent;color:grey; border: none;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{route('edit-post',$post->id)}}">Edit</a>
                    <a class="dropdown-item" href="#" id="delete-btn">Delete</a>
                </div>
            </div>
            <div class="post-meta">
                <label class="post-tag" style="color:#50A625;">Tag: </label><span> {{$post->tag->tag_name}}</span>
                <label class="post-date" style="color:#50A625;">Created at:</label><span>{{$post->created_at}}</span>
                <label class="post-author" style="color:#50A625;">Người viết:</label><span>{{$post->user->name}}</span>
                <label class="post-views" style="color:#50A625;">Views:</label><span id="views-count">{{$post->view_count}}</span>
                <label class="post-likes" style="color:#50A625;">Likes:</label><span id="likes-count">{{$likesCount}}</span>
            </div>
            <div class="post-image">
                <img style="width:100%;height:400px;object-fit:contain;" src="{{ asset('storage/'.$post->image) }}" alt="Hình ảnh bài đăng">
            </div>
            <div class="post-likes">
                <form id="like-action">
                    @csrf
                    Likes:
                    <button type="submit" style="background-color:#50A625;"><i class="mdi mdi-thumb-up"></i></button>
                </form>
            </div>
            <div class="post-content">
                <h4 style="color:#50A625;">Content: </h4>
                <br>
                {!! $post->content !!}
            </div>

            <div class="post-comments">
                <h3>Comments</h3>
                @if($comments->isEmpty())
                <h4>No comment yet!</h4>
                @else

                @foreach($comments as $c)
                <div class="comment">
                    <div class="comment-author">{{$c->user->name}}</div>
                    <div class="comment-content">{{$c->content}}</div>
                    <div class="comment-date">{{$c->created_at}}</div>
                </div>
                @endforeach
                @endif
                <form id="createComment">
                    @csrf
                    <textarea id="comment_content" name="comment_content" rows="4" placeholder="Write some comment..." required></textarea>
                    <button type="submit" class="btn btn-primary">Comment</button>
                </form>
            </div>
        </div>

    </div>
    @include('components.footer')
</div>
<script>
    document.getElementById('delete-btn').addEventListener('click', function(e) {
        e.preventDefault();
        let auth_token = localStorage.getItem('auth_token');
        let id = localStorage.getItem('user_id');
        const postId = "{{ $post->id }}";
        let confirmDelete = confirm('Are you sure you want to delete this post?');
        if (!confirmDelete) return;
        fetch(`/api/delete-post/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success == true) {
                    alert(data.message);
                    window.location.href = '/my-posts/' + id;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    })
</script>
<script>
    document.getElementById('like-action').addEventListener('submit', function(e) {
        e.preventDefault();
        let auth_token = localStorage.getItem('auth_token');
        const postId = "{{ $post->id }}";
        console.log(postId);
        fetch(`/api/like-post/${postId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Post liked successfully!') {
                    let likesCount = document.getElementById('likes-count');
                    likesCount.textContent = parseInt(likesCount.textContent) + 1;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
<script>
    document.getElementById('createComment').addEventListener('submit', function(e) {
        e.preventDefault();
        const userId = localStorage.getItem('user_id');
        const auth_token = localStorage.getItem('auth_token');
        const postId = "{{ $post->id }}";
        let content = document.getElementById('comment_content').value;
        fetch('/api/create-comment/' + userId + '/' + postId, {
                'method': 'POST',
                'headers': {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
                'body': JSON.stringify({
                    content: content
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success == true) {
                    alert(data.message);
                    window.location.href = '/api/detail-post/' + postId;
                } else {
                    alert(data.message);
                }
            }).catch(error => console.error('Error:', error));
    })
</script>
@endsection