
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order_print</title>
<link rel="icon" href="./dist/img/favicon.png">

<!-- Google Font: Source Sans Pro -->

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="./node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./node_modules/admin-lte/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="./node_modules/sweetalert2/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="./node_modules/admin-lte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="./dist/css/app.css">

<body class="hold-transition sidebar-mini">
  <div class="wrapper" id="appOrder">

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="invoice p-3 mb-3">
              <div class="row">
                <div class="col-12">
                  <h4><i class="fas fa-globe"></i> ใบเบิก
                    <small class="float-right">Date:  {{date_thai(order.ord_date)}}</small>
                  </h4>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                  ผู้เบิก
                  <address>
                    <strong>{{order.ord_own}}</strong><br>
                    {{order.dep}}<br>
                    Phone: {{order.phone}}<br>
                  </address>
              </div>
              <div class="col-sm-6 invoice-col">
                <b>Order ID {{order.ord_id}}</b><br>
                <br>
                <b>วันจ่ายของ :</b> {{order.ord_pay_date}}<br>
                <b>ผู้จ่าย :</b> {{order.ord_pay_own}}
              </div>
            </div>
            <div class="row">
              <div class="col-12 ">
                <table class="table table-striped">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>ภาพ</th>
                      <th>รายการ</th>
                      <th>หน่วยนับ</th>
                      <th>จำนวนที่ขอเบิก</th>
                      <th>จำนวนที่จ่าย</th>
                      <th>หมายเหตุ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="ol,index in order_lists" class="text-center" >
                      <td>{{index + 1}}</td>
                      <td>
                          <img :src="'./uploads/'+ ol.img" alt="data.img" class="float-left" height="60" >
                      </td>
                      <td class="text-left">{{ol.pro_name}}</td>
                      <td>{{ol.unit_name}}</td>
                      <td>{{ol.qua}}</td>
                      <td>{{ol.qua_pay}}</td>
                      <td></td>
                    </tr>                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="7" class="text-center">-</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-6">

              </div>
              <div class="col-6">
                <!-- <p class="lead">สรุป</p>
                <div class="table-responsive">
                  <table class="table">
                    <tr>
                      <th style="width:50%">รายการเบิก :</th>
                      <td> {{order_lists}}</td>
                    </tr>
                    <tr>
                      <th>จำนวนชิ้น</th>
                      <td>{{count_pay}}</td>
                    </tr>
                    <tr>
                      <th>Shipping:</th>
                      <td>$5.80</td>
                    </tr>
                    <tr>
                      <th>Total:</th>
                      <td>$265.24</td>
                    </tr>
                  </table>
                </div> -->
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
        </div>

        <div class="row text-center mt-5 w-100">
          <!-- <div class="col-12"> -->
            <div class="float-left col-6">
            ................................................................................................................ผู้เบิก<br><br>
            <!-- ({{order.ord_own}}) -->
            </div>
            <div class="float-right col-6">
            ...............................................................................................................ผู้อนุมัติ<br><br>
            <!-- ({{order.ord_own}}) -->
            </div>
            
          <!-- </div> -->
        <!-- </div> -->
      </div>
      <div class="row mt-5 w-100">
        <!-- <div class="col-12"> -->
          <div class="float-left text-center col-6">
          .....................................................................................................................ผู้จ่าย<br>
          
          </div>
          <div class="float-right text-center col-6">
          .....................................................................................................................ผู้รับของ<br>
          </div>
  
        <!-- </div> -->

      </div>
    </section>
    <!-- {{ord_print}} -->

  </div>

</body>
</html>

<script src="./dist/js/vue.global.js"></script>
<script>
  Vue.createApp({
    data() {
      return {
        datas:'',
        url_base:'',
        ord_print:'',
        order:'',
        order_lists:'',
        count_index:0,
        count_pay:0,
      }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.ord_print = JSON.parse(localStorage.getItem("ord_print"))
      this.order = this.ord_print.order[0]
      this.order_lists = this.ord_print.order_lists
      localStorage.removeItem("ord_print")
      // window.print()
    },
    methods: {      
      date_thai(day){
        var monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม"];
        var dayNames = ["วันอาทิตย์ที่","วันจันทร์ที่","วันอังคารที่","วันพุทธที่","วันพฤหัสบดีที่","วันศุกร์ที่","วันเสาร์ที่"];
        var monthNamesEng = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var dayNamesEng = ['Sunday','Monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        var d = new Date(day);
        return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + "  " + (d.getFullYear() + 543)
      },
      },
  }).mount('#appOrder');
</script>
</body>
</html>
