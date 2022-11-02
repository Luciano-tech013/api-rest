<?php
require_once './app/models/categorias-model.php';
require_once './app/views/motor-api.view.php';

class CategoriasApiController {
    private $model;
    private $view;

    private $data;

    function __construct(){
        $this->model = new CategoriasModel();
        $this->view = new MotorApiView();

        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getCategorias($params = null){
        $categorias = $this->model->get();
        $this->view->response($categorias, 200);
    }

    public function getCategoriaById($params = null){
        $id = $params[":ID"];
        $categorias = $this->model->getById($id);

        if($categorias){
            $this->view->response($categorias, 200);
        } else {
            $this->view->response("La Categoria con el id=$id no existe", 404);
        }
    }

    public function deleteCategoria($params = null){
        $id = $params[":ID"];

        $categorias = $this->model->get($id);

        if($categorias){
            $this->model->delete($id);
            $this->view->response($categorias, 200);
        } else {
            $this->view->response("La Categoria con el id=$id no se existe", 404);
        }
    }

    public function insertCategoria($params = null){
        $categorias = $this->getData();

        if(empty($categorias->nombre) || empty($categorias->descripcion) || empty($categorias->tipo)){
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->add($categorias->nombre, $categorias->descripcion, $categorias->tipo);
            $categorias = $this->model->get($id);
            $this->view->response($categorias, 201);
        }
    }

    public function updateCategoria($params = null){
        $categorias = $this->model->getById($id);
        $id = $params[':ID'];
        
        if(empty($categorias->nombre) || empty($categorias->descripcion) || empty($categorias->tipo)){
            $this->view->response("La Categoria con el id=$id no se existe", 404);
        } else {
            $categoria = $this->getData();
            $categoriaUpdate = $this->model->update($id, $categoria->nombre, $categoria->descripcion, $categoria->tipo);
            $this->view->response($categoriaUpdate, 201);
        }
    }
}