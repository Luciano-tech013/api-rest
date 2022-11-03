<?php

class AutoModel {

    private $db;

    public function __construct(){
        $this->db = $this->getDb();
    }

    private function getDB() {
        $db = new PDO('mysql:host=localhost;'.'dbname=motorsport_bd;charset=utf8', 'root', '');
        return $db;
    }

    public function getAll(){
        $query = $this->db->prepare("SELECT * FROM autos");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get($id){
        $query = $this->db->prepare("SELECT * FROM autos WHERE id = ?");
        $query->execute([$id]);
        
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }

    public function add($nombres, $descripcion, $modelo, $marca, $id_categoria){
        $query = $this->db->prepare("INSERT INTO autos (nombres, descripcion, modelo, marca, id_categorias) VALUES (?,?,?,?,?)");
        $query->execute([$nombres, $descripcion, $modelo, $marca, $id_categoria]);

    }

    public function delete($id){
        $query = $this->db->prepare("DELETE FROM autos WHERE id = ?");
        $query->execute([$id]);

    }

    public function update($id, $nombre, $descripcion, $modelo, $marca, $id_categoria){
        $query = $this->db->prepare("UPDATE `autos` SET `nombres` = ?, `descripcion` = ?, `modelo` = ?, `marca` = ?, `id_categorias` = ? WHERE `autos`.`id` = ?");
        $query->execute([$nombre, $descripcion, $modelo, $marca, $id_categoria,$id]);
    }
}