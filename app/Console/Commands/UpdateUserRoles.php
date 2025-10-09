<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los roles de "user" a "client"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando roles de usuarios...');
        
        $updated = User::where('role', 'user')->update(['role' => 'client']);
        
        $this->info("Se actualizaron {$updated} usuarios de 'user' a 'client'");
        
        // Mostrar resumen de roles
        $this->info("\nResumen de roles:");
        $this->table(
            ['Rol', 'Cantidad'],
            [
                ['admin', User::where('role', 'admin')->count()],
                ['nutritionist', User::where('role', 'nutritionist')->count()],
                ['client', User::where('role', 'client')->count()],
            ]
        );
        
        return Command::SUCCESS;
    }
}
