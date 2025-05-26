
<?php
  ob_start(); 
  session_start();

  if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) ) {
    header("Location: login");
    exit;
  }

  if($_SESSION['user_role'] !== 'admin'){
    header("Location: dashboard");
    exit;
  }
?>

<?php include './Components/NavigationBar.php' ?>


<div style="background-color: #9a6dbd;" class="text-start p-2   m-1 rounded text-light d-flex justify-content-between" >
    <h4> Inventory </h4>

    <button class="btn btn-primary" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProduct"> New Product </button>
</div>

<!-- new products modal  -->
<div class="modal fade" id="newProduct" aria-hidden="true" aria-labelledby="newProduct" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newProduct">new categoryu</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Products/NewProduct.php' ?>
      </div>
     
    </div>
  </div>
</div>

<script src="./assests/js/inventory.js"> </script>

<?php $content = ob_get_clean(); include './layout.php' ?>