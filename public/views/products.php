
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
    <h4> Products </h4>

    
</div>



<?php $content = ob_get_clean(); include './layout.php' ?>