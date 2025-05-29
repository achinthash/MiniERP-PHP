<?php


require '../models/supplier.php';

$suppliers = new Suppliers();

$action = $_POST['action'];



if ($action == 'newSupplier') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
        if (empty($name)) {
            $errors['name'] = 'Supplier name is required.';
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $errors['name'] = 'Only letters and white space allowed.';
        }


        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        } elseif ($suppliers->existsByEmail($email)) {
            $errors['email'] = "Email already exists.";
        }


        if (!empty($phone)) {
            if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
                $errors['phone'] = 'Phone number must be 10 to 15 digits.';
            }
        }


        if (!empty($address) && strlen($address) > 300) {
            $errors['address'] = 'Address must not exceed 300 characters.';
        }


        if (empty($errors)) {

            $result = $suppliers->create($name, $email, $phone, $address); 

            if ($result) {
                echo json_encode(['success' => 'Suppliers created successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to create Suppliers.']]);
            }

        } else {
            echo json_encode(['errors' => $errors]);
        }
    }

}


if($action == 'suppliersProfile'){

    $id = $_POST['id'];

    $auth = $suppliers->suppliersProfile($id); 
    if($auth ){
        echo json_encode(['success' => true, 'suppliers' => $auth]);
    }else{
        echo json_encode(['errors' => ['Suppliers not found']]);
    }

}


if($action == 'fetchSuppliers'){

    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 5;

    $data = $suppliers->suppliers($search, $page, $perPage);
    $total = $suppliers->count($search);

    echo json_encode([
        'data' => $data,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}


if ($action == 'updateSupplier') {

    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $errors = [];

 
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
        if (empty($name)) {
            $errors['name'] = 'Supplier name is required.';
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $errors['name'] = 'Only letters and white space allowed.';
        }


        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        } elseif ($suppliers->existsByEmail($email,$id)) {
            $errors['email'] = "Email already exists.";
        }


        if (!empty($phone)) {
            if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
                $errors['phone'] = 'Phone number must be 10 to 15 digits.';
            }
        }


        if (!empty($address) && strlen($address) > 300) {
            $errors['address'] = 'Address must not exceed 300 characters.';
        }


        if (empty($errors)) {

            $result = $suppliers->update($id,$name, $email, $phone, $address); 

            if ($result) {
                echo json_encode(['success' => 'Supplier updated successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to updated Supplier.']]);
            }

        } else {
            echo json_encode(['errors' => $errors]);
        }
    }

}


if($action == 'deleteSupplier'){

    $id = $_POST['id'];

    $result = $suppliers->delete($id);

    echo json_encode($result);
    exit;
}







?>