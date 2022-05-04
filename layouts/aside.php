<aside class="main-sidebar sidebar-dark-primary elevation-4" >   
<a href="#" class="brand-link">
      <img src="./node_modules/admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>
<div class="sidebar" id="aside" v-cloak>
     

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">         
          
          <!-- <li class="nav-item">
            <a href="./products.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Products
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./catalogs.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                ประเภทสินค้า
                <span class="right badge badge-danger">ประเภทสินค้า</span>
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./units.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                หน่วยนับ
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> -->

          <li class="nav-item " v-for="menu,index in menus">
            <a :href="url_base + menu.menu_url" :class="'nav-link ' + menu.menu_class" @click="set_menu(menu.menu_url)">
              <i :class="menu.menu_icon_class"></i>
              <p>
                {{menu.menu_name}}
                <span class="right badge badge-danger">{{menu.menu_badge}}</span>
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
  </aside>