<?php

require '../models/sales.php';

$sales = new Sales();

$action = $_POST['action'] ?? '';

// List customers
if ($action === 'customersList') {
    $customers = $sales->customersList();
    echo json_encode($customers);
}

// List products
if ($action === 'productsList') {
    $products = $sales->productsList();
    echo json_encode($products);
}

// Create new sales order
if ($action === 'newSalesOrder') {
    $customer_id = $_POST['customer_id'] ?? '';
    $sale_date = $_POST['order_date'] ?? ''; // use 'sale_date' to match DB column
    $created_by = $_POST['created_by'] ?? '';
    $items = $_POST['items'] ?? [];

    $errors = [];

    // Basic validations
    if (empty($customer_id)) $errors['customer_id'] = 'Customer is required.';
    if (empty($sale_date)) $errors['sale_date'] = 'Sale date is required.';
    if (empty($created_by)) $errors['created_by'] = 'Creator is required.';
    if (empty($items) || !is_array($items)) $errors['items'] = 'At least one item is required.';

    if (empty($errors)) {
        $total_amount = 0;

        // Validate and compute item totals
        foreach ($items as &$item) {
            if (!isset($item['product_id'], $item['quantity'], $item['unit_price'])) {
                $errors['items'] = 'Each item must have product_id, quantity, and unit_price.';
                break;
            }

            // Typecast for safety
            $item['quantity'] = (int)$item['quantity'];
            $item['unit_price'] = (float)$item['unit_price'];
            $item['line_total'] = $item['quantity'] * $item['unit_price'];
            $total_amount += $item['line_total'];
        }

        if (empty($errors)) {
            // Optional values — you can set or receive from $_POST if needed
            $discount = $_POST['discount'] ?? 0.00;
            $payment_status = $_POST['payment_status'] ?? 'pending';
            $payment_method = $_POST['payment_method'] ?? null;
            $remarks = $_POST['remarks'] ?? null;

            $grand_total = $total_amount - $discount;

            $data = [
                'customer_id'     => $customer_id,
                'sale_date'       => $sale_date,
                'total_amount'    => $total_amount,
                'discount'        => $discount,
                'grand_total'     => $grand_total,
                'payment_status'  => $payment_status,
                'payment_method'  => $payment_method,
                'remarks'         => $remarks,
                'created_by'      => $created_by,
            ];

            $result = $sales->create($data, $items);

            if (isset($result['success']) && $result['success']) {
                echo json_encode(['success' => true, 'message' => 'Sales created successfully.', 'sale_id' => $result['id']]);
            } else {
                echo json_encode(['errors' => ['Failed to create sales order.', $result['error'] ?? 'Unknown error']]);
            }
        } else {
            echo json_encode(['errors' => $errors]);
        }
    } else {
        echo json_encode(['errors' => $errors]);
    }
}


// Fetch all sales orders
if ($action === 'fetchSalesOrders') {
    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 10;

    $data = $sales->getAll($search, $page, $perPage);
    $total = $sales->count($search);

    echo json_encode([
        'data' => $data,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
}


// Get a single sales order with items
if ($action === 'getSalesOrder') {
    $id = $_POST['id'] ?? '';

    $order = $sales->getOrderWithItems($id);

    if ($order) {
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['errors' => ['Sales order not found.']]);
    }
}

// Delete a sales order
if ($action === 'deleteSalesOrder') {
    $id = $_POST['id'] ?? '';
    $result = $sales->delete($id);
    echo json_encode($result);
}

// Delete a sales order item
if ($action === 'deleteSalesItem') {
    $id = $_POST['id'] ?? '';
    $result = $sales->deleteSalesItem($id);
    echo json_encode($result);
}



// Update sales order

if ($action === 'updateSalesOrder') {
    $sales_order_id = $_POST['id'] ?? '';
    $customer_id = $_POST['customer_id'] ?? '';
    $sale_date = $_POST['sale_date'] ?? '';
    $payment_status = $_POST['payment_status'] ?? '';
    $payment_method = $_POST['payment_method'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $items = $_POST['items'] ?? [];
    $discount = floatval($_POST['discount'] ?? 0.00);

    $errors = [];

    // Validation
    if (empty($sales_order_id)) $errors['id'] = 'Sales order ID is required.';
    if (empty($customer_id)) $errors['customer_id'] = 'Customer is required.';
    if (empty($sale_date)) $errors['sale_date'] = 'Order date is required.';
    if (empty($payment_status)) $errors['payment_status'] = 'Payment status is required.';
    if (empty($items) || !is_array($items)) {
        $errors['items'] = 'At least one item is required.';
    }

    $total_amount = 0;

    foreach ($items as &$item) {
        if (!isset($item['id'], $item['quantity'], $item['unit_price'])) {
            $errors['items'] = 'Each item must have id, quantity, and unit price.';
            break;
        }

        $item['quantity'] = (float) $item['quantity'];
        $item['unit_price'] = (float) $item['unit_price'];
        $item['total'] = $item['quantity'] * $item['unit_price'];
        $total_amount += $item['total'];
    }

    $grand_total = $total_amount - $discount;

    // If validation fails
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Data to update
    $data = [
        'customer_id' => $customer_id,
        'sale_date' => $sale_date,
        'total_amount' => $total_amount,
        'discount' => $discount,
        'grand_total' => $grand_total,
        'payment_status' => $payment_status,
        'payment_method' => $payment_method,
        'remarks' => $remarks
    ];

    // Call update method
    $result = $sales->update($sales_order_id, $data, $items);

    if (isset($result['success']) && $result['success'] === true) {
        echo json_encode(['success' => true, 'message' => 'Sales updated successfully.']);
    } else {
        echo json_encode([
            'success' => false,
            'errors' => ['Failed to update sales.', $result['error'] ?? 'Unknown error occurred.']
        ]);
    }
}



?>
