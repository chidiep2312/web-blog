@extends('layout.blog')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
<div class="main-panel">
    <div class="content-wrapper">
    @if(($mostLikes->isEmpty() && $mostViews->isEmpty() && $newest_posts->isEmpty()))
        <h3 class="font-weight-bold">What is H1 13LOG ?</h3>
                
                                <img src="{{ asset('assets/images/blog-logo.png')}}">
                                <h4 class="post-title" style="margin-top:10px;">H1 13LOG</h4>
                                <p>
                                Is a social media platform focused on blogging, where users can register an account, log in, write blogs, and interact with the community through various features such as liking, 
                                commenting, following, and messaging.
                                </p>
                                <p>
                                Main Features:
                                </p>
                                <p>
                                1. Register/Login: Create a new account and log in to use the website's features.<br>
                                2. Create Post: Write, edit, and delete blog posts, add tags (topics) to categorize the posts.<br>
                                3. View Post Details: Display the number of likes and views of a post, and allow liking or commenting on the post.<br>
                                4. Profile Page: Display personal information and the posts the user has published.<br>
                                5. Home Page: Display posts from users you follow, with the ability to search users by name or ID.<br>
                                6. Follow Users: Search for and follow other users to receive their latest posts.<br>
                                7. Messaging: Send and receive messages with other users.<br>
                                8. Interact with Posts: Like, comment, and share posts.<br>

                                </p>
                    
        @endif
        @if(!empty($mostLikes) && $mostLikes->count() > 0)
            <h3 class="font-weight-bold">Most likes</h3>
            <div class="row">
                @foreach($mostLikes as $like)
                    <div class="col-md-4 grid-margin">
                        <div class="post">
                            <div class="post-image">
                                <img style="width:300px; height:300px; object-fit: cover;" src="{{ asset('storage/'.$like->image) }}" alt="{{ $like->title }}">
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
        @endif

        @if(!empty($mostViews) && $mostViews->count() > 0)
            <h3 class="font-weight-bold">Most views</h3>
            <div class="row">
                @foreach($mostViews as $view)
                    <div class="col-md-4 grid-margin">
                        <div class="post">
                            <div class="post-image">
                                <img style="width:300px; height:300px; object-fit: cover;" src="{{ asset('storage/'.$view->image) }}" alt="{{ $view->title }}">
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
        @endif

        @if(!empty($newest_posts) && $newest_posts->count() > 0)
            <h3 class="font-weight-bold">Newest</h3>
            <div class="row">
                @foreach($newest_posts as $newest)
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
        @endif
    </div>

    <!-- content-wrapper ends -->

    @include('components.footer')
</div>

@endsection
