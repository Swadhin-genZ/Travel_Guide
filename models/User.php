<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($name, $email, $passwordHash, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password_hash, role, is_verified) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);
        return $stmt->execute();
    }

    public function updateProfile($id, $name, $email, $picturePath = null) {
        if ($picturePath) {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, email=?, profile_picture=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $email, $picturePath, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $email, $id);
        }
        return $stmt->execute();
    }

    public function updatePassword($id, $hash) {
        $stmt = $this->conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }

    public function setRememberToken($id, $tokenHash) {
        $stmt = $this->conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
        $stmt->bind_param("si", $tokenHash, $id);
        return $stmt->execute();
    }

    public function findByRememberToken($tokenHash) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE remember_token=?");
        $stmt->bind_param("s", $tokenHash);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function clearRememberToken($id) {
        $stmt = $this->conn->prepare("UPDATE users SET remember_token=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


    public function getAll() {
        $result = $this->conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function toggleVerify($id, $status) {
        $stmt = $this->conn->prepare("UPDATE users SET is_verified=? WHERE id=?");
        $stmt->bind_param("ii", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function createVerified($name, $email, $hash, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password_hash, role, is_verified) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);
        return $stmt->execute();
    }

    public function getCounts() {
        $res = $this->conn->query("SELECT role, COUNT(*) as cnt FROM users GROUP BY role");
        $counts = [];
        while ($row = $res->fetch_assoc()) $counts[$row['role']] = $row['cnt'];
        return $counts;
    }
}