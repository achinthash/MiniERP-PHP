<?php

require_once '../config/Database.php';


class Suppliers{

    private $conn;
    private $table = 'suppliers';

    public function __construct(){
        $db = new Database();
        $this->conn = $db->connect();
    }


    public function create($name ,$email,$phone,$address){

        try{
            $stmt = $this->conn->prepare("INSERT INTO suppliers (name, email, phone, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name ,$email,$phone,$address]);
            
            return ['success' => true];

        } catch(PDOException $e){
            return ['error' => $e->getMessage()];
        }
    }

    public function existsByEmail($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM suppliers WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }


   


    public function suppliers($search, $page, $perPage){

        $offset = ($page - 1) * $perPage;
        $query = "SELECT * from suppliers WHERE name LIKE ? LIMIT $perPage OFFSET $offset";
        $stmt = $this->conn->prepare($query);

        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($search){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM suppliers WHERE name LIKE ?");
        $stmt->execute(["%$search%"]);
        return $stmt->fetchColumn();
    }

    public function suppliersProfile($id){

        $stmt = $this->conn->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function update($id,$name, $email, $phone, $address){
        try {
            $stmt = $this->conn->prepare("UPDATE suppliers SET 
            name = ?, 
            email = ?, 
            phone = ?, 
            address = ?  WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $address,$id]);

            return ['success' => 'Suppliers updated successfully'];

        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }

    }


    public function delete($productId){
        $stmt = $this->conn->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$productId]);

        return ['success' => true, 'message' => 'Suppliers deleted successfully.'];
        exit;
    }


}


?>