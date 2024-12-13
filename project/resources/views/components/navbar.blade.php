<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
<style>

.circle-button {
    width: 50px;             
    height: 50px;            
    border-radius: 50%;       
    background-color: #fff; 
    display: flex;             
    justify-content: center;  
    align-items: center;        
    border: none;             
    cursor: pointer;           
    transition: transform 0.3s ease, background-color 0.3s ease;
}
.circle-button i{
  color: #28a745; 
}
.circle-button:hover {
    background-color: #28a745;
}
.circle-button:hover i {
    color: white; 
}

</style>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href=""><img id="logo" src="{{ asset('assets/images/blog-logo.png')}}" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href=""><img src="{{ asset('assets/images/logo-mini.svg')}}" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <a href="{{route('create-post')}}">
        <button class="circle-button" type="button" data-toggle="minimize">
       <i class="mdi mdi-lead-pencil"></i>
        </button></a>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>

             <form id="search-friend">
              @csrf
              <input type="text" name="friend" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
              <button type="submit" style="display: none;"></button>
            </form>

            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="followDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-account"></i>
              <span style="color:#BB0000"id="count"class="count"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="followDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">You have new followers!</p>
      
              <a class="dropdown-item preview-item" id="notification-list">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal"></h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
         
            </div>
          </li>

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
        
    <a id="user_page" class="dropdown-item" href="#">
        <button type="button" style="background-color: transparent; border: none;">
            <i class="mdi mdi-account"></i> User information
        </button>
    </a>
    <a id="setPass" class="dropdown-item" href="#">
        <button type="button" style="background-color: transparent; border: none;">
            <i class="mdi mdi-key"></i> Set password
        </button>
    </a>
             <form id="logout">
             @csrf
        <span class="dropdown-item">
        <button type="submit" style="background-color: transparent; border: none;">
            <i class="ti-power-off text-primary"></i>
            Logout
        </button>
          </span>
   
           </form>
         
            </div>
          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
<script>
  document.getElementById('setPass').addEventListener('click',function(e){
    e.preventDefault();

        window.location.href = '/set-password' ;
     
  })
</script>
    <script>
      document.getElementById("search-friend").addEventListener("submit",function(e){
        e.preventDefault();
        const userId= document.querySelector('input[name="friend"]').value;
        let auth_token = localStorage.getItem('auth_token');
        let user_id = localStorage.getItem('user_id');
       fetch('/api/search-user',{
        method:"POST",
        headers:{
          "X-CSRF-TOKEN":'{{ csrf_token() }}',
          "Content-Type": "application/json",
          'Authorization': `Bearer ${auth_token}`
        },
        body: JSON.stringify({ friend: userId })
       })
       .then(response=>response.json())
       .then(data=>{
        if(data.success==true){
          const userId=data.id;
          alert("Find successfully");
          window.location.href='/api/search-user-page/'+userId+'/'+user_id;
        }else{
          alert("Can not find user!");
        }
       }).catch(error => console.error("Error:", error));
      })
    </script>
    <script>
      document.getElementById("logout").addEventListener("submit",function(e){
        e.preventDefault();
        let auth_token = localStorage.getItem('auth_token');
     
        fetch('/api/logout', {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN":'{{ csrf_token() }}',
             
                'Authorization': `Bearer ${auth_token}`
            },
        })
        .then(response => response.json())
        .then(data => {
          if (data.status=="success") {
            alert(data.message);
            localStorage.removeItem('user_id');
            window.location.href = "/";
        } else {
            alert(data.message || "Đăng xuất thất bại!");
        }
        })
        .catch(error => console.error("Error:", error));
    });

    </script> 

<script>
    document.addEventListener('DOMContentLoaded', function() {
      
        let userId = localStorage.getItem('user_id');
        if (userId) {
          
            document.getElementById('user_page').href = '/api/personal-page/' + userId;
        }
    });
</script>

<script>
    document.getElementById('logo').addEventListener('click', function(e) {
      e.preventDefault();
        let userId = localStorage.getItem('user_id');
        if (userId) {
          
           window.location.href = '/home/' + userId;
        }
    });
</script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
  const notificationList = document.getElementById("notification-list");
  const notificationCount = document.querySelector(".count");
  let userId = localStorage.getItem('user_id');
  async function fetchNotifications() {
    try {
      const response = await fetch(`/api/follow-notifications/${userId}`);
      const data = await response.json();

      if (data.success && data.users.length > 0) {
        notificationCount.innerText = data.users.length;

        notificationList.innerHTML = "";
        data.users.forEach((notification) => {
          const user = notification.user;
          const item = `
          <div class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <div class="preview-icon bg-info">
                  <i class="ti-user mx-0"></i>
                </div>
              </div>
              <div class="preview-item-content">
              <form id="follow-back">
              @csrf
                <h6 class="preview-subject font-weight-normal">${user.name} started following you</h6>
                <input type="hidden" value="${user.id}" name="user_id" id="user_id"></input>
                <button type="submit" class="btn btn-sm btn-primary">Follow back</button>
                </form>
              </div>
            </div>
          `;
          notificationList.insertAdjacentHTML("beforeend", item);
        });
      } else {
        notificationList.innerHTML = `<p class="text-muted">No new followers!</p>`;
        notificationCount.innerText = 0;
      }
    } catch (error) {
      console.error("Error fetching notifications:", error);
    }
  }
  setInterval(fetchNotifications, 10000);
  fetchNotifications();
});

</script>

<script>
 document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'follow-back') {
        e.preventDefault();
        let auth_token = localStorage.getItem('auth_token');
        let userId = localStorage.getItem('user_id');
        let friendId = e.target.querySelector('#user_id').value;

        fetch(`/api/follow-back/${userId}`, {
            'method': "POST",
            'headers': {
                'X-CSRF-TOKEN': '{{csrf_token()}}',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${auth_token}`
            },
            body: JSON.stringify({ friendId: friendId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                alert(data.message);
            } else {
                alert("Something went wrong!");
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
});

</script>
<script>
  document.addEventListener('DOMContentLoaded',function(){
    let id=localStorage.getItem('user_id');
    let auth_token=localStorage.getItem('auth_token');
    fetch('/api/load-avatar/'+id,{
      headers:{
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${auth_token}`
      }
    }).then(response=>response.json())
    .then(data=>{
      if(data.success==true){
          let avatarImg = document.querySelector('#profileDropdown img');
                if (avatarImg && data.avatar) {
                    avatarImg.src ='{{ asset("storage/") }}/' + data.user.avatar;
                }
         
      } else {
                console.error('Failed to load avatar:', data.message);
            }
    })
  })
</script>