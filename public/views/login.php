
<?php
    session_start();
    if (!empty($_SESSION['user_id'])) {
        header("Location: users");
       exit;
    }
  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- boostrap  -->
    <link rel="stylesheet" href="./assests/css/bootstrap.min.css" >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    <section class="vh-100 container py-5">
        
        <div class="row justify-content-center justify-content-center ">

            <div class="col-xl-10 card p-5 shadow">

                <h3 class="text-center mb-2">Log in </h3>
                <div id="success_message" ></div>
                <div id="error_message" ></div>

                <div class="row d-flex align-items-center justify-content-center h-100">
            
                    <div class="col-md-8 col-lg-7 col-xl-6" >
                        <img src="./uploads/login-background.png"
                        class="img-fluid" alt="Phone image">
                    </div>

                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1 mt-5">
                        <form method="POST" id="loginForm">
                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" name="email"  class="form-control form-control-lg" />
                            </div>

                            <!-- Password input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" name="password"  class="form-control form-control-lg" />
                            </div>

                            <!-- Submit button -->
                            <button type="submit" name="login"  class="btn btn-primary  btn-block">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


 <!-- boostrap  -->
 <script src="./assests/js/bootstrap.bundle.min.js"></script>
 <script src="./assests/js/login.js"></script>
 
</body>
</html>


