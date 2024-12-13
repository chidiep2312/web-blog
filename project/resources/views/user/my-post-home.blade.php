@extends('layout.blog')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<div class="main-panel">
    <div class="content-wrapper">
        <h3 class="font-weight-bold">My post</h3>
        <div class="row">

            @foreach($posts as $newest)
            <div class="col-md-12 grid-margin">
                <div class="post">
                    <div class="post-image">
                        <img src="{{ asset('storage/'.$newest->image) }}" alt="{{ $newest->title }}">
                    </div>
                    <div class="post-content">
                        <h4 class="post-title">{{$newest->title}}</h4>
                        <p class="post-text">
                            {!! Str::limit($newest->content, 2000) !!}
                            <a href="/api/detail-post/{{$newest->id}}">See more...</a>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    <!-- content-wrapper ends -->

    @include('components.footer')
</div>
@endsection