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
        $query = $this->db->prepare("SELECT autos.*, categorias.nombre FROM autos JOIN categorias ON autos.id_categorias = categorias.id_categorias");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get($id){
        $query = $this->db->prepare("SELECT autos.*, categorias.nombre FROM autos JOIN categorias ON autos.id_categorias = categorias.id_categorias WHERE id = ?");
        $query->execute([$id]);
        
        return $query->fetch(PDO::FETCH_OBJ); 
    }

    public function add($nombres, $descripcion, $modelo, $marca, $id_categoria){
        try {
            $query = $this->db->prepare("INSERT INTO autos (nombres, descripcion, modelo, marca, id_categorias) VALUES (?,?,?,?,?)");
            $query->execute([$nombres, $descripcion, $modelo, $marca, $id_categoria]);

            return $this->db->lastInsertId();
        }
        catch(\Throwable $th){
            return false;
        }
    }

    public function delete($id){
        $query = $this->db->prepare("DELETE FROM autos WHERE id = ?");
        $query->execute([$id]);

    }

    public function update($id, $nombre, $descripcion, $modelo, $marca, $id_categoria){
        try {
            $query = $this->db->prepare("UPDATE `autos` SET `nombres` = ?, `descripcion` = ?, `modelo` = ?, `marca` = ?, `id_categorias` = ? WHERE `autos`.`id` = ?");
            $query->execute([$nombre, $descripcion, $modelo, $marca, $id_categoria, $id]); 
        } 
        catch(Exception $e){
            return $e;
        }
    }

    public function getAllByOrder($sort, $order){
        $query = $this->db->prepare("SELECT autos.*, categorias.nombre FROM autos JOIN categorias ON autos.id_categorias = categorias.id_categorias ORDER BY $sort $order");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByFilter($value){
        $query = $this->db->prepare("SELECT * FROM autos WHERE modelo = ?");
        $query->execute([$value]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllByPagination($offset, $limit){
        $query = $this->db->prepare('SELECT autos.*, categorias.nombre FROM autos JOIN categorias ON autos.id_categorias = categorias.id_categorias LIMIT :paginas, :limite');

        $query->bindParam(':paginas', $offset, PDO::PARAM_INT);
        $query->bindParam(':limite', $limit, PDO::PARAM_INT);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}