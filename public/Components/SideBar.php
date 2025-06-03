<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
  $userProPicture = $_SESSION['user_profilePic'];
  $userId =  $_SESSION['user_id'];
  $userrole = $_SESSION['user_role'];
?>
<?php


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  session_unset();
  session_destroy();
  header("Location: login");
  exit;
}

?>


<nav class="navbar navbar-light " style="background-color: #f5efdf;">
    <div class="container-fluid">

        <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="fa-solid fa-bars"></i>
        </button>

      <img class="d-none d-md-block" src="./uploads/login-background.png" height="30" alt="Logo" loading="lazy" />
      <div class="mt-auto  border-top d-flex align-items-center gap-2">
        
        <div class="dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?php echo './uploads/profile_pictures/'.$userProPicture ; ?>" class="rounded-circle" height="30" width="30" alt="Avatar" />
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item"  type="button" 
              

              onclick="userProfilebtn(<?php echo $userId ?>)"



              data-bs-toggle="modal" data-bs-target="#userProfile"> My Profile </a> </li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><a class="dropdown-item " href="?action=logout">Logout</a></li>
            </ul>
        </div>


      </div>
    </div>
  </nav>

  <!-- Sidebar for desktop -->
  <div class="d-none d-md-flex flex-column position-fixed vh-100 bg-light border-end p-2" style="width: 200px;">
   
    <nav class="nav flex-column">
      <a class="nav-link  text-dark  hover-bg-dark" href="#">  <i class="fa-solid fa-house"></i>   Home</a>
      <a class="nav-link text-dark hover-bg-dark" href="dashboard"> <i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
      <a class="nav-link text-dark hover-bg-dark" href="purchaseorders"><i class="bi bi-table me-2"></i> Orders</a>
      <a class="nav-link text-dark hover-bg-dark" href="inventory"><i class="fa-solid fa-warehouse"></i> Inventory</a>
      <a class="nav-link text-dark hover-bg-dark" href="customers"><i class="bi bi-person-circle me-2"></i> Customers</a>
      <a class="nav-link text-dark hover-bg-dark" href="users"><i class="fa-solid fa-users"></i> Users</a>
      <a class="nav-link text-dark hover-bg-dark" href="suppliers"><i class="fa-solid fa-truck-field"></i> Suppliers</a> 
    </nav>
   
  </div>

  <!-- Offcanvas sidebar for mobile -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="mobileSidebarLabel"><i class="bi bi-bootstrap-fill me-2"></i>Sidebar</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <nav class="nav flex-column">
        <a class="nav-link active text-white bg-primary" href="#"><i class="bi bi-house-door-fill me-2"></i> Home</a>
        <a class="nav-link text-dark" href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a class="nav-link text-dark" href="purchaseorders"><i class="bi bi-table me-2"></i> Orders</a>
        <a class="nav-link text-dark" href="inventory"><i class="bi bi-grid me-2"></i> inventory</a>
        <a class="nav-link text-dark" href="customers"><i class="bi bi-person-circle me-2"></i> Customers</a> 
        <a class="nav-link text-dark" href="suppliers"><i class="fa-solid fa-truck-field"></i> Suppliers</a> 
        
      </nav>
      
    </div>
  </div>



<!--  User profile Modal -->
<div class="modal fade modal-xl" id="userProfile"  aria-labelledby="userProfile" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">    
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"> User Profile</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
  
        <!-- <div id="success_message1" ></div>
        <div id="error_message1" ></div> -->
        <?php include './Components/UserProfile.php' ?>
        </div>
       
      </div>
    </div>
  </div>



<script src="./assets/js/logedUser.js"> </script>