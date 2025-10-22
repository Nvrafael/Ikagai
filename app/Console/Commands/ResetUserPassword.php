<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-password {email} {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetear la contraseña de un usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuario no encontrado: {$email}");
            $this->newLine();
            $this->info("💡 Usuarios disponibles:");
            $this->call('users:list');
            return 1;
        }

        $this->info("🔍 Usuario encontrado:");
        $this->line("   • Nombre: {$user->name}");
        $this->line("   • Email: {$user->email}");
        $this->line("   • Rol: {$user->role}");
        $this->newLine();

        if ($this->confirm("¿Resetear contraseña a '{$password}'?", true)) {
            $user->password = Hash::make($password);
            $user->save();

            $this->info("✅ Contraseña reseteada exitosamente!");
            $this->newLine();
            $this->info("🔐 Nuevas credenciales:");
            $this->line("   Email:    {$user->email}");
            $this->line("   Password: {$password}");
            $this->newLine();

            // Probar la nueva contraseña
            if (Hash::check($password, $user->password)) {
                $this->info("✅ Contraseña verificada - El login debería funcionar ahora.");
            }

            return 0;
        }

        $this->info("❌ Operación cancelada.");
        return 1;
    }
}

