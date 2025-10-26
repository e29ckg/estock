<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="./node_modules/admin-lte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./node_modules/admin-lte/dist/js/adminlte.min.js"></script>
<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="./node_modules/admin-lte/plugins/select2/js/select2.min.js"></script>
<!-- <script src="./node_modules/vue/dist/vue.min.js"></script> -->

<script src="./dist/js/vue.global.js"></script>
<script src="./node_modules/axios/dist/axios.min.js"></script>
<script src="./dist/js/utils/auth.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  requireAuth(); // ถ้าไม่มี JWT → redirect ไป login

  Vue.createApp({
    data() {
      return {
        url_base: "./",
        menus: [],
        menus_setting: []
      }
    },
    methods: {
      loadMenus() {
        const token = getJWT();
        const currentPage = window.location.pathname.split("/").pop();

        axios.get("./api/auth/get_menus.php", {
          headers: { Authorization: `Bearer ${token}` }
        })
        .then(res => {
          if (res.data.status) {
            this.menus = res.data.menus;
            this.menus_setting = res.data.menus_setting;

            // ✅ เรียก set_menu หลังจากเมนูถูกโหลดแล้ว
            this.set_menu(currentPage);
          }
        })
        .catch(err => {
          console.error("Load menus error:", err);
          handleAuthError(err); // จาก auth.js
        });
      },
      set_menu(url) {
        this.menus.forEach(m => m.menu_class = (m.menu_url === url ? "active" : ""));
        this.menus_setting.forEach(m => m.menu_class = (m.menu_url === url ? "active" : ""));
      }
    },
    mounted() {
      this.loadMenus();
    }
  }).mount("#aside");
});
</script>
