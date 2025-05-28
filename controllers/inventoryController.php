<?php

require '../models/category.php';

$category = new Category();

$action = $_POST['action'];


if($action == 'create'){

    $name = $_POST['name'];
    $description = $_POST['description'];
    $errors = [];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
       
        // name
        if(empty($name)){
            $errors['name'] = 'Category Name is required';
        } elseif(!preg_match("/^[a-zA-Z ]+$/",$name)){
            $errors['name'] = "Only letters and white space allowed.";
        }  elseif ($category->existsByName($name)){
            $errors['name'] = "Category already exists.";
        }

        // description 
        if(!preg_match("/^[a-zA-Z ]+$/",$description)){
            $errors['description'] = "Only letters and white space allowed.";
        }elseif(strlen($description) < 2 || strlen($description) > 250){
            $errors['description'] = "Description must be between 2 and 250 characters.";
        }

        if(empty($errors)){

            $result = $category->create($name,$description);
                
            if ($result) {
                echo json_encode(['success' => 'Category created successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to create category.']]);
            }
    
        }else{
            echo json_encode(['errors' => $errors]);
        }
    } 

}



if($action == 'categoryTable'){

    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 5;

    $categories = $category->categories($search, $page, $perPage);
    $total = $category->count($search);

    echo json_encode([
        'data' => $categories,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}

if($action == 'getCategory'){
    $id = $_POST['id'];

    $auth = $category->fetchCategory($id); 
    if($auth ){
        echo json_encode(['success' => true, 'category' => $auth]);
    }else{
        echo json_encode(['errors' => ['Category not found']]);
    }


}


if($action == 'updateCategory'){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $errors = [];
   
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
       
        // name
        if(empty($name)){
            $errors['name'] = 'Category Name is required';
        } elseif(!preg_match("/^[a-zA-Z ]+$/",$name)){
            $errors['name'] = "Only letters and white space allowed.";
        }  elseif ($category->existsByName($name,$id)){
            $errors['name'] = "Category already exists.";
        }

        // description 
        if(!preg_match("/^[a-zA-Z ]+$/",$description)){
            $errors['description'] = "Only letters and white space allowed.";
        }elseif(strlen($description) < 2 || strlen($description) > 250){
            $errors['description'] = "Description must be between 2 and 250 characters.";
        }

        if(empty($errors)){

            $result = $category->update($id,$name,$description);
                
            if ($result) {
                echo json_encode(['success' => 'Category updated successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to updated category.']]);
            }
    
        }else{
            echo json_encode(['errors' => $errors]);
        }
    } 

}

if($action == 'deleteCategory'){

    $id = $_POST['id'];

    $result = $category->delete($id);

    echo json_encode($result);
    exit;
}


?>

