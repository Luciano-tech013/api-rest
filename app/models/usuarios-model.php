<?php

class UsuariosModel {

    private $db;
    
    public function __construct(){
        $this->db = $this->getDb();
    }

    private function getDB() {
        $db = new PDO('mysql:host=localhost;'.'dbname=motorsport_bd;charset=utf8', 'root', '');
        return $db;
    }

    public function get($user){
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $query->execute([$user]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}