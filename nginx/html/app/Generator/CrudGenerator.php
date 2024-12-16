<?php
namespace App\Generator;

class CrudGenerator {
    private $table;
    private $fields;
    private $primaryKey;
    private $modelNamespace = 'App\\Models\\';
    private $viewPath = __DIR__ . '/../../public/views/';
    private $modelPath = __DIR__ . '/../Models/';
    
    public function __construct(string $table, array $fields, string $primaryKey = 'id') {
        $this->table = $table;
        $this->fields = $fields;
        $this->primaryKey = $primaryKey;
    }
    
    public function generate() {
        $this->createModel();
        $this->createViews();
        $this->createController();
    }
    
    private function createModel() {
        $modelName = $this->getModelName();
        $content = $this->getModelTemplate($modelName);
        
        if (!file_exists($this->modelPath)) {
            mkdir($this->modelPath, 0777, true);
        }
        
        file_put_contents($this->modelPath . $modelName . '.php', $content);
    }
    
    private function createViews() {
        $viewFolder = $this->viewPath . strtolower($this->table);
        
        if (!file_exists($viewFolder)) {
            mkdir($viewFolder, 0777, true);
        }
        
        // Create index view
        $indexContent = $this->getIndexTemplate();
        file_put_contents($viewFolder . '/index.php', $indexContent);
        
        // Create form view
        $formContent = $this->getFormTemplate();
        file_put_contents($viewFolder . '/form.php', $formContent);
        
        // Create show view
        $showContent = $this->getShowTemplate();
        file_put_contents($viewFolder . '/show.php', $showContent);
    }
    
    private function createController() {
        $controllerName = $this->getControllerName();
        $content = $this->getControllerTemplate($controllerName);
        
        $controllerPath = __DIR__ . '/../Controllers/';
        if (!file_exists($controllerPath)) {
            mkdir($controllerPath, 0777, true);
        }
        
        file_put_contents($controllerPath . $controllerName . '.php', $content);
    }
    
    private function getModelName() {
        return ucfirst(substr($this->table, 0, -1)); // Remove 's' from table name
    }
    
    private function getControllerName() {
        return ucfirst($this->table) . 'Controller';
    }
    
    private function getModelTemplate($modelName) {
        return "<?php
namespace {$this->modelNamespace};

use App\Core\Model;

class {$modelName} extends Model {
    protected \$table = '{$this->table}';
    protected \$primaryKey = '{$this->primaryKey}';
    protected \$fillable = " . var_export($this->fields, true) . ";
}";
    }
    
    private function getIndexTemplate() {
        $fields = implode(', ', array_map(function($field) {
            return "'{$field}'";
        }, $this->fields));
        
        return "<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class='container mx-auto px-4 py-8'>
    <div class='flex justify-between items-center mb-6'>
        <h1 class='text-2xl font-bold text-gray-800'>" . ucfirst($this->table) . "</h1>
        <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "/create' 
           class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>
            Novo Registro
        </a>
    </div>

    <div class='bg-white shadow-md rounded my-6'>
        <table class='min-w-full leading-normal'>
            <thead>
                <tr>
                    <?php foreach ([{$fields}] as \$field): ?>
                        <th class='px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>
                            <?= ucfirst(\$field) ?>
                        </th>
                    <?php endforeach; ?>
                    <th class='px-5 py-3 border-b-2 border-gray-200 bg-gray-100'></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (\$items as \$item): ?>
                    <tr>
                        <?php foreach ([{$fields}] as \$field): ?>
                            <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>
                                <?= \$item[\$field] ?>
                            </td>
                        <?php endforeach; ?>
                        <td class='px-5 py-5 border-b border-gray-200 bg-white text-sm text-right'>
                            <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "/edit/<?= \$item['{$this->primaryKey}'] ?>'
                               class='text-blue-600 hover:text-blue-900 mr-4'>
                                Editar
                            </a>
                            <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "/delete/<?= \$item['{$this->primaryKey}'] ?>'
                               class='text-red-600 hover:text-red-900'
                               onclick='return confirm(\"Tem certeza que deseja excluir?\");'>
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>";
    }
    
