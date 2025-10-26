<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="./node_modules/admin-lte/dist/img/AdminLTELogo.png"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">E-Stock</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar" id="aside" v-cloak>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false">

        <!-- Dynamic Main Menus -->
        <li class="nav-item" v-for="(menu, index) in menus" :key="index">
          <a :href="url_base + menu.menu_url"
             :class="'nav-link ' + menu.menu_class"
             @click="set_menu(menu.menu_url)">
            <i :class="menu.menu_icon_class"></i>
            <p>
              {{ menu.menu_name }}
              <span v-if="menu.menu_badge" class="right badge badge-danger">
                {{ menu.menu_badge }}
              </span>
            </p>
          </a>
        </li>

        <!-- Settings Submenu -->
        <li class="nav-item menu-is-opening menu-open">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              ตั้งค่า
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="display: block;">
            <li class="nav-item" v-for="(ms, idx) in menus_setting" :key="'setting-' + idx">
              <a :href="url_base + ms.menu_url"
                 :class="'nav-link ' + ms.menu_class"
                 @click="set_menu(ms.menu_url)">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ ms.menu_name }}</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>