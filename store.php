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
  <div class="content-wrapper" id="appStore" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Store</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Store</li>
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
                <h5 class="card-title">Store</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_store_insert()" ref="m_show">เพิ่มร้านค้า</button>                  
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อร้านค้า</th>
                      <th>รายละเอียด</th>
                      <th>phone</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in stores">
                     
                      <td>{{data.str_id}}</td>
                      <td>
                        {{data.str_name}}
                      </td>
                      <td>
                        {{data.str_detail}} 
                      </td>
                      <td>{{data.str_phone}}</td>
                      <td>
                        <button class="btn btn-warning" @click="b_store_update(data.str_id)" >Update</button>  
                        <button class="btn btn-danger mx-2" @click="destroy_store(data.str_id)">Delete</button>
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
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form @submit.prevent="b_store_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ร้านค้า</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_store_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <!-- <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อ</label>
                  <input type="text" class="form-control" v-model="store.str_id" required>
                </div>
              </div>
            </div>    -->
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อร้านค้า</label>
                  <input type="text" class="form-control" v-model="store.str_name" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <input type="e-mail" class="form-control" v-model="store.str_detail" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>โทรศัพท์</label>
                  <input type="text" class="form-control" v-model="store.str_phone" >
                </div>
              </div>
            </div>    
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_store_close()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
            <!-- {{store}} -->
            
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
        store: {
          str_id: '',
          str_name: '',
          str_detail: '',
          str_phone: '',
          action: 'insert'
        },
        stores: [] // เก็บรายการร้านค้า
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host ;
      this.get_Stores();
    },
    methods: {      
      get_Stores() {
        const token = getJWT();
        axios.get(this.url_base + '/api/store/get_stores.php', {
          headers: { "Authorization": `Bearer ${token}` }
        })
        .then(response => {
          console.log("API response:", response.data);

          if (response.data.status) {
            this.stores = response.data.respJSON;
          } else {
            Swal.fire({
              icon: 'error',
              title: response.data.message || "ไม่สามารถโหลดข้อมูลร้านค้าได้",
              showConfirmButton: false,
              timer: 1500
            });
          }
        })
        .catch(error => {
          console.error("Error loading stores:", error);
          Swal.fire({
            icon: 'error',
            title: "เกิดข้อผิดพลาดในการเชื่อมต่อ API",
            showConfirmButton: false,
            timer: 1500
          });
        });
      },
      b_store_insert(){
        this.b_store_close();
      },  
    b_store_update(str_id) {
      this.$refs.m_show.click(); // เปิด modal
      const token = getJWT();
      axios.post(this.url_base + '/api/store/get_store.php', { str_id: str_id }, {
        headers: { "Authorization": `Bearer ${token}` }
      })
      .then(response => {
        if (response.data.status) {
          // ✅ ให้ API ส่ง object เดียวมา จะได้ไม่ต้องใช้ [0]
          this.store = response.data.respJSON;
          this.store.action = 'update';
        } else {
          Swal.fire({
            icon: 'error',
            title: response.data.message || "ไม่พบข้อมูลร้านค้า",
            showConfirmButton: false,
            timer: 1500
          });
        }
      })
      .catch(error => {
        console.error("Error get_store:", error);
        Swal.fire({
          icon: 'error',
          title: "เกิดข้อผิดพลาดในการเชื่อมต่อ API",
          showConfirmButton: false,
          timer: 1500
        });
      });
    } , 
    b_store_save() {
      const token = getJWT();
        axios.post(this.url_base + '/api/store/store_action.php',
          { store: this.store },   // ส่ง object store ไป
          { headers: { "Authorization": `Bearer ${token}` } }
        )
        .then(response => {
          console.log("Store save response:", response.data);

          if (response.data.status) {
            Swal.fire({
              icon: 'success',
              title: response.data.message,
              showConfirmButton: false,
              timer: 1500
            });
            this.get_Stores();   // โหลดข้อมูลร้านค้าใหม่
            this.$refs.m_close.click(); // ปิด modal
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
          console.error("Error saving store:", error);
          Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ API',
            showConfirmButton: false,
            timer: 1500
          });
        });
      },

      // ลบร้านค้า (soft delete)
      destroy_store(str_id) {
        Swal.fire({
          title: 'คุณแน่ใจหรือไม่?',
          text: "คุณต้องการปิดการใช้งานร้านค้านี้",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'ใช่, ปิดการใช้งาน!'
        }).then((result) => {
          if (result.isConfirmed) {
            const payload = {
              store: {
                action: 'delete',
                str_id: str_id
              }
            };
            const token = getJWT();
            axios.post(this.url_base + '/api/store/store_action.php', payload, {
              headers: { "Authorization": `Bearer ${token}` }
            })
            .then(response => {
              console.log("Delete store response:", response.data);

              if (response.data.status) {
                Swal.fire({
                  icon: 'success',
                  title: response.data.message,
                  showConfirmButton: false,
                  timer: 1500
                });
                this.get_Stores(); // โหลดข้อมูลใหม่
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
              console.error("Error deleting store:", error);
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
      
      b_store_close(){
        this.store = {
              str_id:'',
              str_name:'',
              str_detail:'',
              str_phone:'',
              action:'insert'        
            };     
          }        
        },
  }).mount('#appStore');
</script>
</body>
</html>
