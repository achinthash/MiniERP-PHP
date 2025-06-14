<?php
ob_start(); 
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ) {
    header("Location: login");
    exit;
}

if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'manager' )  {
  header("Location: unauthorized");
  exit;
}
?>

<div style="background-color: #dfd3ed; color: #7626d1" class="p-2 m-1 rounded d-flex justify-content-between">
    <h5 class="text-start">Dashboard</h5>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row" style="max-height: 80vh">

      <div class="col-md-4 mb-3">
        <h6 class="text-center">Sales by Payment Method</h6>

          <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px; "  >
            <canvas id="paymentMethodChart" ></canvas>
          </div>
      </div>
    

    <!-- Top Customers -->
    <div class="col-md-4 mb-3">
        <h6 class="text-center">Top Customers</h6>
        <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px; "  >
          <canvas id="topCustomersChart"></canvas>
        </div>
       
    </div>

    <!-- Top Sales Items -->
    <div class="col-md-4 mb-3">
        <h6 class="text-center">Top Sales Items</h6>
        <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px;  "  >
          <canvas id="topProductsChart"></canvas>
        </div>
    </div>

    <!-- Stock Levels -->
    <div class="col-md-6 bg-white mb-3" style="max-height: 300px;">
        <h5 class="text-center">Stock Levels</h5>
        <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px; "  >
          <canvas id="allStockChart"></canvas>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div class="col-md-6 mb-3" style="max-height: 300px;">
      <h6 class="text-center">Monthly Sales</h6>
      <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px; "  >
        <canvas id="monthlySalesChart"></canvas>
      </div>
    </div>



    <!-- Daily Sales  -->
    <div class="col-md-12 mb-3" style="max-height: 300px;">
        <h6 class="text-center">Daily Sales (Current Month)</h6>
        <div  style="padding: 10px; align-items: center; display: flex; justify-content: center; max-height: 250px; "  >
          <canvas id="dailySalesChart"></canvas>
        </div>
    </div>

   
</div>


<script src="./assets/js/dashboard.js"></script> 

<?php $content = ob_get_clean(); include './layout.php'; ?>
