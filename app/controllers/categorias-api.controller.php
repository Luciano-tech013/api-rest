<?php
require_once './app/models/categorias-model.php';
require_once './app/views/motor-api.view.php';

class CategoriasApiController {
    private $model;
    private $view;

    private $data;

    function __construct(){
        $this->model = new CategoriaModel();
        $this->view = new MotorApiView();

        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getCategorias($params = null){
        if(isset($_GET['sort']) || !empty($_GET['sort']) && isset($_GET['order']) || !empty($_GET['order']))
        {
            $sort = $_GET['sort'];
            $order = $_GET['order'];

            if($this->verifyField($sort) && ($order == 'asc' && $order == 'desc')) {
                $categorias = $this->model->order($sort, $order);
                if($categorias){
                    $this->view->response($categorias, 200);
                } else {
                    $this->view->response("No se puede ordenar", 400);
                }
            } else{
                $this->view->response("No se puede ordenar", 400);
            }
        } 
        
        else if (isset($_GET['value']) || !empty($_GET['value']))
        {
            $value = $_GET['value'];

            if($this->verifyValue($value)){
                $categorias = $this->model->filter($value);
                if($categorias){
                    $this->view->response($categorias, 200);
                } else {
                    $this->view->response("No se puede filtrar", 400);
                }
            } else {
                $this->view->response("El valor ingresado no existe", 400);
            }

        } else {
            $categorias = $this->model->getAll();
            if($categorias){
                $this->view->response($categorias, 200);
            } else {
                $this->view->response("No se pudo encontrar el recurso solicitado", 400);            }
        }
    }
    
    public function getCategoriaById($params = null){
        $id = $params[":ID"];
        $categorias = $this->model->get($id);

        if($categorias){
            $this->view->response($categorias, 200);
        } else {
            $this->view->response("La Categoria con el id $id no existe", 404);
        }
    }

    public function deleteCategoria($params = null){
        $id = $params[":ID"];
        $categorias = $this->model->get($id);

        if($categorias){
            $this->model->delete($id);
            $this->view->response($categorias, 200);
        } else {
            $this->view->response("La Categoria con el id $id no existe", 404);
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
        $id = $params[':ID'];
        $categorias = $this->model->get($id);

        if(empty($categorias->nombre) || empty($categorias->descripcion) || empty($categorias->tipo)){
            $this->view->response("La Categoria con el id $id no existe", 404);
        } else {
            $categoria = $this->getData();
            $this->model->update($id, $categoria->nombre, $categoria->descripcion, $categoria->tipo);
            $categoriaUpdate = $this->model->get($id);
            $this->view->response($categoriaUpdate, 201);
        }
    }

    private function verifyField($sort){
        $whiteList = array(
            0 => "id_categorias", 
            1 => "nombre", 
            2 => "descripcion",
            3 => "tipo"
        );

        return in_array($sort, $whiteList);
    }

    private function verifyValue($value){
        $whiteList = array(
            0 => "NACIONAL",
            1 => "INTERNACIONAL",
            2 => "CONTINENTAL",
            3 => "PROVINCIAL",
            4 => "ZONAL"
        );

        return in_array($value, $whiteList);
    }
}