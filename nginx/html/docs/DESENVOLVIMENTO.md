# Guia de Desenvolvimento - Sistema de Custos Extras

## 1. Padrões de Desenvolvimento

### 1.1 Arquitetura
- MVC com Service Layer
- Repository Pattern para acesso a dados
- Form Requests para validação
- Policies para autorização
- Events e Listeners para ações assíncronas

### 1.2 Organização de Código
```
/app
  /Services        # Lógica de negócios
  /Repositories    # Acesso a dados
  /Events         # Eventos do sistema
  /Listeners      # Handlers de eventos
  /Exceptions     # Exceções personalizadas
  /Support        # Classes auxiliares
```

## 2. Componentes Principais

### 2.1 Filament Admin
- Painéis administrativos
- Gerenciamento de recursos
- Widgets e dashboards
- Relatórios e gráficos

### 2.2 Livewire
- Componentes dinâmicos
- Atualizações em tempo real
- Validação instantânea
- Upload de arquivos

### 2.3 Spatie Permissions
- Controle de acesso
- Papéis e permissões
- Cache de permissões
- Middleware de autorização

## 3. Exemplos de Implementação

### 3.1 Service Layer
```php
namespace App\Services;

class CustoService
{
    public function __construct(
        private readonly CustoRepository $repository
    ) {}

    public function create(array $data): Custo
    {
        // Validação de negócios
        $this->validateBusinessRules($data);

        // Processamento
        $custo = $this->repository->create($data);

        // Eventos
        event(new CustoCriado($custo));

        return $custo;
    }

    private function validateBusinessRules(array $data): void
    {
        // Regras específicas do negócio
        if ($data['valor'] > 10000) {
            throw new ValorExcedidoException();
        }
    }
}
```

### 3.2 Repository Pattern
```php
namespace App\Repositories;

class CustoRepository
{
    public function __construct(
        private readonly Custo $model
    ) {}

    public function create(array $data): Custo
    {
        return $this->model->create($data);
    }

    public function findByPeriodo(Carbon $inicio, Carbon $fim)
    {
        return $this->model
            ->whereBetween('data', [$inicio, $fim])
            ->with(['usuario', 'categoria'])
            ->get();
    }
}
```

### 3.3 Filament Resource
```php
namespace App\Filament\Resources;

class CustoResource extends Resource
{
    public static function getWidgets(): array
    {
        return [
            CustoStats::class,
            CustoChart::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::pendentes()->count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['descricao', 'valor', 'usuario.nome'];
    }
}
```

### 3.4 Livewire Component
```php
namespace App\Http\Livewire;

class CustoForm extends Component
{
    public Custo $custo;
    public $categorias = [];

    protected $rules = [
        'custo.descricao' => 'required|min:3',
        'custo.valor' => 'required|numeric|min:0',
        'custo.categoria_id' => 'required|exists:categorias,id'
    ];

    public function mount()
    {
        $this->categorias = Categoria::all();
    }

    public function save()
    {
        $this->validate();
        $this->custo->save();
        
        $this->notify('Custo salvo com sucesso!');
    }
}
```

## 4. Testes

### 4.1 Testes Unitários
```php
namespace Tests\Unit;

class CustoTest extends TestCase
{
    /** @test */
    public function it_calculates_total_with_tax()
    {
        $custo = Custo::factory()->make([
            'valor' => 100.00
        ]);

        $this->assertEquals(110.00, $custo->valorComImposto);
    }
}
```

### 4.2 Testes de Feature
```php
namespace Tests\Feature;

class CustoManagementTest extends TestCase
{
    /** @test */
    public function authorized_users_can_create_custos()
    {
        $this->actingAs($user = User::factory()->create());
        $user->givePermissionTo('create custos');

        $response = $this->post('/custos', [
            'descricao' => 'Teste',
            'valor' => 100.00
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('custos', [
            'descricao' => 'Teste'
        ]);
    }
}
```

## 5. Frontend

### 5.1 Blade Components
```php
// resources/views/components/custo-card.blade.php
@props(['custo'])

<div class="card">
    <div class="card-body">
        <h5>{{ $custo->descricao }}</h5>
        <p>R$ {{ number_format($custo->valor, 2, ',', '.') }}</p>
        
        {{ $slot }}
    </div>
</div>
```

### 5.2 Alpine.js Integration
```html
<div x-data="{ open: false }">
    <button @click="open = true">Detalhes</button>
    
    <div x-show="open" 
         x-transition
         class="modal">
        <!-- Conteúdo -->
    </div>
</div>
```

## 6. API

### 6.1 API Resources
```php
namespace App\Http\Resources;

class CustoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'valor' => $this->valor,
            'data' => $this->data->format('Y-m-d'),
            'usuario' => new UserResource($this->whenLoaded('usuario')),
            'links' => [
                'self' => route('api.custos.show', $this->id),
                'edit' => route('api.custos.edit', $this->id),
            ]
        ];
    }
}
```

### 6.2 API Controllers
```php
namespace App\Http\Controllers\Api;

class CustoController extends Controller
{
    public function index(): CustoCollection
    {
        $custos = Custo::with(['usuario', 'categoria'])
            ->latest()
            ->paginate();

        return new CustoCollection($custos);
    }

    public function store(StoreCustoRequest $request): CustoResource
    {
        $custo = $this->custoService->create($request->validated());

        return new CustoResource($custo);
    }
}
```

## 7. Segurança

### 7.1 Middleware
```php
namespace App\Http\Middleware;

class VerifyCustoAccess
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->can('view', $request->custo)) {
            abort(403);
        }

        return $next($request);
    }
}
```

### 7.2 Policies
```php
namespace App\Policies;

class CustoPolicy
{
    public function view(User $user, Custo $custo): bool
    {
        return $user->hasRole('admin') || 
               $custo->usuario_id === $user->id;
    }

    public function update(User $user, Custo $custo): bool
    {
        return $user->hasRole('admin') || 
               ($custo->usuario_id === $user->id && 
                $custo->created_at->diffInHours() < 24);
    }
}
```

## 8. Performance

### 8.1 Cache
```php
namespace App\Services;

class DashboardService
{
    public function getStats(): array
    {
        return Cache::remember('dashboard.stats', now()->addHour(), function () {
            return [
                'total_custos' => Custo::sum('valor'),
                'count_pendentes' => Custo::pendentes()->count(),
                'media_mensal' => Custo::whereMonth('data', now())
                    ->avg('valor')
            ];
        });
    }
}
```

### 8.2 Query Optimization
```php
// Eager Loading
$custos = Custo::with(['usuario', 'categoria'])
    ->whereHas('usuario', function ($query) {
        $query->where('ativo', true);
    })
    ->latest()
    ->paginate();

// Chunk Processing
Custo::chunk(100, function ($custos) {
    foreach ($custos as $custo) {
        // Process each custo
    }
});
```

## 9. Manutenção

### 9.1 Logging
```php
namespace App\Services;

class AuditoriaService
{
    public function registrarAcao(string $acao, $modelo): void
    {
        Log::channel('auditoria')->info($acao, [
            'modelo' => get_class($modelo),
            'id' => $modelo->id,
            'usuario' => auth()->id(),
            'ip' => request()->ip()
        ]);
    }
}
```

### 9.2 Backups
```php
// config/backup.php
return [
    'backup' => [
        'name' => env('APP_NAME', 'laravel-backup'),
        'source' => [
            'files' => [
                'include' => [
                    base_path(),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],
            ],
            'databases' => [
                'mysql',
            ],
        ],
        'destination' => [
            'disks' => ['s3'],
        ],
    ],
];
```
