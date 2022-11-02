<?php
require_once './app/models/autos-model.php';
require_once './app/views/motor-api.view.php';

class AutosApiController {
    private $model;
    private $view;

    private $data;

    function __construct(){
        $this->model = new AutosModel();
        $this->view = new MotorApiView();

        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getAutos($params = null){
        $autos = $this->model->getAll();
        $this->view->response($autos, 200);
    }

    public function getAutoById($params = null){
        $id = $params[":ID"];
        $autos = $this->model->get($id);

        if($autos){
            $this->view->response($autos, 200);
        } else {
            $this->view->response("El Auto con el id=$id no existe", 404);
        }
    }

    public function deleteAuto($params = null){
        $id = $params[":ID"];

        $autos = $this->model->get($id);

        if($autos){
            $this->model->delete($id);
            $this->view->response($autos, 200);
        } else {
            $this->view->response("El Auto con el id=$id no se existe", 404);
        }
    }

    public function insertAuto($params = null){
        $autos = $this->getData();

        if(empty($autos->nombres) || empty($autos->descripcion) || empty($autos->modelo) || empty($autos->marca) || empty($autos->id_categorias)){
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->add($autos->nombres, $autos->descripcion, $autos->modelo, $autos->marca, $autos->id_categorias);
            $autos = $this->model->get($id);
            $this->view->response($autos, 201);
        }
    }

    public function updateAuto($params = null){
        $autos = $this->model->getById($id);
        $id = $params[':ID'];
        
        if(empty($autos->nombres) || empty($autos->descripcion) || empty($autos->modelo) || empty($autos->marca) || empty($autos->id_categorias)){
            $this->view->response("El Auto con el id=$id no se existe", 404);
        } else {
            $auto = $this->getData();
            $autoUpdate = $this->model->update($id, $auto->nombres, $auto->descripcion, $auto->modelo, $auto->marca, $auto->id_categorias);
            $this->view->response($autoUpdate, 201);
        }
    }
}