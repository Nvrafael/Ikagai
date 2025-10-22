<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar todos los usuarios del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all(['id', 'name', 'email', 'role', 'created_at']);

        if ($users->isEmpty()) {
            $this->error('❌ No hay usuarios en el sistema.');
            $this->newLine();
            $this->info('💡 Ejecuta: php artisan db:seed --class=RoleUsersSeeder');
            return;
        }

        $this->info('📊 Total de usuarios: ' . $users->count());
        $this->newLine();

        $tableData = $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Nombre' => $user->name,
                'Email' => $user->email,
                'Rol' => $this->getRoleLabel($user->role),
                'Creado' => $user->created_at->format('d/m/Y H:i'),
            ];
        })->toArray();

        $this->table(
            ['ID', 'Nombre', 'Email', 'Rol', 'Creado'],
            $tableData
        );

        $this->newLine();
        $this->info('🔑 Credenciales de prueba (password: password):');
        $this->line('   • Admin: admin@ikagai.com');
        $this->line('   • Nutricionista: nutritionist@ikagai.com');
        $this->line('   • Cliente: client@ikagai.com');
    }

    private function getRoleLabel($role)
    {
        return match($role) {
            'admin' => '🔧 Admin',
            'nutritionist' => '👨‍⚕️ Nutricionista',
            'client' => '👤 Cliente',
            default => $role,
        };
    }
}

