@extends('layout.blog')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/search-user-page.css') }}">

<div class="main-panel">
    <div class="content-wrapper">
        <div class="user-profile-container">
            <div class="user-card">
                <!-- Ảnh đại diện -->
                <div class="avatar">
                    <img src="https://via.placeholder.com/150" alt="User Avatar">
                </div>
                @if($user)
            
                <div class="user-info">
                   <a href="/api/friend-page/{{$authUser->id}}/{{$user->id}}"><h2 class="user-name">{{$user->name}}</h2></a> 
                    <p class="user-email">{{$user->email}}</p>
                    <p class="user-id"><strong>ID:</strong> {{$user->id}}</p>
                </div>
                 @else
                 <div class="user-info">
                    N/A
                </div>
                @endif
                <div class="action-buttons">
                    <form id="follow-action">
                        @csrf
                        <input id="friendId" type="hidden" value="{{$user->id}}">
                         
        @php
            $friend = $authUser->friends()->where('friend_id', $user->id)->first(); 
        @endphp

        @if($friend)
            <button id="friend-request-btn" style="background-color:blue;" class="btn-add-friend">{{ $friend->pivot->status }}</button> 
        @else
            <button id="friend-request-btn" class="btn-add-friend">Follow</button>
        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

    @include('components.footer')
</div>
<script>
    document.getElementById("follow-action").addEventListener("submit",function(e){
        e.preventDefault();
        const friendId=document.getElementById('friendId').value;
        let auth_token = localStorage.getItem('auth_token');
        fetch('/api/follow-user',{
            method:"POST",
            headers:{
                "X-CSRF-TOKEN":'{{ csrf_token() }}',
                "Content-Type": "application/json",
               'Authorization': `Bearer ${auth_token}`
            },
            body:JSON.stringify({ friendId: friendId })
        }).then(response=>response.json()).then(data=>{
           if(data.success==true){
            const followButton = document.getElementById('friend-request-btn');
            followButton.innerText = 'Following';
            followButton.style.backgroundColor = '#4B49AC'; 
            followButton.style.color = 'white';
           }else{
            alert(data.message);
           }
        }).catch(error => console.error("Error:", error));
    })
</script>
@endsection