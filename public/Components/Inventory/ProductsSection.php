
   <!-- response alerts messages   -->
   <div style="position: absolute; right: 0; bottom: 0;" id="delete_success_message" ></div>
    <div style="position: absolute; right: 0; bottom: 0;" id="delete_error_message" ></div>

<div style="background-color: #dfd3ed;  color: #7626d1" class="p-2  m-1 rounded  d-flex justify-content-between" >
    <h6 class="text-start p-1"> Products </h6>
    <input type="text" id="search" class="form-control w-25" placeholder="Search by name">
    <button class="btn btn-primary " type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProduct"> New product </button>

</div>


<!-- new product modal  -->
<div class="modal fade" id="newProduct" aria-hidden="true" aria-labelledby="newProduct" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newProduct">New Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Inventory/NewProduct.php' ?>
      </div>
     
    </div>
  </div>
</div>




<table class="table  table-hover">
    <thead>
        <tr >
            <th>Product Name </th>
            <th>SKU</th>
            <th>Category</th>
            <th>Suplier</th>
            <th>Current Stock</th>
            <th>Unit Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="productTableBody"></tbody>
</table>
<nav>
    <ul class="pagination" id="pagination_products">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?url=User/team&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>


<!-- product profile modal  -->
<div class="modal fade" id="productProfle" aria-hidden="true" aria-labelledby="productProfle" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" > Product profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <?php include './Components/Inventory/ProductProfile.php' ?>
      </div>
     
    </div>
  </div>
</div>

<!-- product edit modal  -->
<div class="modal fade" id="productEdit" aria-hidden="true" aria-labelledby="productEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" > Product Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <?php include './Components/Inventory/EditProduct.php' ?>
      </div>
     
    </div>
  </div>
</div>




<script src="./assests/js/products.js"> </script>