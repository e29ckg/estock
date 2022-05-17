<nav class="main-header navbar navbar-expand navbar-white navbar-light" id="nav" v-cloak> 
  <ul class="navbar-nav" >
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="./index.php" class="nav-link">
          <i class="fas fa-tachometer-alt mr-2"></i>Home
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../eshop" target="_blank" class="nav-link">
        <i class="fas fa-shopping-cart mr-2"></i>E-SHOP
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Notifications Dropdown Menu -->
      <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> -->
      
      <li class="nav-item">
        <div class="user-panel d-flex">
        <!-- <div class="image">
          <img :src="url_img" class="img-circle elevation-2" alt="User Image">
        </div> -->
        <div class="info">
           <h5>
             {{user.fullname}} 
           </h5>
        </div>
      </div> 
      </li>
      <li class="nav-item">
          <button class="btn btn-danger" @click="logout()">Logout</button>
      </li>
      
    </ul>
    </nav>