<?php

class CategoriaModel {

    private $db;
    
    public function __construct(){
        $this->db = $this->getDb();
    }

    private function getDB() {
        $db = new PDO('mysql:host=localhost;'.'dbname=motorsport_bd;charset=utf8', 'root', '');
        return $db;
    }

    public function getAll(){
        $query = $this->db->prepare("SELECT * FROM categorias");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($id){
        /**Traigo registros mediante el ID para mostrar el form.edit precargado */
        $query = $this->db->prepare("SELECT * FROM categorias WHERE id_categorias = ?");
        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function add($nombre, $descripcion, $tipo){
        $query = $this->db->prepare("INSERT INTO categorias (nombre, descripcion, tipo) VALUES(?,?,?)");
        $query->execute([$nombre, $descripcion, $tipo]);

        return $this->db->lastInsertId();
    }
    
    public function delete($id){
        try {
            $query = $this->db->prepare("DELETE FROM categorias WHERE id_categorias = ?");
            $query->execute([$id]); 
        } catch(Exception $e){
            return $e;
        }
       
    }

    public function update($id, $nombre, $descripcion, $tipo){
        $query = $this->db->prepare("UPDATE `categorias` SET `nombre` = ?, `descripcion` = ?, `tipo` = ? WHERE `categorias`.`id_categorias` = ?");
        $query->execute([$nombre, $descripcion, $tipo, $id]);
    }

    public function getAllByOrder($sort, $order){
        $query = $this->db->prepare("SELECT * FROM categorias ORDER BY $sort $order");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByFilter($value){
        $query = $this->db->prepare("SELECT * FROM categorias WHERE tipo = ?");
        $query->execute([$value]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllByPagination($offset, $limit){
        $query = $this->db->prepare('SELECT * FROM categorias LIMIT :paginas, :limite');

        $query->bindParam(':paginas', $offset, PDO::PARAM_INT);
        $query->bindParam(':limite', $limit, PDO::PARAM_INT);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}