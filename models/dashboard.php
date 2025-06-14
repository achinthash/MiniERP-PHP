<?php

require_once '../config/Database.php';

class Dashboard {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function StockQuantity() {
        $stmt = $this->conn->prepare("SELECT name, quantity_in_stock FROM products ORDER BY quantity_in_stock DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function TopSellingProducts() {
        $stmt = $this->conn->prepare("
            SELECT p.name, SUM(si.quantity) AS total_sold 
            FROM sales_items si 
            JOIN products p ON si.product_id = p.id 
            GROUP BY si.product_id 
            ORDER BY total_sold DESC 
            LIMIT 5
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function DailySalesThisMonth() {
        $stmt = $this->conn->prepare("
            SELECT DAY(sale_date) AS day, SUM(grand_total) AS total_sales
            FROM sales
            WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())
            GROUP BY DAY(sale_date)
            ORDER BY day
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function PaymentMethodUsage() {
        $stmt = $this->conn->prepare("SELECT payment_method, COUNT(*) AS count FROM sales GROUP BY payment_method");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function MonthlySales() {
        $stmt = $this->conn->prepare("
            SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month, SUM(grand_total) AS total
            FROM sales
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function TopCustomers() {
        $stmt = $this->conn->prepare("
            SELECT c.name, SUM(s.grand_total) AS total_spent 
            FROM sales s 
            JOIN customers c ON s.customer_id = c.id 
            GROUP BY s.customer_id 
            ORDER BY total_spent DESC 
            LIMIT 5
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
