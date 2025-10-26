<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Stock</title>
  
  <?php include "./layouts/head.php";?>
  
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" >
  <!-- Navbar -->
  <?php include "./layouts/nav.php";?>
  <?php include "./layouts/aside.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="appUsers" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Users</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_user_insert()" ref="m_show">เพิ่มสมาชิก</button>  </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อ</th>
                      <th>username</th>
                      <th>เบอร์โทร</th>
                      <th>สถานะ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.user_id}}</td>
                      <td>{{data.fullname}}<br><small>{{data.dep}}</small></td>
                      <td>
                        {{data.username}}  
                      </td>
                      <td>{{data.phone}}</td>
                      <td>
                        <!-- {{data.st}} -->
                        {{data.role}}
                        <span v-if="data.st == 10" class="badge badge-primary">ปกติ</span>
                        <span v-else class="badge badge-danger">ระงับ</span>
                      </td>
                      <td>
                        <button class="btn btn-warning mx-2" @click="b_user_update(data.user_id)" >Update</button>  
                        <button class="btb btn-danger mx-2" @click="destroy_user(data.user_id)">Delete</button>  
                      </td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>          
        </div>
      </div>
    
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" data-bs-keyboard="false" tabindex="-1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form @submit.prevent="b_user_save()">
            <div class="modal-header">
              <h5 class="modal-title">จัดการผู้ใช้</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_user_close()"><span aria-hidden="true">&times;</span></button>
              
            </div>

            <div class="modal-body">
              <!-- user_id (ซ่อน) -->
              <input type="hidden" v-model="user.user_id">

              <!-- ชื่อ -->
              <div class="form-group mb-3">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" v-model="user.fullname" required>
              </div>

              <!-- username -->
              <div class="form-group mb-3">
                <label>Username</label>
                <input type="text" class="form-control" v-model="user.username" required>
              </div>

              <!-- password -->
              <div class="form-group mb-3">
                <label>Password</label>
                <input type="password" class="form-control" v-model="user.password" :required="user.action === 'insert'">
                <small class="form-text text-muted">
                  {{ user.action === 'update' ? 'ปล่อยว่างถ้าไม่ต้องการเปลี่ยนรหัสผ่าน' : '' }}
                </small>
              </div>

              <!-- email -->
              <div class="form-group mb-3">
                <label>E-mail</label>
                <input type="email" class="form-control" v-model="user.email" required>
              </div>

              <!-- dep -->
              <div class="form-group mb-3">
                <label>แผนก (Department)</label>
                <input type="text" class="form-control" v-model="user.dep">
              </div>

              <!-- phone -->
              <div class="form-group mb-3">
                <label>เบอร์โทร</label>
                <input type="text" class="form-control" v-model="user.phone">
              </div>

              <div class="row">
                <!-- สถานะ -->
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>สถานะ</label>
                    <select class="form-control" v-model="user.st">
                      <option value="10">ใช้งาน</option>
                      <option value="0">ระงับการใช้งาน</option>
                    </select>
                  </div>
                </div>

                <!-- สิทธิ์ -->
                <div class="col-sm-6">
                  <div class="form-group mb-3">
                    <label>สิทธิ์</label>
                    <select class="form-control" v-model="user.role">
                      <option value="admin">ADMIN</option>
                      <option value="member">MEMBER</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="b_user_close()">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- END APP -->
  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>
<script>
requireAuth();
  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        datas_main:'',
        message: 'Hello Vue!',
        user:{
          user_id:'',
          fullname:'',          
          username:'',          
          password:'',          
          email:'',          
          dep:'',          
          phone:'',          
          st:10,          
          fullname:'',          
          role:'member',          
          action:'insert'        
        },
        
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host ;
      this.get_Users();
    },
    methods: {    
      defaultUser() {
        return {
          user_id:'',
          fullname:'',          
          username:'',          
          password:'',          
          email:'',          
          dep:'',          
          phone:'',          
          st:10,          
          fullname:'',          
          role:'member',          
          action:'insert' 
        }
      },  
      get_Users() {
        const token = getJWT();
        axios.get(this.url_base + '/api/users/get_users.php', {
          headers: { Authorization: `Bearer ${token}` }
        })
        .then(response => {
          console.log("API response:", response.data);

          if (response.data.status) {
            this.datas = response.data.respJSON;
          } else {
            Swal.fire({
              icon: 'error',
              title: response.data.message || "ไม่สามารถโหลดข้อมูลผู้ใช้ได้",
              showConfirmButton: false,
              timer: 1500
            });
          }
        })
        .catch(error => {
          console.error("Error loading users:", error);
          Swal.fire({
            icon: 'error',
            title: "เกิดข้อผิดพลาดในการเชื่อมต่อ API",
            showConfirmButton: false,
            timer: 1500
          });
        });
      },
     
      b_user_insert(){
        this.b_user_close();
      },  
      b_user_update(user_id){
        this.$refs.m_show.click();
        const token = getJWT();
        axios.post(this.url_base + '/api/users/get_user.php',{user_id:user_id}, {
          headers: { Authorization: `Bearer ${token}` } })
            .then(response => {
                if (response.data.status) {
                  this.user = {...response.data.respJSON[0], action:'update'};
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      }, 
      
      b_user_save(){
        const token = getJWT();
        axios.post(this.url_base + '/api/users/user_action.php',{user:this.user},{ headers: {"Authorization" : `Bearer ${token}`}})
            .then(response => {
                if (response.data.status) {
                  Swal.fire({
                    icon: 'success',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Users();  
                  this.user = this.defaultUser();    
                }else{
                  Swal.fire({
                    icon: 'error',
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  })
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      destroy_user(user_id) {
        Swal.fire({
          title: 'Are you sure?',
          text: "คุณต้องการลบผู้ใช้นี้หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            // ✅ สร้าง object ใหม่ ไม่แก้ไข this.user โดยตรง
            const payload = {
              user: {
                action: 'delete',
                user_id: user_id
              }
            };
            const token = getJWT();
            axios.post(this.url_base + '/api/users/user_action.php', payload, {
              headers: { "Authorization": `Bearer ${token}` }
            })
            .then(response => {
              console.log("Delete response:", response.data);

              if (response.data.status) {
                Swal.fire({
                  icon: 'success',
                  title: response.data.message,
                  showConfirmButton: false,
                  timer: 1500
                });
                this.get_Users(); // โหลด users ใหม่
              } else {
                Swal.fire({
                  icon: 'error',
                  title: response.data.message,
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            })
            .catch(error => {
              console.error("Delete error:", error);
              Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ API',
                showConfirmButton: false,
                timer: 1500
              });
            });
          }
        });
      },
        b_user_close(){
           this.$refs['m_close'].click();
        }        
      },
  }).mount('#appUsers');
</script>
</body>
</html>
