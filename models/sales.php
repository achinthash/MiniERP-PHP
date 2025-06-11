<?php

require_once '../config/Database.php';

class Sales
{
    private $conn;
    private $table = 'sales';

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // list of customers
    public function customersList() {
        $sql = "SELECT id, name FROM customers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // list of products
    public function productsList() {
        $sql = "SELECT id, name FROM products";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data, $items)
{
    try {
        $this->conn->beginTransaction();

        // Insert into sales table
        $stmt = $this->conn->prepare("
            INSERT INTO sales (
                customer_id,
                sale_date,
                total_amount,
                discount,
                grand_total,
                payment_status,
                payment_method,
                remarks,
                created_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['customer_id'],
            $data['sale_date'],
            $data['total_amount'],
            $data['discount'] ?? 0.00,
            $data['grand_total'],
            $data['payment_status'] ?? 'pending',
            $data['payment_method'] ?? null,
            $data['remarks'] ?? null,
            $data['created_by']
        ]);

        $saleId = $this->conn->lastInsertId();

        // Insert into sales_items table
        $itemStmt = $this->conn->prepare("
            INSERT INTO sales_items (sale_id, product_id, quantity, unit_price, total)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {
            $itemStmt->execute([
                $saleId,
                $item['product_id'],
                $item['quantity'],
                $item['unit_price'],
                $item['quantity'] * $item['unit_price']
            ]);
        }

        $this->conn->commit();
        return ['success' => true, 'id' => $saleId];

    } catch (PDOException $e) {
        $this->conn->rollBack();
        return ['error' => $e->getMessage()];
    }
}


    public function getAll($search, $page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $query = "
            SELECT so.*, c.name AS customer_name 
            FROM {$this->table} so
            JOIN customers c ON so.customer_id = c.id
            WHERE c.name LIKE ?
            ORDER BY so.created_at DESC
            LIMIT $perPage OFFSET $offset
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$search%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($search)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) 
            FROM {$this->table} so
            JOIN customers c ON so.customer_id = c.id
            WHERE c.name LIKE ?
        ");
        $stmt->execute(["%$search%"]);

        return $stmt->fetchColumn();
    }

    public function getOrderWithItems($id)
{
    // Fetch sales order with customer details
    $stmt = $this->conn->prepare("
        SELECT s.*, 
               c.name AS customer_name,
               c.email AS customer_email,
               c.address AS customer_address,
               c.phone AS customer_phone
        FROM sales s
        JOIN customers c ON s.customer_id = c.id
        WHERE s.id = ?
    ");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Fetch related sales items with product names
        $itemStmt = $this->conn->prepare("
            SELECT si.*, p.name AS product_name 
            FROM sales_items si
            JOIN products p ON si.product_id = p.id
            WHERE si.sale_id = ?
        ");
        $itemStmt->execute([$id]);
        $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $order;
}


public function update($saleId, $data, $items)
{
    try {
        $this->conn->beginTransaction();

        // Update sales table
        $stmt = $this->conn->prepare("
            UPDATE sales SET
                customer_id = ?,
                sale_date = ?,
                total_amount = ?,
                discount = ?,
                grand_total = ?,
                payment_status = ?,
                payment_method = ?,
                remarks = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['customer_id'],
            $data['sale_date'],
            $data['total_amount'],
            $data['discount'] ?? 0.00,
            $data['grand_total'],
            $data['payment_status'] ?? 'pending',
            $data['payment_method'] ?? null,
            $data['remarks'] ?? null,
            $saleId
        ]);

        $itemStmt = $this->conn->prepare("UPDATE sales_items SET quantity = ?, unit_price = ?, total = ? WHERE id = ?");

        foreach ($items as $item) {
            $itemStmt->execute([
                $item['quantity'],
                $item['unit_price'],
                $item['quantity'] * $item['unit_price'],
                $item['id'],
            ]);
        }

        $this->conn->commit();
        return ['success' => true];

    } catch (PDOException $e) {
        $this->conn->rollBack();
        return ['error' => $e->getMessage()];
    }
}

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);

            return ['success' => true, 'message' => 'Sales Order deleted successfully.'];

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteSalesItem($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM sales_items WHERE id = ?");
            $stmt->execute([$id]);

            return ['success' => true, 'message' => 'Sales item deleted successfully.'];

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

?>
