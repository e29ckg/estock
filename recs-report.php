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
<!-- <div class="wrapper" > -->

  <!-- Content Wrapper. Contains page content -->
  <div class="wrapper" id="appRecs" v-cloak>   

    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <td colspan="6" class="text-center"><b> <h5>ใบรับของเข้า</h5> </b></td>
            </tr>
            <tr>
              <td colspan="6" >
                <strong>From </strong>{{rec.str_name}}<br>
                <strong> {{rec.str_detail}}</strong> PHONE: </strong>{{rec.str_phone}}<br>
                OWN  : {{rec.rec_own}} APP  : {{rec.rec_app}}<br>
                <strong>DATE : </strong>{{rec.rec_date}} <strong>CODE : </strong>{{rec.rec_id}}<br>
              </td>
            </tr>
            <tr class="text-center">
              <th>#</th>
              <th>Product</th>
              <th>หน่วยนับ</th>
              <th>จำนวน</th>
              <th>ราคาต่อหน่วย</th>
              <th>ราคา</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data,index in datas.respJSON" class="text-center">
              <td>{{index + 1 }}</td>
              <td class="text-left">{{data.pro_name}}</td>
              <td>{{data.unit_name}}</td>
              <td>{{data.qua}}</td>
              <td class="text-right">{{formatCurrency(data.price_one)}}</td>
              <td class="text-right">{{formatCurrency(data.price)}}</td>
            </tr>            
            <tr>
              <td colspan="5"></td>
              <td class="bg-gray text-right">{{formatCurrency(datas.price_all)}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row no-print">
              <div class="col-12">
                <button rel="noopener" class="btn btn-default" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                <!-- <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                  Payment
                </button>
                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                  <i class="fas fa-download"></i> Generate PDF
                </button> -->
              </div>
            </div>
    

  </div>
<!-- END APP -->
 
<!-- </div> -->
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

<script>

</script>
<script>
 
  Vue.createApp({
    data() {
      return {
        datas:'',      
        str_name:'',      
        rec:'',      
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host     
      this.datas = JSON.parse(localStorage.getItem("print_rec"))
      this.rec = this.datas.rec
      localStorage.removeItem("print_rec")
      // window.print()
    },
    methods: {    
      
      formatCurrency(number) {
          number = parseFloat(number);
          return number.toFixed(2).replace(/./g, function(c, i, a) {
              return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
          });
        },     
      

    },
  }).mount('#appRecs');
</script>
</body>
</html>
