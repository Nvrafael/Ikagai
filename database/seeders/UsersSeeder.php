<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@ikigai.com'],
            [
                'name' => 'Admin IKIGAI',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Crear nutricionista principal: Paco Villar Cantalejo
        User::updateOrCreate(
            ['email' => 'paco@ikigai.com'],
            [
                'name' => 'Paco Villar Cantalejo',
                'password' => Hash::make('password'),
                'role' => 'nutritionist',
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario cliente de ejemplo
        User::updateOrCreate(
            ['email' => 'cliente@ikigai.com'],
            [
                'name' => 'Cliente Ejemplo',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Usuarios del sistema creados correctamente');
        $this->command->info('  - Admin: admin@ikigai.com / password');
        $this->command->info('  - Nutricionista: paco@ikigai.com / password (Paco Villar Cantalejo)');
        $this->command->info('  - Cliente: cliente@ikigai.com / password');
    }
}

