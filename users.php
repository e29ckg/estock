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
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" @click="b_user_insert()" ref="m_show">เพิ่มสมาชิก</button>                  
                  <button class="btn btn-danger" data-toggle="modal" data-target="#users_main_Modal" @click="get_Users_main()" ref="m_user_main_show">เพิ่มสมาชิก(from_main)</button>                  
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อ</th>
                      <th>username</th>
                      <th>สถานะ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.user_id}}</td>
                      <td>{{data.fullname}}</td>
                      <td>
                       {{data.username}} {{data.dep}} {{data.phone}}
                      </td>
                      <td>
                        <!-- {{data.st}} -->
                        <span v-if="data.st == 10" class="badge badge-primary">ปกติ</span>
                        <span v-else class="badge badge-danger">ระงับ</span>
                      </td>
                      <td>
                        <button @click="b_user_update(data.user_id)" >Update</button>  
                        <button @click="destroy_user(data.user_id)">Delete</button>  
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
          <form @submit.prevent="b_user_save()">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="m_close" @click="b_user_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">  
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>ชื่อ</label>
                  <input type="text" class="form-control" v-model="user[0].fullname" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>username</label>
                  <input type="text" class="form-control" v-model="user[0].username" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>E-mail</label>
                  <input type="e-mail" class="form-control" v-model="user[0].email" required>
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>dep</label>
                  <input type="text" class="form-control" v-model="user[0].dep" >
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                  <label>phone</label>
                  <input type="text" class="form-control" v-model="user[0].phone" >
                </div>
              </div>
            </div>   
            <div class="row">   
              <div class="col-sm-6">
                <div class="form-group">
                  <label>สถานะ</label>
                  <select class="form-control" v-model="user[0].st">
                    <option value=10>ใช้งาน</option>
                    <option value=0>ระงับการใช้งาน</option>
                  </select>
                </div>
              </div>
            </div>   

            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"  @click="b_user_close()">Close</button>
            <button type="submit" class="btn btn-primary" >Save changes</button>
          </div>
            <!-- {{catatlog}} -->
            
          </form>
        </div>
      </div>
    </div>

    <!-- Modal m_user_main-->
    <div class="modal fade" id="users_main_Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="users_main_ModalModalLabel">m_user_main</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="close_m_user_main" @click="b_user_close()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">             
            <table class="table table-striped">
              <thead>
                <tr>
                  <td>user_id</td>
                  <td>username</td>
                  <td>email</td>
                  <td>fullname</td>
                  <td>dep</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="dm,index in datas_main">
                  <td>{{dm.user_id}}</td>
                  <td>
                    {{dm.username}}
                  </td>
                  <td>{{dm.email}}</td>
                  <td>
                    {{dm.fullname}}<br>
                    {{dm.phone}}</td>
                  <td>{{dm.dep}}</td>
                  <td><button @click="add_user_for_main(index)">เพิ่มสมาชิก</button></td>
                </tr>
                
              </tbody>
            </table>
            
            <!-- {{datas_main}} -->
                       
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="submit" class="btn btn-primary" >Save changes</button> -->
          </div>
            <!-- {{catatlog}} -->
            
        </div>
      </div>
    </div><!-- m_user_main -->
    


  </div>
<!-- END APP -->
  <?php include "./layouts/footer.php";?>
</div>
<?php include "./layouts/footer2.php";?>
<script>
  var url_base = window.location.protocol + '//' + window.location.host + '/estock/';

  Vue.createApp({
    data() {
      return {
        datas:'',
        datas_main:'',
        message: 'Hello Vue!',
        user:[{
          user_id:'',
          fullname:'',          
          username:'',          
          password:'',          
          email:'',          
          dep:'',          
          phone:'',          
          st:10,          
          fullname:'',          
          action:'insert'        
        }],
        
      }
    },
    mounted(){
      this.get_Users();
    },
    methods: {      
      get_Users(){
        axios.post(url_base + 'api/users/get_users.php')
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      get_Users_main(){
        axios.post(url_base + 'api/users/get_users_main.php')
            .then(response => {
                // console.log(response.data);
                if (response.data.status) {
                    this.datas_main = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_user_insert(){
        this.b_user_close();
      },  
      b_user_update(user_id){
        this.$refs.m_show.click();
        axios.post(url_base + 'api/users/get_user.php',{user_id:user_id})
            .then(response => {
                if (response.data.status) {
                  this.user = response.data.respJSON;
                  this.user[0].action = 'update';              
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      }, 
      add_user_for_main(index){
        user = this.datas_main[index]
        var jwt = localStorage.getItem("jwt");
        axios.post(url_base + 'api/users/save_users_form_main.php',{user:user},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                if (response.data.status == 'success') {
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.massege,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.get_Users();  
                }else{
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.massege,
                    showConfirmButton: false,
                    timer: 1500
                  })
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_user_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(url_base + 'api/users/user_action.php',{user:this.user},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                if (response.data.status == 'success') {
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.massege,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Users();  
                  this.user = [{
                              user_id:'',
                              fullname:'',
                              username:'',
                              email:'',
                              dep:'',
                              phone:'',
                              action:'insert'        
                            }];     
                }else{
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.massege,
                    showConfirmButton: false,
                    timer: 1500
                  })
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      destroy_user(user_id){
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
                  this.user[0].action = 'delete';  
                  this.user[0].user_id = user_id;  
                  axios.post(url_base + 'api/users/user_action.php',{user:this.user},{ headers: {"Authorization" : `Bearer ${jwt}`}})
                    .then(response => {
                        if (response.data.status == 'success') {
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.massege,
                            showConfirmButton: false,
                            timer: 1500
                          })
                          this.get_Users(); 
                             
                        }else{
                          Swal.fire({
                            icon: response.data.status,
                            title: response.data.massege,
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
        b_user_close(){
          this.user = [{
                              user_id:'',
                              username:'',
                              email:'',
                              dep:'',
                              phone:'',
                              action:'insert'        
                            }];     
        }        
      },
  }).mount('#appUsers');
</script>
</body>
</html>
