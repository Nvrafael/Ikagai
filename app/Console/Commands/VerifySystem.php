<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class VerifySystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar que el sistema esté correctamente configurado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 VERIFICACIÓN DEL SISTEMA IKAGAI');
        $this->newLine();

        $allOk = true;

        // 1. Verificar conexión a base de datos
        $this->info('1️⃣  Verificando conexión a base de datos...');
        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            $this->line("   ✅ Conectado a: {$dbName}");
        } catch (\Exception $e) {
            $this->error("   ❌ Error de conexión: " . $e->getMessage());
            $allOk = false;
        }
        $this->newLine();

        // 2. Verificar usuarios
        $this->info('2️⃣  Verificando usuarios de prueba...');
        $emails = [
            'admin@ikagai.com' => 'admin',
            'nutritionist@ikagai.com' => 'nutritionist',
            'client@ikagai.com' => 'client'
        ];

        foreach ($emails as $email => $expectedRole) {
            $user = User::where('email', $email)->first();
            if ($user) {
                if ($user->role === $expectedRole) {
                    $this->line("   ✅ {$email} ({$expectedRole})");
                    
                    // Verificar contraseña
                    if (Hash::check('password', $user->password)) {
                        $this->line("      🔑 Contraseña verificada");
                    } else {
                        $this->error("      ❌ Contraseña incorrecta");
                        $allOk = false;
                    }
                } else {
                    $this->error("   ❌ {$email} - Rol incorrecto: {$user->role} (esperado: {$expectedRole})");
                    $allOk = false;
                }
            } else {
                $this->error("   ❌ Usuario no encontrado: {$email}");
                $allOk = false;
            }
        }
        $this->newLine();

        // 3. Verificar rutas de autenticación
        $this->info('3️⃣  Verificando rutas de autenticación...');
        $routes = ['login', 'logout', 'dashboard'];
        foreach ($routes as $routeName) {
            if (Route::has($routeName)) {
                $this->line("   ✅ Ruta '{$routeName}' registrada");
            } else {
                $this->error("   ❌ Ruta '{$routeName}' no encontrada");
                $allOk = false;
            }
        }
        $this->newLine();

        // 4. Verificar tablas de base de datos
        $this->info('4️⃣  Verificando tablas de base de datos...');
        $tables = [
            'users', 'categories', 'products', 'reviews', 
            'services', 'bookings', 'orders', 'order_items',
            'nutritional_plans', 'messages'
        ];
        
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ Tabla '{$table}' existe ({$count} registros)");
            } else {
                $this->error("   ❌ Tabla '{$table}' no existe");
                $allOk = false;
            }
        }
        $this->newLine();

        // 5. Verificar archivos de configuración
        $this->info('5️⃣  Verificando archivos de configuración...');
        
        if (env('APP_KEY')) {
            $this->line("   ✅ APP_KEY configurada");
        } else {
            $this->error("   ❌ APP_KEY no configurada - Ejecuta: php artisan key:generate");
            $allOk = false;
        }

        if (env('DB_DATABASE')) {
            $this->line("   ✅ DB_DATABASE configurada: " . env('DB_DATABASE'));
        } else {
            $this->error("   ❌ DB_DATABASE no configurada");
            $allOk = false;
        }
        $this->newLine();

        // Resumen final
        if ($allOk) {
            $this->info('✨ ¡TODO ESTÁ CORRECTO! El sistema está listo para usar.');
            $this->newLine();
            $this->info('🔐 Credenciales de acceso:');
            $this->table(
                ['Rol', 'Email', 'Password'],
                [
                    ['Admin', 'admin@ikagai.com', 'password'],
                    ['Nutricionista', 'nutritionist@ikagai.com', 'password'],
                    ['Cliente', 'client@ikagai.com', 'password'],
                ]
            );
            $this->newLine();
            $this->info('🌐 Accede a tu aplicación y prueba el login!');
            return 0;
        } else {
            $this->error('⚠️  Se encontraron problemas. Revisa los mensajes anteriores.');
            $this->newLine();
            $this->info('💡 Comandos útiles:');
            $this->line('   • php artisan migrate - Ejecutar migraciones');
            $this->line('   • php artisan db:seed --class=RoleUsersSeeder - Crear usuarios');
            $this->line('   • php artisan optimize:clear - Limpiar caches');
            $this->line('   • php artisan key:generate - Generar APP_KEY');
            return 1;
        }
    }
}

