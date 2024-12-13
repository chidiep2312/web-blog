<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login to H1 I309</title>
 
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css')}}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" />
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{ asset('assets/images/blog-logo.png')}}" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form id="loginFrm"class="pt-3">
                @csrf
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
                </div>
                <div class="mt-3">
                  <button type="submit"class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                   
                  </div>
                  <a id ="forgot-pass"href="#" class="auth-link text-black">Forgot password?</a>
                </div>
               
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="{{route('register')}}" class="text-primary">Create</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
   
  </div>

  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{ asset('assets/js/off-canvas.js')}}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{ asset('assets/js/template.js')}}"></script>
  <script src="{{ asset('assets/js/settings.js')}}"></script>
  <script src="{{ asset('assets/js/todolist.js')}}"></script>

  <script>
    document.getElementById('loginFrm').addEventListener('submit',function(e){
      e.preventDefault();
      const email=document.getElementById('email').value;
      const password=document.getElementById('password').value;
      let auth_token = localStorage.getItem('auth_token');
      fetch('api/login',{
        method:'POST',
        headers:{
          'X-CSRF-TOKEN':'{{csrf_token()}}',
          'Content-Type':'application/json',
        
        },
        body: JSON.stringify({email,password})
      })
      .then(response=>response.json())
      .then(data=>{
        if(data.token){
          alert("Đăng nhập thành công!"+data.user_id);
          
          localStorage.setItem('auth_token',data.token);
               
          localStorage.setItem('user_id',data.user_id);
       
          window.location.href='/home/'+data.user_id; 
        }else{
          alert("Đăng nhập thất bại!");
        }
      })
      .catch(error=>console.error('Lỗi:',error))
      
    })
  </script> 
  <script>
    document.getElementById('forgot-pass').addEventListener('click',function(){
      window.location.href="/api/reset-password";
    })
  </script>
</body>

</html>
