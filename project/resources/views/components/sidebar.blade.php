<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a id="home" class="nav-link" href="#">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Home</span>
      </a>

    </li>
    <li class="nav-item">
      <a id="myPost" class="nav-link" href="#">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">My posts</span>
      </a>
    </li>

    <li class="nav-item">
      <a id="friend" class="nav-link" href="#">
        <i class="icon-layout menu-icon"></i>
        <span class="menu-title">Follows</span>

      </a>

    </li>

  </ul>
</nav>

<script>
  document.getElementById('home').addEventListener('click', function() {

    let userId = localStorage.getItem('user_id');
    if (userId) {
      document.getElementById('home').href = '/home/' + userId;
    }
  });
</script>

<script>
  document.getElementById('friend').addEventListener('click', function() {

    let userId = localStorage.getItem('user_id');
    if (userId) {
      document.getElementById('friend').href = '/friend/' + userId;
    }
  });
</script>

<script>
  document.getElementById('myPost').addEventListener('click', function() {
    let userId = localStorage.getItem('user_id');
    if (userId) {
      document.getElementById('myPost').href = '/my-posts/' + userId;
    }
  });
</script>