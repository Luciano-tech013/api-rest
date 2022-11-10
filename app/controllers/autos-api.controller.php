<?php
require_once './app/models/autos-model.php';
require_once './app/views/motor-api.view.php';

class AutosApiController {
    private $model;
    private $view;

    private $data;

    function __construct(){
        $this->model = new AutoModel();
        $this->view = new MotorApiView();

        $this->data = file_get_contents("php://input");
    }

    public function getData(){
        return json_decode($this->data);
    }

    public function getAutos(){
        if(isset($_GET['sort']) || !empty($_GET['sort']) && isset($_GET['order']) || !empty($_GET['order']))
        {
            $sort = $_GET['sort'];
            $order = $_GET['order'];

            if($order != 'asc' && $order != 'desc'){
                $this->view->response(""); //que retorno??
            }
            
            if($this->verifyField($sort)) {
                $autos = $this->model->order($sort, $order);
                if($autos){
                    $this->view->response($autos, 200);
                } else {
                    $this->view->response("No se puede ordenar", 400);
                }
            } else{
                $this->view->response("La columna ingresada no existe", 400);
            }
        } 
        
        else if (isset($_GET['value']) || !empty($_GET['value']))
        {
            $value = $_GET['value'];

            if($this->verifyValue($value)){
                $autos = $this->model->filter($value);
                if($autos){
                    $this->view->response($autos, 200);
                } else {
                    $this->view->response("No se puede filtrar", 400);
                }
            } else {
                $this->view->response("El valor ingresado no existe", 400);
            }

        } else {
            $autos = $this->model->getAll();
            if($autos){
                $this->view->response($autos, 200);
            } else {
                $this->view->response("No se pudo encontrar el recurso solicitado", 400);            }
        }
    }

    public function getAutoById($params = null){
        $id = $params[":ID"];
        $autos = $this->model->get($id);

        if($autos){
            $this->view->response($autos, 200);
        } else {
            $this->view->response("El Auto con el id $id no existe", 404);
        }
    }

    public function deleteAuto($params = null){
        /**$this->helper->isLoggedIn();*/
        $id = $params[":ID"];
        $autos = $this->model->get($id);

        if($autos){
            $this->model->delete($id);
            $this->view->response($autos, 200);
        } else {
            $this->view->response("El Auto con el id $id no se existe", 404);
        }
    }

    public function insertAuto($params = null){
        /**$this->helper->isLoggedIn();*/
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
        /**$this->helper->isLoggedIn();*/
        $id = $params[':ID'];
        $autos = $this->model->get($id);

        if(empty($autos->nombres) || empty($autos->descripcion) || empty($autos->modelo) || empty($autos->marca) || empty($autos->id_categorias)){
            $this->view->response("El Auto con el id $id no existe", 404);
        } else {
            $auto = $this->getData();
            $this->model->update($id, $auto->nombres, $auto->descripcion, $auto->modelo, $auto->marca, $auto->id_categorias);
            $autoUpdate = $this->model->get($id);
            $this->view->response($autoUpdate, 201);
        }
    }

    private function verifyField($sort){
        $whiteList = array(
            0 => "id", 
            1 => "nombres", 
            2 => "descripcion",
            3 => "modelo",
            4 => "marca",
            5 => "id_categorias"
        );

        return in_array($sort, $whiteList);
    }

    private function verifyValue($value){
        $whiteList = array(
            0 => "GT3",
            1 => "GTE",
            2 => "Hypercar",
            3 => "Monoplaza",
            4 => "Turismo",
            5 => "LPM1"
        );

        return in_array($value, $whiteList);
    }
}