<!-- nav.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" id="nav">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="./index.php" class="nav-link">
        <i class="fas fa-tachometer-alt mr-2"></i> Home
      </a>
    </li>
    
  </ul>
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown Menu -->
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span id="navFullname" class="d-none d-md-inline">Guest</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <li class="user-header bg-primary">
          <p id="dropdownFullname">Guest</p>
        </li>
        <li class="user-body">
          <div class="row">
            <div class="col-12 text-center">
              <a href="profile.php" class="btn btn-default btn-flat">
                <i class="fas fa-user mr-1"></i> Profile
              </a>
            </div>
          </div>
        </li>
        <li class="user-footer">
          <a href="javascript:void(0)" id="logoutBtn" class="btn btn-default btn-flat float-right">
            <i class="fas fa-sign-out-alt mr-1"></i> Logout
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const userData = JSON.parse(localStorage.getItem("user_data") || sessionStorage.getItem("user_data") || "{}");
    if (userData.fullname) {
      document.getElementById("navFullname").innerText = userData.fullname;
      document.getElementById("dropdownFullname").innerText = userData.fullname;
    }
  });
</script>