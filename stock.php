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
  <div class="content-wrapper" id="appStock" v-cloak>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item active">Stock</li>
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
                <h5 class="card-title">Stock</h5>
                <div class="card-tools">
                  <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal">test</button>                  
                </div>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>วันที่</th>
                      <th>รายการ</th>
                      <th>เลขเอกสาร</th>
                      <th>หน่วยนับ</th>
                      <th>ราคาต่อหน่วย</th>
                      <th>ยกมา</th>
                      <th>รับ</th>
                      <th>ออก</th>
                      <th>คงเหลือ</th>
                      <th>หมายเหตุ</th>
                    </tr>
                  </thead>
                  <tbody >
                    <tr v-for="data,index in datas">
                      <td>{{data.created_at}}</td>
                      <td>{{data.pro_name}}(id:{{data.pro_id}})</td>
                      <td>{{data.rec_ord_id}}</td>
                      <td>{{data.unit_name}}</td>
                      <td>{{data.price_one}}</td>
                      <td>{{data.bf}}</td>
                      <td>{{data.stck_in}}</td>
                      <td>{{data.stck_out}}</td>
                      <td>{{data.bal}}</td>
                      <td>{{data.comment}}</td>    
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
          </div>          
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
        message: 'Hello Vue!'       
        
      }
    },
    mounted(){
      this.get_Stock();
    },
    methods: {      
      get_Stock(){
        axios.post(url_base + 'api/stock/get_stocks.php')
            .then(response => {
                if (response.data.status) {
                    this.datas = response.data.respJSON;         
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      },
      b_stock_insert(){
        this.b_stock_close();
      }, 
      b_stock_save(){
        var jwt = localStorage.getItem("jwt");
        axios.post(url_base + 'api/stock/stock_action.php',{stock:this.stock},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                if (response.data.status == 'success') {
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.massege,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Stock();  
                   
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
             
      },
  }).mount('#appStock');
</script>
</body>
</html>
