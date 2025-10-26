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
                  <!-- <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal">test</button>                   -->
                </div>
              </div>
              <div class="card-body">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr class="text-center">
                      <th width="185px">วันที่</th>
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
                    <tr v-for="data,index in datas" class="text-center">
                      <td :data-date="data.created_at" class="text-left">
                        {{date_thai(data.created_at)}}
                      </td>
                      <td class="text-left">{{data.pro_name}}</td>
                      <td>
                        <!-- {{data.rec_ord_id}} -->
                        <a v-if="data.stck_in > 0" @click="show_recs(data.rec_ord_id)">{{data.rec_ord_id}}</a>
                        <a  v-if="data.stck_out > 0" @click="show_ords(data.rec_ord_id)">{{data.rec_ord_id}}</a>
                      </td>
                      <td>{{data.unit_name}}</td>
                      <td class="text-center">{{formatCurrency(data.price_one)}}</td>
                      <td>{{formatCurrency0(data.bf)}}</td>
                      <td>{{formatCurrency0(data.stck_in)}}</td>
                      <td>{{formatCurrency0(data.stck_out)}}</td>
                      <td>{{formatCurrency0(data.bal)}}</td>
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
  
  Vue.createApp({
    data() {
      return {
        url_base:'',
        datas:'',
        message: 'Hello Vue!'       
        
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.get_Stock();
    },
    methods: {   
      show_recs(rec_ord_id){
        axios.post('/api/recs/rec_print.php',{rec_id:rec_ord_id})
            .then(response => {
                if (response.data.status) {
                  var print_rec = JSON.stringify(response.data);    
                  localStorage.setItem("print_rec",print_rec);
                  window.open(this.url_base + 'recs-report','_blank')

                }
            })
            .catch(function (error) {
                console.log(error);
            });
      } ,  
      show_ords(rec_ord_id){
        axios.post('./api/orders/orders_print.php',{ord_id:rec_ord_id})
            .then(response => {
                if (response.data.status) {
                    ord_print = JSON.stringify(response.data)   
                    localStorage.setItem("ord_print",ord_print)
                    window.open("orders-print.php",'_blank')      
                }
            })
            .catch(function (error) {
                console.log(error);
            });
      } ,  
      get_Stock(){
        axios.post('./api/stock/get_stocks.php')
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
        axios.post(this.url_base + 'api/stock/stock_action.php',{stock:this.stock},{ headers: {"Authorization" : `Bearer ${jwt}`}})
            .then(response => {
                if (response.data.status == 'success') {
                  Swal.fire({
                    icon: response.data.status,
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                  });
                  this.$refs['m_close'].click();
                  this.get_Stock();  
                   
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
      date_thai(day){
        var monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม"];
        var dayNames = ["วันอาทิตย์ที่","วันจันทร์ที่","วันอังคารที่","วันพุทธที่","วันพฤหัสบดีที่","วันศุกร์ที่","วันเสาร์ที่"];
        var monthNamesEng = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var dayNamesEng = ['Sunday','Monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        var d = new Date(day);
        return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + "  " + (d.getFullYear() + 543)
      },
      formatCurrency(number) {
        number = parseFloat(number);
        return number.toFixed(2).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
        });
      },
      formatCurrency0(number) {
        number = parseFloat(number);
        return number.toFixed(0).replace(/./g, function(c, i, a) {
            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
        });
      }
             
    },
  }).mount('#appStock');
</script>
</body>
</html>
