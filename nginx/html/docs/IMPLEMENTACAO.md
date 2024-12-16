# Guia de Implementação - Sistema de Custos Extras

## 1. Requisitos do Sistema

### 1.1 Ambiente de Desenvolvimento
- PHP 8.1 ou superior
- Composer 2.x
- Node.js 18.x ou superior
- MySQL 8.0
- Redis 6.x
- Git

### 1.2 Extensões PHP Necessárias
- php-mbstring
- php-xml
- php-curl
- php-mysql
- php-redis
- php-gd
- php-zip

## 2. Instalação e Configuração

### 2.1 Instalação do Laravel
```bash
composer create-project laravel/laravel custos-extras
cd custos-extras
```

### 2.2 Pacotes Principais
```bash
# Pacotes Base
composer require filament/filament:"^3.0"
composer require livewire/livewire:"^3.0"
composer require spatie/laravel-permission
composer require yajra/laravel-datatables
composer require intervention/image
composer require maatwebsite/excel
composer require barryvdh/laravel-debugbar --dev

# Assets e UI
npm install -D tailwindcss @tailwindcss/forms @tailwindcss/typography
npm install alpinejs
```

### 2.3 Configuração do Ambiente
```env
APP_NAME="Sistema de Custos Extras"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=custos_extras
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

## 3. Estrutura do Projeto

### 3.1 Diretórios Principais
```
/app
  /Filament           # Recursos do Filament (Admin)
  /Http
    /Controllers      # Controllers da aplicação
    /Livewire        # Componentes Livewire
  /Models            # Models do Eloquent
  /Services          # Lógica de negócios
  /Policies          # Políticas de autorização
/resources
  /views
    /components      # Componentes Blade
    /layouts         # Layouts base
    /livewire        # Views Livewire
/database
  /migrations        # Migrações do banco
  /seeders          # Seeders para dados iniciais
```

## 4. Desenvolvimento

### 4.1 Padrões de Código
- PSR-12 para estilo de código
- Tipagem estrita em PHP
- Documentação PHPDoc
- Testes unitários e de feature

### 4.2 Convenções de Nomenclatura
```php
// Models (singular)
class User extends Model {}

// Controllers (plural)
class UsersController extends Controller {}

// Migrations
create_users_table
add_role_to_users_table

// Livewire Components
class UserTable extends Component {}
```

### 4.3 Exemplo de CRUD Completo

#### Model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Custo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'status',
        'usuario_id'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'datetime',
    ];
}
```

#### Filament Resource
```php
namespace App\Filament\Resources;

use App\Filament\Resources\CustoResource\Pages;
use Filament\Forms;
use Filament\Tables;

class CustoResource extends Resource
{
    protected static ?string $model = Custo::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('valor')
                    ->required()
                    ->numeric()
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->money(prefix: 'R$ ', thousandsSeparator: '.', decimalPlaces: 2)
                    ),
                Forms\Components\DatePicker::make('data')
                    ->required(),
            ]);
    }
}
```

### 4.4 Validação e Autorização
```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Custo::class);
    }

    public function rules(): array
    {
        return [
            'descricao' => ['required', 'string', 'max:255'],
            'valor' => ['required', 'numeric', 'min:0'],
            'data' => ['required', 'date'],
        ];
    }
}
```

## 5. Deploy e Manutenção

### 5.1 Checklist de Deploy
- [ ] Configurar variáveis de ambiente
- [ ] Otimizar autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Compilar assets: `npm run build`
- [ ] Cachear configurações: `php artisan config:cache`
- [ ] Cachear rotas: `php artisan route:cache`
- [ ] Cachear views: `php artisan view:cache`
- [ ] Configurar supervisor para filas
- [ ] Configurar backup automático

### 5.2 Monitoramento
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'],
    ],
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Sistema de Custos',
        'emoji' => ':boom:',
        'level' => 'critical',
    ],
]
```

### 5.3 Backup
```php
// config/backup.php
return [
    'backup' => [
        'name' => 'custos_extras_backup',
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
    ],
];
```

## 6. Boas Práticas

### 6.1 Performance
- Usar cache quando possível
- Eager loading de relacionamentos
- Índices no banco de dados
- Paginação em listagens
- Compressão de assets

### 6.2 Segurança
- Validação de entrada
- Sanitização de saída
- CSRF protection
- Rate limiting
- Autenticação em dois fatores

### 6.3 Manutenibilidade
- Documentação atualizada
- Testes automatizados
- Code review
- Versionamento semântico
- Logs detalhados

## 7. Recursos Adicionais

### 7.1 Documentação Oficial
- [Laravel](https://laravel.com/docs)
- [Filament](https://filamentphp.com/docs)
- [Livewire](https://livewire.laravel.com/docs)

### 7.2 Ferramentas Recomendadas
- PHPStorm ou VSCode
- TablePlus para banco de dados
- Postman para testes de API
- Ray para debugging
- Laravel Telescope

## 8. Troubleshooting

### 8.1 Problemas Comuns
1. Permissões de arquivo
2. Cache desatualizado
3. Composer autoload
4. Configurações de ambiente

### 8.2 Comandos Úteis
```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Verificar status
php artisan about

# Diagnosticar problemas
php artisan diagnose
```
