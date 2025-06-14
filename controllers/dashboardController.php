<?php

require '../models/dashboard.php';

$dashboard = new Dashboard();

$action = $_POST['action'];

switch ($action) {
    case 'StockQuantity':
        echo json_encode($dashboard->StockQuantity());
        break;

    case 'TopSellingProducts':
        echo json_encode($dashboard->TopSellingProducts());
        break;

    case 'DailySalesThisMonth':
        echo json_encode($dashboard->DailySalesThisMonth());
        break;

    case 'PaymentMethodUsage':
        echo json_encode($dashboard->PaymentMethodUsage());
        break;

    case 'MonthlySales':
        echo json_encode($dashboard->MonthlySales());
        break;

    case 'TopCustomers':
        echo json_encode($dashboard->TopCustomers());
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
exit;
