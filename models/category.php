<?php

require_once '../config/Database.php';

class Category{

    private $conn;
    private $table = 'categories';

    public function __construct(){
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($name,$description){
        try{
            $stmt = $this->conn->prepare("INSERT INTO categories (name, description) VALUES (?,?)");
            $stmt->execute([$name,$description]);

            return ['success' => true];
        } catch(PDOException $e){
            return ['error' => $e->getMessage()];
        }
    }

    public function existsByName($name, $excludeId = null) {
        if ($excludeId !== null) {
            $sql = "SELECT COUNT(*) FROM categories WHERE name = ? AND id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM categories WHERE name = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name]);
        }
    
        return $stmt->fetchColumn() > 0;
    }
    

    public function categories($search, $page, $perPage){

        $offset = ($page - 1) * $perPage;
        $query = "SELECT * from categories WHERE name LIKE ? LIMIT $perPage OFFSET $offset";
        $stmt = $this->conn->prepare($query);

        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($search){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM categories WHERE name LIKE ?");
        $stmt->execute(["%$search%"]);
        return $stmt->fetchColumn();
    }

    public function fetchCategory($id){
        $stmt = $this->conn->prepare("SELECT id,name,description FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $description){
        try {
            $stmt = $this->conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $id]);

            return ['success' => 'Category updated successfully'];

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function delete($categoryId){
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);

        return ['success' => true, 'message' => 'Category deleted successfully.'];
        exit;
    }

    public function existsCategoryId($category_id){
        $stmt = $this->conn->prepare("SELECT id FROM categories WHERE id = ?");
        $stmt->execute([$category_id]);
    
        return $stmt->fetchColumn() !== false;
    }
    
    
    


}


?>