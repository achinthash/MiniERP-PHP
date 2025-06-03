<?php

require '../models/purchaseOrders.php';

$purchaseOrder = new PurchaseOrders();

$action = $_POST['action'] ?? '';


if($action == 'suppliersList'){

    $suppliers = $purchaseOrder->suppliersList();
    echo json_encode($suppliers);
}

if($action == 'productsList'){

    $products = $purchaseOrder->productsList();
    echo json_encode($products);
}

 

if ($action === 'newPurchaseOrder') {
    
    $supplier_id = $_POST['supplier_id'] ?? '';
    $order_date = $_POST['order_date'] ?? '';
    $created_by = $_POST['created_by'] ?? '';
    $items = $_POST['items'] ?? [];

    $errors = [];

    if (empty($supplier_id)) $errors['supplier_id'] = 'Supplier is required.';
    if (empty($order_date)) $errors['order_date'] = 'Order date is required.';
    if (empty($created_by)) $errors['created_by'] = 'Creator is required.';
    if (empty($items) || !is_array($items)) $errors['items'] = 'At least one item is required.';

    if (empty($errors)) {
        $total_amount = 0;
        foreach ($items as &$item) {
            if (!isset($item['product_id'], $item['quantity'], $item['unit_price'])) {
                $errors['items'] = 'Each item must have product_id, quantity, and unit_price.';
                break;
            }
            $item['line_total'] = $item['quantity'] * $item['unit_price'];
            $total_amount += $item['line_total'];
        }

        if (empty($errors)) {
            $data = [
                'supplier_id' => $supplier_id,
                'order_date' => $order_date,
                'total_amount' => $total_amount,
                'created_by' => $created_by
            ];

            $result = $purchaseOrder->create($data, $items);

            if (isset($result['success']) && $result['success']) {
                echo json_encode(['success' => true, 'message' => 'Purchase order updated successfully.']);

            } else {
                echo json_encode(['errors' => ['Failed to create purchase order.']]);
            }
        } else {
            echo json_encode(['errors' => $errors]);
        }
    } else {
        echo json_encode(['errors' => $errors]);
    }
}


if ($action === 'updatePurchaseOrder') {
    $purchase_order_id = $_POST['id'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';
    $order_date = $_POST['order_date'] ?? '';
    $status = $_POST['status'] ?? '';

    $items = $_POST['items'] ?? [];


    $errors = [];

    if (empty($purchase_order_id)) $errors['id'] = 'Purchase order ID is required.';
    if (empty($supplier_id)) $errors['supplier_id'] = 'Supplier is required.';
    if (empty($order_date)) $errors['order_date'] = 'Order date is required.';
    if (empty($status)) $errors['status'] = 'Status is required.';
    if (empty($items) || !is_array($items)) $errors['items'] = 'At least one item is required.';

    // Validate and calculate total amount
    $total_amount = 0;
    foreach ($items as &$item) {
        
        if (!isset($item['id'], $item['quantity'], $item['unit_price'])) {
            $errors['items'] = 'Each item must have id, quantity, and unit_price.';
            break;
        }


        $item['line_total'] = $item['quantity'] * $item['unit_price'];
        $total_amount += $item['line_total'];
    }

    if (empty($errors)) {
        $data = [
            'supplier_id' => $supplier_id,
            'order_date' => $order_date,
            'total_amount' => $total_amount,
            'status' => $status,      
        ];

        $result = $purchaseOrder->update($purchase_order_id, $data, $items);

        if (isset($result['success']) && $result['success']) {
            echo json_encode(['success' => 'Purchase order updated successfully.']);
        } else {
            echo json_encode(['errors' => ['Failed to update purchase order.']]);
        }
    } else {
        echo json_encode(['errors' => $errors]);
    }
}




if ($action === 'fetchPurchaseOrders') {
    $search = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = 10;

    $data = $purchaseOrder->getAll($search, $page, $perPage);
    $total = $purchaseOrder->count($search);

    echo json_encode([
        'data' => $data,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage
    ]);
    exit;
}

if ($action === 'getPurchaseOrder') {
    $id = $_POST['id'] ?? '';
 
    $order = $purchaseOrder->getOrderWithItems($id);

    if ($order) {
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['errors' => ['Purchase order not found.']]);
    }
}

if ($action === 'deletePurchaseOrder') {
    $id = $_POST['id'] ?? '';

    $result = $purchaseOrder->delete($id);
    echo json_encode($result);
}


if ($action === 'deletePurchaseOrderItems') {
    $id = $_POST['id'] ?? '';

    $result = $purchaseOrder->deletePoItem($id);
    echo json_encode($result);
}




if ($action === 'poApproval') {

    $id = $_POST['id'] ?? '';
    $approved_by = $_POST['approved_by'] ?? '';

    $errors = [];

    if (empty($id)) $errors['id'] = 'Purchase order ID is required.';
    if (empty($approved_by)) $errors['approved_by'] = 'Approved By is required.';

    if (empty($errors)) {
        $result = $purchaseOrder->updateApproval($id, $approved_by);

        if (isset($result['success']) && $result['success']) {
            echo json_encode(['success' => 'Purchase order updated successfully.']);
        } else {
            echo json_encode(['errors' => ['Failed to update purchase order.']]);
        }
    } else {
        echo json_encode(['errors' => $errors]);
    }
}





?>
