@extends('layout.blog')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/user-page.css') }}">

<div class="main-panel">
    <div class="content-wrapper">

        <div class="account-info card shadow-lg p-4">

            <div class="text-center mb-4">
                <div class="avatar-container position-relative d-inline-block">
                    <img
                        src="{{asset('storage/' . $user->avatar) }}"
                        alt="User Avatar"
                        class="avatar rounded-circle" />

                </div>
            </div>

            <div class="account-details mb-4">
                <p><strong>Id:</strong> {{$user->id}}</p>
                <p><strong>User Name:</strong> {{$user->name}}</p>
                <p><strong>Email:</strong> {{$user->email}}</p>
                <p><strong>Joined Date:</strong> {{$user->created_at}}</p>
            </div>


            <form id="update-avatar" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="avatar" class="form-label">Change Avatar:</label>
                    <input
                        type="file"
                        name="avatar"
                        id="avatar"
                        accept="image/*"
                        class="form-control file-input"
                        required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Update Avatar
                    </button>
                </div>
            </form>
        </div>
        @if(!empty($newest_posts))
        <div class="posts-section">
            <h3>Newest posts</h3>
            <div class="posts-list">
                @foreach($newest_posts as $post)
                <div class="post-card">
                    <div class="post-image">
                        <img style="width:300px; height:300px;  object-fit: cover;" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
                    </div>
                    <div class="post-content">
                        <h4><a href="/api/detail-post/{{$post->id}}">{{ $post->title }}</a></h4>
                        <p>{!! Str::limit($post->content, 100) !!}</p>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <h3>No post have been posted!</h3>
        @endif
        @if(!empty($famous_posts))
        <div class="posts-section">
            <h3>Most famous posts</h3>
            <div class="posts-list">
                @foreach($famous_posts as $post)
                <div class="post-card">
                    <div class="post-image">
                        <img style="width:300px; height:300px;  object-fit: cover;" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
                    </div>
                    <div class="post-content">
                        <h4><a href="/api/detail-post/{{$post->id}}">{{ $post->title }}</a></h4>
                        <p>{!! Str::limit($post->content, 100) !!}</p>

                    </div>
                </div>
                @endforeach

            </div>

        </div>
        @else
        <h3>No post have been posted!</h3>
        @endif

        <div class="posts-section">
            <h3>Tag posts</h3>
            <div class="tags-selection">
                <label for="tags">Select:</label>
                <select id="tags" class="tags-dropdown">
                    @foreach($tags as $t )
                    <option id="tag_id" value="{{$t->id}}">{{$t->tag_name}}</option>
                    @endforeach
                </select>
            </div>

            <div id="post-list" class="posts-list">


            </div>
        </div>

    </div>
    <!-- content-wrapper ends -->

    @include('components.footer')
</div>

<script>
    document.getElementById('update-avatar').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = localStorage.getItem('user_id');
        const auth_token = localStorage.getItem('auth_token');
        const form = document.querySelector('#update-avatar');
        const formData = new FormData(form);
        fetch(`/api/update-avatar/${id}`, {
                "method": "POST",
                "headers": {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',

                    'Authorization': `Bearer ${auth_token}`
                },
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success == true) {
                    alert(data.message);
                } else {
                    alert(data.error);
                }
            }).catch(error => console.error("Error:", error));
    })
</script>

<script>
    document.getElementById('tags').addEventListener('change', function(e) {
        e.preventDefault();
        const tagId = document.getElementById('tags').value;
        let id = localStorage.getItem('user_id');
        fetch('/api/personal-tag-post/' + tagId + '/' + id, {
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                const postList = document.getElementById('post-list');
                postList.innerHTML = '';
                if (data.success) {
                    alert(data.message);
                    data.posts.forEach(post => {
                        const postCard = `
                    <div class="post-card" }">
                        <div class="post-image">
                            <img style="width:300px; height:300px; object-fit: cover;" src="/storage/${post.image}" alt="${post.title}">
                        </div>
                        <div class="post-content">
                            <h4><a href="/api/detail-post/${post.id}">${post.title}</a></h4>
                            <p>${post.content.substring(0, 100)}...</p>
                        </div>
                    </div>`;
                        postList.innerHTML += postCard;
                    });
                } else {
                    alert(data.message);
                    postList.innerHTML = '<p>No posts found for the selected tag.</p>';
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
@endsection