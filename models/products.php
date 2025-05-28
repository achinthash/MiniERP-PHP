<?php

require_once '../config/Database.php';

class Products{

    private $table = 'products';
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->connect();
    }
   
    

    public function create($category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image){

        try{
            $stmt = $this->conn->prepare("INSERT INTO products (category_id, sku, name, description, unit_price, quantity_in_stock, image ) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image]);

            return ['success' => true];
        } catch(PDOException $e){
            return ['error' => $e->getMessage()];
        }
    }


    public function existsSKU($sku, $excludeId = null) {
        if ($excludeId !== null) {
            $sql = "SELECT COUNT(*) FROM products WHERE sku = ? AND id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$sku, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM products WHERE sku = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$sku]);
        }
    
        return $stmt->fetchColumn() > 0;
    }


    public function fetchProducts($search, $page, $perPage){

        $offset = ($page - 1) * $perPage;

        $stmt = $this->conn->prepare("SELECT products.*,categories.name as category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.name LIKE ? LIMIT $perPage OFFSET $offset");

        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function count($search){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM products WHERE name LIKE ?");
        $stmt->execute(["%$search%"]);
        return $stmt->fetchColumn();
    }

    public function productProfile($id){

        $stmt = $this->conn->prepare("SELECT products.*,categories.name as category_name  FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id,$category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image){
        try {
            $stmt = $this->conn->prepare("UPDATE products SET  category_id = ?, 
            sku = ?, 
            name = ?, 
            description = ?, 
            unit_price = ?, 
            quantity_in_stock = ?, 
            image = ?  WHERE id = ?");
            $stmt->execute([$category_id,$sku,$name,$description,$unit_price,$quantity_in_stock,$image, $id]);

            return ['success' => 'Product updated successfully'];

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function delete($productId){
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        return ['success' => true, 'message' => 'Product deleted successfully.'];
        exit;
    }
    
   
}



?>