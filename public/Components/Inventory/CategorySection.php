
    <!-- response alerts messages   -->
    <div style="position: absolute; right: 0; bottom: 0;" id="delete_success_message" ></div>
    <div style="position: absolute; right: 0; bottom: 0;" id="delete_error_message" ></div>

<div style="background-color: #dfd3ed;  color: #7626d1" class="p-2  m-1 rounded  d-flex justify-content-between" >
    <h6 class="text-start p-1"> Categories </h6>
    <input type="text" id="search" class="form-control w-25" placeholder="Search by name">
    <button class="btn btn-primary " type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategory"> New Category </button>

</div>


<!-- new Category modal  -->
<div class="modal fade" id="newCategory" aria-hidden="true" aria-labelledby="newProduct" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newProduct">New Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Inventory/NewCategory.php' ?>
      </div>
     
    </div>
  </div>
</div>





<table class="table table-striped table-hover">
    <thead>
        <tr >
            <th>ID </th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="categoryTableBody"></tbody>
</table>
<nav>
    <ul class="pagination" id="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?url=User/team&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>


<!-- edit Category modal  -->
<div class="modal fade" id="editCategory" aria-hidden="true" aria-labelledby="editCategory" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editCategory">Edit Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/Inventory/EditCategory.php' ?>
      </div>
     
    </div>
  </div>
</div>




<script src="./assests/js/category.js"> </script>