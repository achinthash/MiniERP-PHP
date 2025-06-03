<?php

require_once '../config/Database.php';

class PurchaseOrders
{
    private $conn;
    private $table = 'purchase_orders';

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }


     // list of suppliers
    public function suppliersList() {
        $sql = "SELECT id, name FROM suppliers";
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

            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (supplier_id, order_date, total_amount, created_by)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['supplier_id'],
                $data['order_date'],
                $data['total_amount'],
                $data['created_by']
            ]);

            $orderId = $this->conn->lastInsertId();

            $itemStmt = $this->conn->prepare("
                INSERT INTO purchase_order_items (purchase_order_id, product_id, quantity, unit_price, line_total)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['quantity'] * $item['unit_price']
                ]);
            }

            $this->conn->commit();

            return ['success' => true, 'id' => $orderId];

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function getAll($search, $page, $perPage)
    {
        $offset = ($page - 1) * $perPage;
        $query = "
            SELECT po.*, s.name AS supplier_name 
            FROM {$this->table} po
            JOIN suppliers s ON po.supplier_id = s.id
            WHERE s.name LIKE ?
            ORDER BY po.created_at DESC
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
            FROM {$this->table} po
            JOIN suppliers s ON po.supplier_id = s.id
            WHERE s.name LIKE ?
        ");
        $stmt->execute(["%$search%"]);

        return $stmt->fetchColumn();
    }

    public function getOrderWithItems($id)
    {
        $stmt = $this->conn->prepare("
            SELECT po.*, 
            s.name AS supplier_name,
            s.email AS supplier_email,
            s.address AS supplier_address,
            s.phone AS supplier_phone

            FROM {$this->table} po
            JOIN suppliers s ON po.supplier_id = s.id
            WHERE po.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $itemStmt = $this->conn->prepare("
                SELECT poi.*, p.name AS product_name 
                FROM purchase_order_items poi
                JOIN products p ON poi.product_id = p.id
                WHERE poi.purchase_order_id = ?
            ");
            $itemStmt->execute([$id]);
            $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $order;
    }



    public function updateApproval($id, $approvedBy) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET approved_by = ?, approved_at = NOW() WHERE id = ?");
            $stmt->execute([$approvedBy, (int)$id]);
    
            return ['success' => true];
            
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    



    public function update($id, $data, $items)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                UPDATE {$this->table}
                SET supplier_id = ?, order_date = ?, total_amount = ?, updated_at = NOW(), status = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['supplier_id'],
                $data['order_date'],
                $data['total_amount'],
                $data['status'],
         
                $id
            ]);

             // Update each item
            $itemStmt = $this->conn->prepare("
            UPDATE purchase_order_items
            SET quantity = ?, unit_price = ?, line_total = ?
            WHERE id = ?
            ");

            foreach ($items as $item) {
                $itemStmt->execute([
                    $item['quantity'],
                    $item['unit_price'],
                    $item['quantity'] * $item['unit_price'],
                    $item['id'] 
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

            return ['success' => true, 'message' => 'Purchase Order deleted successfully.'];
            exit;

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function deletePoItem($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM purchase_order_items WHERE id = ?");
            $stmt->execute([$id]);

            return ['success' => true, 'message' => 'Purchase Order Item deleted successfully.'];
            exit;

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }


}

?>
