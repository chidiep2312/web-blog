@extends('layout.blog')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/friendlist.css') }}">
<div class="main-panel">
    <div class="content-wrapper">
        <div class="container">
           
            <div class="d-flex flex-wrap">
                @if(!empty($friends))
                    @foreach($friends as $f)
                        <div class="card friend-card me-3 mb-3" style="width: 30%;">
                            <img src="{{ asset('storage/' . $f->friend->avatar) }}" class="card-img-top" alt="Avatar">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $f->friend->name }}</h5>
                                <img src="{{ asset('storage/' . $f->friend->avatar) }}" class="rounded-circle mb-2" alt="Avatar" style="width: 50px; height: 50px;">
                              <form class="sendMessage">
                                @csrf
                                <input type="hidden" class="receiverId" value="{{$f->friend->id}}">
                                <button type="submit" class="btn btn-primary btn-message">Send message</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center">N/A</p>
                @endif
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

    @include('components.footer')
</div>

<script>
    document.addEventListener('DOMContentLoaded',function(){
      
       const forms=document.querySelectorAll('.sendMessage');
       forms.forEach(function(form){
        form.addEventListener('submit',function(e){
        e.preventDefault();
     
        const senderId= localStorage.getItem('user_id');
        const authToken=localStorage.getItem('auth_token');
        const receiverId=this.querySelector('.receiverId').value;
       
       window.location.href="/chat/"+senderId+"/"+receiverId;
      })
    })
        

    })
</script>
@endsection
