@extends('layout.blog')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<div class="main-panel">
    <div class="content-wrapper">
        <h3 class="font-weight-bold">Most likes</h3>
        <div class="row">
            @foreach($mostLikes as $like)
            <div class="col-md-4 grid-margin">
                <div class="post">
                    <div class="post-image">
                        <img style="width:300px; height:300px;  object-fit: cover;" src="{{ asset('storage/'.$like->image) }}"  alt="{{ $like->title }}">
                    </div>
                    <div class="post-content">
                        <h4 class="post-title">{{$like->title}}</h4>
                        <p class="post-text">
                        {!! Str::limit($like->content, 100) !!}
                            <a href="/api/detail-post/{{$like->id}}">See more...</a>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
          
        </div>

        <h3 class="font-weight-bold">Most views</h3>
        <div class="row">
        @foreach($mostViews as $view)
            <div class="col-md-4 grid-margin">
                <div class="post">
                    <div class="post-image">
                        <img style="width:300px; height:300px;  object-fit: cover;" src="{{ asset('storage/'.$view->image) }}"  alt="{{ $view->title }}">
                    </div>
                    <div class="post-content">
                        <h4 class="post-title">{{$view->title}}</h4>
                        <p class="post-text">
                        {!! Str::limit($view->content, 100) !!}
                            <a href="/api/detail-post/{{$view->id}}">See more...</a>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <h3 class="font-weight-bold">Newest</h3>
        <div class="row">
         
            @foreach($newest_posts as $newest)
            <div class="col-md-12 grid-margin">
                <div class="post">
                    <div class="post-image">
                    <img src="{{ asset('storage/'.$newest->image) }}"  alt="{{ $newest->title }}">
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
