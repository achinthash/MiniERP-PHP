
<?php
  ob_start(); 
  session_start();

  if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ) {
    header("Location: login");
    exit;
  }

  if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'manager') {
    header("Location: unauthorized");
    exit;
  }
?>


<div style="background-color: #dfd3ed;  color: #7626d1" class="p-2  m-1 rounded  d-flex justify-content-between" >
  <h5 class="text-start "> Inventory </h5>

  <div class="dropdown ">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Items</button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" type="button"  onclick="handleCategory()" >Category</a></li>
      <li><a class="dropdown-item" type="button"  onclick="handleProducts()" >Products</a></li>

    </ul>
  </div>

</div>



<div id="products-section">
  <?php include './Components/Inventory/ProductsSection.php' ?>
</div>

<div id="category-section" class="d-none"> 
  <?php include './Components/Inventory/CategorySection.php' ?>
</div>





<script src="./assets/js/inventory.js"> </script> 

<?php $content = ob_get_clean(); include './layout.php' ?> 