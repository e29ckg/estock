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
                    <tr v-for="data,index in datas">
                     
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
                        <!-- <button @click="destroy_store(data.str_id)">Delete</button>   -->
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
                  <input type="text" class="form-control" v-model="store[0].str_id" required>
                </div>
              </div>
            </div>    -->
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อร้านค้า</label>
                  <input type="text" class="form-control" v-model="store[0].str_name" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <input type="e-mail" class="form-control" v-model="store[0].str_detail" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>โทรศัพท์</label>
                  <input type="text" class="form-control" v-model="store[0].str_phone" >
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
  

  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        message: 'Hello Vue!',
        store:[{
          str_id:'',
          str_name:'',          
          str_detail:'',          
          str_phone:'',          
          action:'insert'        
        }],
        
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host + '/estock/';
      this.get_Store();
    },
    methods: {      
      get_Store(){
        axios.post(this.url_base + 'api/store/get_stores.php')
            .then(response => {
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_store_insert(){
        this.b_store_close();
      },  
      b_store_update(str_id){
        this.$refs.m_show.click();
        axios.post(this.url_base + 'api/store/get_store.php',{str_id:str_id})
            .then(response => {
                if (response.data.status) {
                  this.store = response.data.respJSON;
                  this.store[0].action = 'update';              
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },  
      b_store_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(this.url_base + 'api/store/store_action.php',{store:this.store},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                if (response.data.status == 'success') {
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Store();  
                  this.store = [{
                              str_id:'',
                              str_name:'',
                              str_detail:'',
                              str_phone:'',
                              action:'insert'        
                            }];     
                }else{
                  Swal.fire({
                    icon: response.data.status,
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
      destroy_store(str_id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                if (result.isConfirmed) {
                  var jwt = localStorage.getItem("jwt");
                  this.store[0].action = 'delete';  
                  this.store[0].str_id = str_id;  
                  axios.post(this.url_base + 'api/store/store_action.php',{store:this.store},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                        if (response.data.status == 'success') {
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Store(); 
                             
                        }else{
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500
                          })
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                }
              });            
        },
        b_store_close(){
          this.store = [{
                              str_id:'',
                              str_name:'',
                              str_detail:'',
                              str_phone:'',
                              action:'insert'        
                            }];     
        }        
      },
  }).mount('#appStore');
</script>
</body>
</html>
