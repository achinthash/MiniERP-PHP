<?php
  $userProPicture = $_SESSION['user_profilePic'];
  $userId =  $_SESSION['user_id'];
  $userrole = $_SESSION['user_role'];
?>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <!-- Toggle button -->
    <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Navbar brand -->
      <a class="navbar-brand  mt-2 mt-lg-0" href="#">
        <img src="./uploads/login-background.png" height="30" alt="Logo" loading="lazy" />
      </a>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link text-white" href="dashboard">Dashboard</a></li>

          <?php if($userrole === 'admin'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="users">Users</a></li>
          <?php endif ?>

          <li class="nav-item"><a class="nav-link text-white" href="#">Projects</a></li>
        </ul>
      </div>
    </div>

    <!-- Right elements -->
    <div class="d-flex align-items-center">

      <!-- Avatar -->
      <div class="dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo './uploads/profile_pictures/'.$userProPicture ; ?>" class="rounded-circle" height="30" width="30" alt="Avatar" />
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item"  type="button" onclick="userProfilebtn(<?php echo $userId  ?>)"  data-bs-toggle="modal" data-bs-target="#userProfile"> My Profile </a> </li>
          <li><a class="dropdown-item" href="#">Settings</a></li>
          <li><a class="dropdown-item " href="?action=logout">Logout</a></li>
        </ul>
      </div>

    </div>
  </div>


</nav>

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



<?php


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear session
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: login");
    exit;
}
?>


