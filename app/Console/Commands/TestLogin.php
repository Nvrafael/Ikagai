<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:test-login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar login de un usuario específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("🔍 Buscando usuario: {$email}");
        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuario no encontrado: {$email}");
            $this->newLine();
            $this->info("💡 Usuarios disponibles:");
            $this->call('users:list');
            return 1;
        }

        $this->info("✅ Usuario encontrado:");
        $this->line("   • ID: {$user->id}");
        $this->line("   • Nombre: {$user->name}");
        $this->line("   • Email: {$user->email}");
        $this->line("   • Rol: {$user->role}");
        $this->newLine();

        // Verificar la contraseña
        if (Hash::check($password, $user->password)) {
            $this->info("✅ ¡Contraseña correcta!");
            $this->newLine();
            $this->info("🎉 El login debería funcionar correctamente.");
            return 0;
        } else {
            $this->error("❌ Contraseña incorrecta");
            $this->newLine();
            $this->warn("🔧 ¿Quieres resetear la contraseña a 'password'? (s/n)");
            
            if ($this->confirm('¿Resetear contraseña?', true)) {
                $user->password = Hash::make('password');
                $user->save();
                $this->info("✅ Contraseña reseteada a: password");
            }
            
            return 1;
        }
    }
}

