<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;

class ClientsController extends Controller {
    private $model;
    
    public function __construct() {
        parent::__construct();
        $this->model = new Client();
    }
    
    public function index() {
        $items = $this->model->all();
        $this->view('clients/index', ['items' => $items]);
    }
    
    public function create() {
        $this->view('clients/form');
    }
    
    public function store() {
        $data = $this->filterData($_POST);
        $id = $this->model->create($data);
        
        if ($id) {
            $this->redirect('clients');
        }
    }
    
    public function edit($id) {
        $item = $this->model->find($id);
        if (!$item) {
            $this->redirect('clients');
        }
        
        $this->view('clients/form', ['item' => $item]);
    }
    
    public function update($id) {
        $data = $this->filterData($_POST);
        $success = $this->model->update($id, $data);
        
        if ($success) {
            $this->redirect('clients');
        }
    }
    
    public function delete($id) {
        $this->model->delete($id);
        $this->redirect('clients');
    }
    
    private function filterData($data) {
        $fillable = array (
  0 => 'id',
  1 => 'name',
  2 => 'email',
  3 => 'phone',
  4 => 'address',
  5 => 'city',
  6 => 'state',
  7 => 'created_at',
  8 => 'updated_at',
);
        return array_intersect_key($data, array_flip($fillable));
    }
}