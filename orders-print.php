<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Print</title>
  <link rel="icon" href="./dist/img/favicon.png">
  <link rel="stylesheet" href="./node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="./node_modules/admin-lte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="appOrder">

  <section class="content" v-if="order">
    <div class="container-fluid">
      <div class="invoice p-3 mb-3">
        <!-- Header -->
        <div class="row">
          <div class="col-12">
            <h4>
              <i class="fas fa-globe"></i> ใบเบิก
              <small class="float-right">Date: {{ date_thai(order.order_date) }}</small>
            </h4>
          </div>
        </div>

        <!-- Info -->
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
            ผู้เบิก
            <address>
              <strong>{{ order.fullname }}</strong><br>
              {{ order.dep }}<br>
              Phone: {{ order.phone }}<br>
            </address>
          </div>
          <div class="col-sm-6 invoice-col">
            <b>Order ID {{ order.order_id }}</b><br>
            <b>วันจ่ายของ :</b> {{ order.order_pay_date }}<br>
            <b>ผู้จ่าย :</b> {{ order.order_pay_own }}
          </div>
        </div>

        <!-- Table -->
        <div class="row">
          <div class="col-12">
            <table class="table table-striped">
              <thead>
                <tr class="text-center">
                  <th>#</th>
                  <th>ภาพ</th>
                  <th>รายการ</th>
                  <th>หน่วยนับ</th>
                  <th>จำนวนที่ขอเบิก</th>
                  <th>จำนวนที่จ่าย</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(ol,index) in order_lists" :key="ol.order_list_id" class="text-center">
                  <td>{{ index + 1 }}</td>
                  <td><img :src="'./uploads/' + ol.img" height="60"></td>
                  <td class="text-left">{{ ol.pro_name }}</td>
                  <td>{{ ol.unit_name }}</td>
                  <td>{{ ol.qua }}</td>
                  <td>{{ ol.qua_pay }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="text-center font-weight-bold">
                  <td colspan="4">รวม</td>
                  <td>{{ summary.sum_qua }}</td>
                  <td>{{ summary.sum_qua_pay }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- Print Button -->
        <div class="row no-print">
          <div class="col-12 text-center"> <!-- ✅ เพิ่ม text-center -->
            <button class="btn btn-default" onclick="window.print()">
              <i class="fas fa-print"></i> Print
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="./dist/js/vue.global.js"></script>
<script src="./node_modules/axios/dist/axios.min.js"></script>
<script>
Vue.createApp({
  data() {
    return {
      order: null,
      order_lists: [],
      summary: {}
    }
  },
  mounted() {
    const urlParams = new URLSearchParams(window.location.search);
    const order_id = urlParams.get("order_id");
    if (order_id) {
      axios.get(`./api/orders/orders_print.php?order_id=${order_id}`)
        .then(res => {
          if (res.data.status === "success") {
            this.order = res.data.order;
            this.order_lists = res.data.order_lists;
            this.summary = res.data.summary;
          }
        })
        .catch(err => console.error(err));
    }
  },
  methods: {
    date_thai(day) {
      const monthNamesThai = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
      const d = new Date(day);
      return d.getDate() + ' ' + monthNamesThai[d.getMonth()] + " " + (d.getFullYear() + 543);
    }
  }
}).mount('#appOrder')
</script>
</body>
</html>