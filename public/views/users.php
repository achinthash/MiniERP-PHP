
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
    <h4> Users </h4>

    <input type="text" id="search" class="form-control w-25" placeholder="Search by name">

    <button class="btn btn-primary" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalToggle"> New User </button>
</div>

<!-- new user modal  -->
<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">    
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Register User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include './Components/newUser.php' ?>
      </div>
     
    </div>
  </div>
</div>


<div id="userTableContainer" class="table-responsive p-2" >
    <table class="table  table-hover p-2">
        <thead>
            <tr>
                <th>ID </th>
                <th>Uname</th>
                <th>Email</th>
                <th>User Role</th>
                <th>Joined At</th>
            </tr>
        </thead>
        <tbody id="userTableBody"></tbody>
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

  
</div>


<script src="./assests/js/userscript.js"></script>

<?php $content = ob_get_clean(); include './layout.php' ?>