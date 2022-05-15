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
  
    <?php include "./layouts/nav.php";?>
  
    <?php include "./layouts/aside.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="app" v-cloak> 
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Home</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Home</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-12 col-sm-6 col-md-3" @click="go_order_page">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Order รอตรวจสอบ</span>
                <span class="info-box-number">{{order_st0}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3" @click="go_order_page">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Order ตรวจสอบแล้ว</span>
                <span class="info-box-number">{{order_st1}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3" @click="go_recs_page">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">ใบนำเข้าที่ต้องตรวจสอบ</span>
                <span class="info-box-number">{{recs_st0}}</span>
              </div>
            </div>
          </div>
          <!-- <div class="clearfix hidden-md-up"></div> -->
          
          <div class="col-12 col-sm-6 col-md-3" @click="go_users_page">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">New Members</span>
                <span class="info-box-number">{{user_all}}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <button @click="countP">test {{count}}</button>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include "./layouts/footer.php";?>
</div>
  <?php include "./layouts/footer2.php";?>

<script>
  Vue.createApp({
    data() {
      return {
        order_st0:0,
        order_st1:0,
        recs_st0:0,
        user_all:0,
        message: 'Hello Vue!',
        count:0
      }
    },
    mounted(){
      this.url = window.location.href
      this.url_base = url_base
      this.count_odrs_st0()
      this.count_odrs_st1()
      this.count_recs_st0()
      this.count_users()
    },
    methods: {
    countP() {
      this.count++
    },
    count_odrs_st0(){
      axios.post(url_base + 'api/orders/orders_count.php',{data:'st0'})
        .then(response => {
            if(response.data.status) { 
              this.order_st0 = response.data.respJSON;
            }else {
              this.order_st0 = 0;
            }
        })
    },
    count_odrs_st1(){
      axios.post(url_base + 'api/orders/orders_count.php',{data:'st1'})
        .then(response => {
            if(response.data.status) { 
              this.order_st1 = response.data.respJSON;
            }else {
              this.order_st1 = 0;
            }
        })
    },
    count_recs_st0(){
      axios.post(url_base + 'api/recs/recs_count.php',{data:'st0'})
        .then(response => {
            if(response.data.status) { 
              this.recs_st0 = response.data.respJSON;
            }else {
              this.recs_st0 = 0;
            }
        })
    },
    count_users(){
      axios.post(url_base + 'api/users/users_count.php',{})
        .then(response => {
            if(response.data.status) { 
              this.user_all = response.data.respJSON;
            }else {
              this.user_all = 0;
            }
        })
    },
    go_order_page(){
      window.location.href = './orders.php'
    },
    go_recs_page(){
      window.location.href = './recs.php'
    },
    go_users_page(){
      window.location.href = './users.php'
    }
  },
  }).mount('#app')
</script>
</body>
</html>