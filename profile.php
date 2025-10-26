<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Stock | Profile</title>

  <?php include "./layouts/head.php";?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php include "./layouts/nav.php";?>
  <?php include "./layouts/aside.php"; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper" id="app" v-cloak>
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline" v-if="user">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       :src="user.avatar || './uploads/none.png'"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ user.fullname }}</h3>
                <p class="text-muted text-center">{{ user.role || 'User' }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Username</b> <span class="float-right">{{ user.username }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Email</b> <span class="float-right">{{ user.email }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>สมัครเมื่อ</b> <span class="float-right">{{ user.created_at }}</span>
                  </li>
                </ul>

                <button class="btn btn-primary btn-block" @click="openModal">
                  <b>แก้ไขข้อมูล</b>
                </button>

              </div>
            </div>

            <div v-else class="alert alert-warning">
              กำลังโหลดข้อมูลผู้ใช้...
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form @submit.prevent="saveProfile">
                <div class="modal-header">
                  <h5 class="modal-title">แก้ไขข้อมูลผู้ใช้</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <div class="form-group">
                    <label>ชื่อ - นามสกุล</label>
                    <input type="text" class="form-control" v-model="form.fullname" required>
                  </div>
                  <div class="form-group">
                    <label>อีเมล</label>
                    <input type="email" class="form-control" v-model="form.email" required>
                  </div>
                  <div class="form-group">
                    <label>เบอร์โทร</label>
                    <input type="text" class="form-control" v-model="form.phone">
                  </div>
                  <div class="form-group">
                    <label>แผนก</label>
                    <input type="text" class="form-control" v-model="form.dep">
                  </div>
                  <div class="form-group">
                    <label>รหัสผ่านใหม่ (ถ้าเปลี่ยน)</label>
                    <input type="password" class="form-control" v-model="form.password">
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                  <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
              </form>
            </div>
          </div>
        </div>


      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>

<script>
  requireAuth(); // redirect ถ้าไม่มี token

  Vue.createApp({
    data() {
      return {
        user: null,
        apiUrl: "./api/auth/profile.php",
        updateUrl: "./api/auth/update_profile.php", // ✅ API สำหรับอัปเดต
        form: { fullname: "", email: "", phone: "", dep: "", password: "" }
      }
    },
    mounted() {
      this.fetchProfile();
    },
    methods: {
      async fetchProfile() {
        try {
          const token = getJWT();
          const res = await axios.get(this.apiUrl, {
            headers: { Authorization: `Bearer ${token}` }
          });
          if (res.data.status === "success") {
            this.user = res.data.user;
          } else {
            Swal.fire({ icon: "error", title: res.data.message || "โหลดข้อมูลไม่สำเร็จ" });
          }
        } catch (err) {
          console.error("Profile error:", err);
          if (err.response && err.response.status === 401) {
            clearAuth();
            window.location.href = "./login.php";
          }
        }
      },
      openModal() {
        // เตรียมข้อมูลในฟอร์ม
        this.form = this.user ? {
          fullname: this.user.fullname || "",
          email: this.user.email || "",
          phone: this.user.phone || "",
          dep: this.user.dep || "",
          password: ""
        } : { fullname: "", email: "", phone: "", dep: "", password: "" };
        $("#profileModal").modal("show");
      },
      async saveProfile() {
        try {
          const token = getJWT();
          const res = await axios.post(this.updateUrl, this.form, {
            headers: { Authorization: `Bearer ${token}` }
          });
          if (res.data.status === "success") {
            Swal.fire({ icon: "success", title: "อัปเดตข้อมูลสำเร็จ" });
            $("#profileModal").modal("hide");
            this.fetchProfile(); // refresh ข้อมูล
          } else {
            Swal.fire({ icon: "error", title: res.data.message || "อัปเดตไม่สำเร็จ" });
          }
        } catch (err) {
          console.error("Update error:", err);
          Swal.fire({ icon: "error", title: "เกิดข้อผิดพลาด" });
        }
      }

    }
  }).mount("#app");
</script>

</body>
</html>