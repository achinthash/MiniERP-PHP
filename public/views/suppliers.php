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

   <!-- response alerts messages   -->
   <div style="position: absolute; right: 0; bottom: 0;" id="delete_success_message" ></div>
    <div style="position: absolute; right: 0; bottom: 0;" id="delete_error_message" ></div>



<div style="background-color: #dfd3ed;  color: #7626d1" class="p-2  m-1 rounded  d-flex justify-content-between" >
  <h5 class="text-start "> Supliers </h5>

    <input type="text" id="search" class="form-control w-25" placeholder="Search by name">

    <button class="btn btn-primary" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSuplier"> New Suplier </button>

</div>

<!-- new suplier modal  -->
<div class="modal modal-lg fade" id="newSuplier" aria-hidden="true" aria-labelledby="newSuplier" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >New Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Suppliers/NewSupplier.php' ?>
      </div>
     
    </div>
  </div>
</div>

<table class="table  table-hover">
    <thead>
        <tr >
            <th>ID </th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="supplierTableBody"></tbody>
</table>
<nav>
    <ul class="pagination" id="pagination_supplier">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?url=User/team&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>


<!-- suplier  profile modal  -->
<div class="modal modal-lg fade" id="supplierProfile" aria-hidden="true" aria-labelledby="supplierProfile" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Supplier </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Suppliers/SupplierProfile.php' ?>
      </div>
     
    </div>
  </div>
</div>




<!-- suplier  edit  modal  -->
<div class="modal modal-lg fade" id="supplierEdit" aria-hidden="true" aria-labelledby="supplierEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Supplier Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Suppliers/EditSupplier.php' ?>
      </div>
     
    </div>
  </div>
</div>

<script src="./assets/js/suppliers.js"> </script> 

<?php $content = ob_get_clean(); include './layout.php' ?> 