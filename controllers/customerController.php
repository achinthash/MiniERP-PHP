<?php


require '../models/customers.php';

$customers = new Customers();

$action = $_POST['action'];



if ($action == 'newCustomer') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
        if (empty($name)) {
            $errors['name'] = 'Customer name is required.';
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $errors['name'] = 'Only letters and white space allowed.';
        }


        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        } elseif ($customers->existsByEmail($email)) {
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

            $result = $customers->create($name, $email, $phone, $address); 

            if ($result) {
                echo json_encode(['success' => 'Customer created successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to create customer.']]);
            }

        } else {
            echo json_encode(['errors' => $errors]);
        }
    }

}


if($action == 'customerProfile'){

    $id = $_POST['id'];

    $auth = $customers->customerProfile($id); 
    if($auth ){
        echo json_encode(['success' => true, 'customer' => $auth]);
    }else{
        echo json_encode(['errors' => ['Customer not found']]);
    }

}


if($action == 'fetchCustomers'){

    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 5;

    $categories = $customers->customers($search, $page, $perPage);
    $total = $customers->count($search);

    echo json_encode([
        'data' => $categories,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}


if ($action == 'updateCustomer') {

    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $errors = [];

 
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
        if (empty($name)) {
            $errors['name'] = 'Customer name is required.';
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $errors['name'] = 'Only letters and white space allowed.';
        }


        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        } elseif ($customers->existsByEmail($email,$id)) {
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

            $result = $customers->update($id,$name, $email, $phone, $address); 

            if ($result) {
                echo json_encode(['success' => 'Customer updated successfully.']);
            } else {
                echo json_encode(['errors' => ['Failed to updated customer.']]);
            }

        } else {
            echo json_encode(['errors' => $errors]);
        }
    }

}


if($action == 'deleteCustomer'){

    $id = $_POST['id'];

    $result = $customers->delete($id);

    echo json_encode($result);
    exit;
}







?>