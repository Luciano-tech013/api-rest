<?php

class UsuariosModel {

    public function get($user){
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $query->execute([$user]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}