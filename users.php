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
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ชื่อ</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                     
                      <td>{{data.user_id}}</td>
                      <td>
                       {{data.username}} {{data.dep}} {{data.phone}}
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
        message: 'Hello Vue!',
        user:[{
          user_id:'',
          fullname:'',          
          username:'',          
          password:'',          
          email:'',          
          dep:'',          
          phone:'',          
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
