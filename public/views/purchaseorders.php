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

   <!-- response alerts messages   -->
   <div style="position: absolute; right: 0; bottom: 0;" id="delete_po_success_message" ></div>
    <div style="position: absolute; right: 0; bottom: 0;" id="delete_po_error_message" ></div>

    
<div style="background-color: #dfd3ed;  color: #7626d1" class="p-2  m-1 rounded  d-flex justify-content-between" >
  <h5 class="text-start "> Purchase Orders </h5>

    <button class="btn btn-primary" type="button"  onclick="newPurchOrderClick()" > New Order </button>
    
</div>

<!-- new suplier modal  -->
<div class="modal modal-lg fade" id="newpurchaseOrder" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >New Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/PurchaseOrder/NewPurchaseOrder.php' ?>
      </div>
     
    </div>
  </div>
</div>


<table class="table  table-hover">
    <thead>
        <tr >
            <th>ID </th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="purchaseOrderTableBody"></tbody>
</table>
<nav>
    <ul class="pagination" id="pagination_po">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?url=User/team&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>


<!-- new po modal  -->
<div class="modal modal-lg fade" id="purchaseOrderprofile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >New Order</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/PurchaseOrder/PurchaseOrderProfile.php' ?>
      </div>
     
    </div>
  </div>
</div>

<!-- edit  po modal  -->
<div class="modal modal-lg fade" id="purchaseOrderEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >  Edit Purchase Order</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/PurchaseOrder/EditPurchaseOrder.php' ?>
      </div>
     
    </div>
  </div>
</div>

<script src="./assets/js/purchaseOrder.js"> </script> 

<?php $content = ob_get_clean(); include './layout.php' ?> 



