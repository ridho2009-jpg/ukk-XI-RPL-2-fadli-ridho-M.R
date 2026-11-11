<?php
require_once 'Koneksi.php';

class users extends Koneksi {
    
    public function login($username, $password) {
        $stmt = $this->koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if ($password == $row['password']) {
                session_start();
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                return true;
            }
        }
        return false;
    }
}
?>