    private function getFormTemplate() {
        $formFields = '';
        foreach ($this->fields as $field) {
            if ($field !== $this->primaryKey) {
                $formFields .= "
    <div class='mb-4'>
        <label class='block text-gray-700 text-sm font-bold mb-2' for='{$field}'>
            " . ucfirst($field) . "
        </label>
        <input type='text' name='{$field}' id='{$field}'
               value='<?= \$item['{$field}'] ?? '' ?>'
               class='shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'>
    </div>";
            }
        }
        
        return "<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class='container mx-auto px-4 py-8'>
    <div class='max-w-lg mx-auto'>
        <h1 class='text-2xl font-bold text-gray-800 mb-6'>
            <?= isset(\$item) ? 'Editar' : 'Novo' ?> " . ucfirst(substr($this->table, 0, -1)) . "
        </h1>

        <form action='<?= baseUrl() ?>/" . strtolower($this->table) . "/<?= isset(\$item) ? 'update/' . \$item['{$this->primaryKey}'] : 'store' ?>'
              method='POST'
              class='bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4'>
            
            {$formFields}
            
            <div class='flex items-center justify-between'>
                <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                        type='submit'>
                    Salvar
                </button>
                <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "'
                   class='text-blue-500 hover:text-blue-800'>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>";
    }
    
    private function getShowTemplate() {
        $fields = implode(', ', array_map(function($field) {
            return "'{$field}'";
        }, $this->fields));
        
        return "<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class='container mx-auto px-4 py-8'>
    <div class='max-w-lg mx-auto'>
        <h1 class='text-2xl font-bold text-gray-800 mb-6'>
            Detalhes do " . ucfirst(substr($this->table, 0, -1)) . "
        </h1>

        <div class='bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4'>
            <?php foreach ([{$fields}] as \$field): ?>
                <div class='mb-4'>
                    <label class='block text-gray-700 text-sm font-bold mb-2'>
                        <?= ucfirst(\$field) ?>
                    </label>
                    <p class='text-gray-700'>
                        <?= \$item[\$field] ?>
                    </p>
                </div>
            <?php endforeach; ?>

            <div class='flex items-center justify-between mt-6'>
                <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "/edit/<?= \$item['{$this->primaryKey}'] ?>'
                   class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'>
                    Editar
                </a>
                <a href='<?= baseUrl() ?>/" . strtolower($this->table) . "'
                   class='text-blue-500 hover:text-blue-800'>
                    Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>";
    }
    
    private function getControllerTemplate($controllerName) {
        $modelName = $this->getModelName();
        return "<?php
namespace App\Controllers;

use App\Core\Controller;
use {$this->modelNamespace}{$modelName};

class {$controllerName} extends Controller {
    private \$model;
    
    public function __construct() {
        parent::__construct();
        \$this->model = new {$modelName}();
    }
    
    public function index() {
        \$items = \$this->model->all();
        \$this->view('" . strtolower($this->table) . "/index', ['items' => \$items]);
    }
    
    public function create() {
        \$this->view('" . strtolower($this->table) . "/form');
    }
    
    public function store() {
        \$data = \$this->filterData(\$_POST);
        \$id = \$this->model->create(\$data);
        
        if (\$id) {
            \$this->redirect('" . strtolower($this->table) . "');
        }
    }
    
    public function edit(\$id) {
        \$item = \$this->model->find(\$id);
        if (!\$item) {
            \$this->redirect('" . strtolower($this->table) . "');
        }
        
        \$this->view('" . strtolower($this->table) . "/form', ['item' => \$item]);
    }
    
    public function update(\$id) {
        \$data = \$this->filterData(\$_POST);
        \$success = \$this->model->update(\$id, \$data);
        
        if (\$success) {
            \$this->redirect('" . strtolower($this->table) . "');
        }
    }
    
    public function delete(\$id) {
        \$this->model->delete(\$id);
        \$this->redirect('" . strtolower($this->table) . "');
    }
    
    private function filterData(\$data) {
        \$fillable = " . var_export($this->fields, true) . ";
        return array_intersect_key(\$data, array_flip(\$fillable));
    }
}";
    }
}
