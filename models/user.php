<?php
require_once '../config/Database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create($username, $email, $password, $userrole, $profilePicture) {

        try{
            $hashPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, userrole, profile_picture) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashPassword, $userrole, $profilePicture]);
            return ['success' => true];

         } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function existsByEmail($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }


    public function fetchUser($id){
        
        $stmt = $this->conn->prepare("SELECT id,username,email,profile_picture,userrole,created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function paginate($search,$page,$perPage){
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM users WHERE username LIKE ? LIMIT $perPage OFFSET $offset";
        $stmt = $this->conn->prepare($query);

        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  

    public  function count($search) {
        $stmt =  $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username LIKE ?");
        $stmt->execute(["%$search%"]);
        return $stmt->fetchColumn();
    }


    public function update($id, $username, $email, $userrole, $profilePicture) {
        $sql = "UPDATE $this->table 
                SET username = :username, 
                    email = :email, 
                    userrole = :userrole, 
                    profile_picture = :profile_picture 
                WHERE id = :id";
    
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
            ':userrole' => $userrole,
            ':profile_picture' => $profilePicture
        ]);
    
    }


    public function passwordUpdate($userId,$oldPassword,$newPassword,$confirmPassword){

        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user || !password_verify($oldPassword, $user['password'])){
            return ['success' => false, 'message' => 'Old password is incorrect.'];
        }

        $hashedPassword = password_hash($newPassword,PASSWORD_DEFAULT);
        $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $userId]);
        
        return ['success' => true, 'message' => 'Password updated successfully.'];
        exit;
    }


    public function delete($userId,$password) {

        if (empty($password)) {
            return ['success' => false, 'message' => 'Password field is required!'];
        }

        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Password is incorrect.'];
        }
        
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        return ['success' => true, 'message' => 'User deleted successfully.'];
        exit;
    }



    public function login($email, $password) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

}
