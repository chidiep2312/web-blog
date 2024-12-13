@extends('layout.blog')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<div class="main-panel">
    <div class="content-wrapper">
        <form id="editPost" method="Post" enctype="multipart/form-data">
            @csrf
            <label style="color:#28a745;font-size:26px; "><strong>Edit post</strong></label>
            <div class="form-group">
                <label for="title"><strong>Title</strong></label>
                <input type="text" name="title" class="form-control" value="{{old('title',$post->title)}}" required>
            </div>

            <div class="form-group">
                <label for="tags"><strong>Tag:</strong></label>
                <select name="tag_id" class="form-control">
                    <option value="{{old('tag_id',$post->tag->id)}}" disabled selected>{{$post->tag->tag_name}}</option>
                    @foreach($tags as $t)
                    <option value="{{$t->id}}">{{$t->tag_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image"><strong>Image:</strong></label>
                <input type="file" name="image" class="form-control-file" accept="image/*">
            </div>

            <div class="form-group">
                <label for="content"><strong>Content:</strong></label>
                <div id="editor" class="border"></div>
                <input type="hidden" name="content" id="content" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>

    </div>

    @include('components.footer')
</div>
</div>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    const quill = new Quill('#editor', {
        theme: 'snow',

    });
    quill.root.style.height = '400px';
    document.getElementById('editPost').addEventListener('submit', function(e) {
        e.preventDefault();

        document.querySelector('#content').value = quill.root.innerHTML;
        const form = document.querySelector('#editPost');
        const formData = new FormData(form);
        let auth_token = localStorage.getItem('auth_token');
        let user_id = localStorage.getItem('user_id');
        const postId = "{{ $post->id }}";
        fetch(`/api/update-post/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${auth_token}`
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    alert(data.message);
                    window.location.href = '/api/detail-post/' + data.id;

                } else {

                    alert(data.error);
                }
            })
            .catch(error => {

                alert('Error:' + error);
            });
    });
</script>
@endsection