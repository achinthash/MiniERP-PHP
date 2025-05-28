<?php

require '../models/products.php';
require '../models/category.php';

$products = new Products();
$category = new Category();

$action = $_POST['action'];


if($action == 'create'){

    $category_id = $_POST['category_id'];
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];
    $image = null;



    $errors = [];

    // echo json_encode(['errors' => ['Failed to create product.']]);


    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // category id
        if (empty($category_id)) {
            $errors['Category ID'] = 'Category ID is required';
        } elseif (!$category->existsCategoryId($category_id)) {
            $errors['Category ID'] = "Category ID does not exist.";
        }
        

        // name
        if(empty($name)){
            $errors['name'] = 'Product Name is required';
        } elseif(!preg_match("/^[a-zA-Z ]+$/",$name)){
            $errors['name'] = "Only letters and white space allowed.";
        }  
        

        // description 
        if(!preg_match("/^[a-zA-Z ]+$/",$description)){
            $errors['description'] = "Only letters and white space allowed.";
        }elseif(strlen($description) < 2 || strlen($description) > 250){
            $errors['description'] = "Description must be between 2 and 250 characters.";
        }


        // sku 
        if (empty($sku)) {
            $errors['sku'] = 'SKU is required';
        } elseif ($products->existsSKU($sku)) {
            $errors['sku'] = "SKU already exists.";
        }

        // Unit Price
        if (empty($unit_price)) {
            $errors['Unit Price'] = 'Unit price is required.';
        } elseif (!is_numeric($unit_price) || $unit_price < 0) {
            $errors['Unit Price'] = 'Unit price must be a non-negative number.';
        }

        // Quantity in Stock
        if (!isset($quantity_in_stock) || $quantity_in_stock === '') {
            $errors['Quantity'] = 'Quantity in stock is required.';
        } elseif (!ctype_digit($quantity_in_stock) || (int)$quantity_in_stock < 0) {
            $errors['Quantity'] = 'Quantity in stock must be a non-negative integer.';
        }

         //image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('product_', true) . '.' . $fileExtension;
                $uploadFileDir = '../public/uploads/products/';
                $destPath = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $image = $newFileName;
                } else {
                    $errors['image'] = "There was an error uploading the file.";
                }
            } else {
                $errors['image'] = "Only JPG, JPEG, and PNG files are allowed.";
            }
        } 


        if(empty($errors)){

            $result = $products->create($category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image);
                
            if ($result) {
                echo json_encode(['success' => 'Product created successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to create product.']]);
            }
    
        }else{
            echo json_encode(['errors' => $errors]);
        }

    }

}



if($action == 'fetchProducts'){

    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 5;

    $categories = $products->fetchProducts($search, $page, $perPage);
    $total = $products->count($search);

    echo json_encode([
        'data' => $categories,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}


if($action == 'productProfile'){

    $id = $_POST['id'];

    $auth = $products->productProfile($id); 
    if($auth ){
        echo json_encode(['success' => true, 'product' => $auth]);
    }else{
        echo json_encode(['errors' => ['Product not found']]);
    }

}

if($action == 'updateProduct'){
   
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];
 

    $existingimage = $_POST['existing_image'] ?? ''; 

    $errors = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // category id
        if (empty($category_id)) {
            $errors['Category ID'] = 'Category ID is required';
        } elseif (!$category->existsCategoryId($category_id)) {
            $errors['Category ID'] = "Category ID does not exist.";
        }
        

        // name
        if(empty($name)){
            $errors['name'] = 'Product Name is required';
        } elseif(!preg_match("/^[a-zA-Z ]+$/",$name)){
            $errors['name'] = "Only letters and white space allowed.";
        }  
        

        // description 
        if(!preg_match("/^[a-zA-Z ]+$/",$description)){
            $errors['description'] = "Only letters and white space allowed.";
        }elseif(strlen($description) < 2 || strlen($description) > 250){
            $errors['description'] = "Description must be between 2 and 250 characters.";
        }


        // sku 
        if (empty($sku)) {
            $errors['sku'] = 'SKU is required';
        } elseif ($products->existsSKU($sku,$id)) {
            $errors['sku'] = "SKU already exists.";
        }

        // Unit Price
        if (empty($unit_price)) {
            $errors['Unit Price'] = 'Unit price is required.';
        } elseif (!is_numeric($unit_price) || $unit_price < 0) {
            $errors['Unit Price'] = 'Unit price must be a non-negative number.';
        }

        // Quantity in Stock
        if (!isset($quantity_in_stock) || $quantity_in_stock === '') {
            $errors['Quantity'] = 'Quantity in stock is required.';
        } elseif (!ctype_digit($quantity_in_stock) || (int)$quantity_in_stock < 0) {
            $errors['Quantity'] = 'Quantity in stock must be a non-negative integer.';
        }

         //image
        $image = $existingimage;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('product_', true) . '.' . $fileExtension;
                $uploadFileDir = '../public/uploads/products/';
                $destPath = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $image = $newFileName;
                } else {
                    $errors['image'] = "There was an error uploading the file.";
                }
            } else {
                $errors['image'] = "Only JPG, JPEG, and PNG files are allowed.";
            }
        } 


        if(empty($errors)){

            $result = $products->update($id,$category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image);
                
            if ($result) {
                echo json_encode(['success' => 'Product updated successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to updated product.']]);
            }
    
        }else{
            echo json_encode(['errors' => $errors]);
        }

    }





}


if($action == 'deleteProduct'){

    $id = $_POST['id'];

    $result = $products->delete($id);

    echo json_encode($result);
    exit;
}

?>