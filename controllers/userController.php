<?php
require_once '../models/user.php';
$user = new User();

$action = $_POST['action'];

if ($action == 'read') {
    echo json_encode($user->readAll());
}

if ($action === 'paginate') {
    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 5;

    $users = $user->paginate($search, $page, $perPage);
    $total = $user->count($search);

    echo json_encode([
        'data' => $users,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}










if ($_POST['action'] === 'create') {


    $errors = [];

    $username = $_POST['username'];

    $email = trim($_POST['email']);


    $password = $_POST['password'];
    $userrole = $_POST['userrole'];
    $confirmPassword = $_POST['confirmPassword'];

    $profilePicture = null;


    if($_SERVER['REQUEST_METHOD'] === 'POST' ){

        if($password !== $confirmPassword){
            $errors['confirmPassword'] = "Passwords do not match.";
        }

        // username
        if(empty($username)){
            $errors['username'] = "User Name is required.";
        }elseif(!preg_match("/^[a-zA-Z ]+$/",$username)){
            $errors['username'] = "Only letters and white space allowed.";
        }elseif (strlen($username) < 2 || strlen($username) > 50) {
            $errors['username'] = "User name must be between 2 and 50 characters.";
        }

        // userrole
        if(empty($userrole)){
        $errors['userrole'] = "User Role is required.";
        }

     //email

     if(empty($email)){
        $errors['email'] = "Email is required.";
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "Invalid email format";
    }elseif($user->existsByEmail($email)){
         $errors['email'] = "Email already exists.";

    }
    


     //password 
     if(empty($password)){
        $errors['password'] = "Password is required.";
    }elseif(strlen($password)<8){
        $errors['password'] = "Password must be at least 8 characters long.";
    }



 // profile picture

 if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = $_FILES['profile_picture']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Allowed file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
        $uploadFileDir = '../public/uploads/profile_pictures/';
        $destPath = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $profilePicture = $newFileName;
        } else {
            $errors['profile_picture'] = "There was an error uploading the file.";
        }
    } else {
        $errors['profile_picture'] = "Only JPG, JPEG, and PNG files are allowed.";
    }
} 


    if (empty($errors)) {
        $userModel = new User(); 
        $result = $userModel->create($username, $email, $password, $userrole, $profilePicture);
        // && $result['success'] === true
        if (isset($result['success']) ) {
            echo json_encode(['success' => 'User added successfully!']);
        } elseif (isset($result['error'])) {
            echo json_encode(['errors' => ['database' => $result['error']]]);
        }
        exit;

    } else {
        echo json_encode(['errors' => $errors]);
    }
   
    }
   

    
}







if($action == 'get'){
    $id = $_POST['id'];

    $auth = $user->fetchUser($id); 
    if($auth ){
        echo json_encode(['success' => true, 'user' => $auth]);
    }else{
        echo json_encode(['errors' => ['User not found']]);
    }


}









if ($action == 'update') {
    $id = $_POST['userId'] ?? '';
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $userrole = $_POST['userrole'] ?? '';
    $existingProfilePicture = $_POST['existing_profile_picture'] ?? ''; 

  
    if (!$id || !$name || !$email) {
        echo json_encode(['errors' => [$existingProfilePicture]]);
        exit;
    }

    $errors = [];

if (empty($name)) {
    $errors['username'] = "User Name is required.";
} elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
    $errors['username'] = "Only letters and white space allowed.";
} elseif (strlen($name) < 2 || strlen($name) > 50) {
    $errors['username'] = "User name must be between 2 and 50 characters.";
}

if (empty($userrole)) {
    $errors['userrole'] = "User Role is required.";
}

if (empty($email)) {
    $errors['email'] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
} elseif ($user->existsByEmail($email, $id)) {
    $errors['email'] = "Email already exists.";
}


// profile picture

 $profilePicture = $existingProfilePicture; 

 if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
     $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
     $fileName = $_FILES['profile_picture']['name'];
     $fileNameCmps = explode(".", $fileName);
     $fileExtension = strtolower(end($fileNameCmps));
     $allowedExtensions = ['jpg', 'jpeg', 'png'];

     if (in_array($fileExtension, $allowedExtensions)) {
         $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
         $uploadFileDir = '../public/uploads/profile_pictures/';
         $destPath = $uploadFileDir . $newFileName;

         if (move_uploaded_file($fileTmpPath, $destPath)) {
             $profilePicture = $newFileName;

         } else {
             $errors['profile_picture'] = "Error uploading the file.";
         }
     } else {
         $errors['profile_picture'] = "Only JPG, JPEG, and PNG files are allowed.";
     }
 }


if (empty($errors)) {
    $result = $user->update($id, $name, $email, $userrole, $profilePicture);
    if ($result) {
        echo json_encode(['success' => 'User updated successfully.']);
    } else {
        echo json_encode(['errors' => ['Database update failed.']]);
    }
    exit;
} else {
    echo json_encode(['errors' => $errors]);
}


  
}




if ($action == 'delete') {

    $userId = $_POST['delete_user_id'];
    $password = $_POST['password'];

    $result = $user->delete($userId,$password);

    if($result){
        echo json_encode(['success'=> true, 'message' => 'Category Delete Successfully.']);
        exit;
    }else{
        echo json_encode(['success'=> false, 'message' => 'unable to Category Delete.']);
        exit;
    }

   
}



if($action == 'login'){

    session_start();

    $error = null;
    $email = $_POST['email'];
    $password =  $_POST['password'];

     $auth = $user->login($email, $password);

     if ($auth) {

        $_SESSION['user_id'] = $auth['id'];
        $_SESSION['user_name'] = $auth['username'];
        $_SESSION['user_role'] = $auth['userrole'];
        $_SESSION['user_profilePic'] = $auth['profile_picture'];
 
        echo json_encode(['success' => "Login successfull.", 'user_role' => $auth['userrole'] ]);
        exit(); 

    } else {
     
        echo json_encode(['error' => "Invalid email or password."]);
    }
    
}




if($action == 'updatePassword'){

    $userId = $_POST['password_user_id'];

    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $errors =[];

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    if(strlen($oldPassword) < 8 ||  strlen($newPassword) < 8 || strlen($confirmPassword)<8 ){
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
        exit;
    } 

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['errors' =>  'New passwords do not match..']);
        exit;
    }


    $result = $user->passwordUpdate($userId,$oldPassword,$newPassword,$confirmPassword);
    echo json_encode($result);
    exit;


}

