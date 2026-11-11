<?php
class Koneksi {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "db_kasir";
    public $koneksi;

    public function __construct() {
        $this->koneksi = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->koneksi->connect_error) {
            die("Koneksi gagal: " . $this->koneksi->connect_error);
        }
    }
}
?>