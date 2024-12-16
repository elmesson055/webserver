<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Embarcador;

class EmbarcadorController extends Controller {
    public function __construct() {
        $this->model = new Embarcador();
        $this->viewPath = 'embarcadores';
    }

    protected function validateStore($data) {
        return $this->model->validateCreate($data);
    }

    protected function validateUpdate($data, $id) {
        return $this->model->validateCreate($data);
    }

    public function search() {
        $term = $_GET['term'] ?? '';
        $page = $_GET['page'] ?? 1;
        
        $result = $this->model->search(
            $term,
            $this->model->getSearchFields(),
            $page,
            $this->itemsPerPage
        );

        if ($this->isAjax()) {
            return $this->json($result);
        }

        return $this->view($this->viewPath . '/index', [
            'items' => $result['data'],
            'pagination' => [
                'total' => $result['total'],
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page']
            ],
            'search' => $term
        ]);
    }
}
