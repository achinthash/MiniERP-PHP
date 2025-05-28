<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>

    <!-- boostrap  -->
    <link rel="stylesheet" href="./assests/css/bootstrap.min.css" >
    <link rel="stylesheet" href="./assests/css/index.css" >
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <!-- Font Awesome 6 (latest) CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">





</head>
<body>

    <!-- jquery -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->




   <?php include './Components/SideBar.php' ?>

   

    <main class=" " id="main-content" >
        <?= $content ?>
    </main>
    
   

    <!-- boostrap  -->
    <script src="./assests/js/bootstrap.bundle.min.js"></script>
    

  
</body>
</html>