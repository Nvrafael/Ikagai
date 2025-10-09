<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ikagai.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Usuario Nutricionista
        User::create([
            'name' => 'Dr. Maria García',
            'email' => 'nutritionist@ikagai.com',
            'password' => Hash::make('password'),
            'role' => 'nutritionist',
            'email_verified_at' => now(),
        ]);

        // Usuario Cliente
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'client@ikagai.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Usuarios de prueba creados exitosamente!');
        $this->command->info('📧 Credenciales (password para todos: password):');
        $this->command->info('   Admin: admin@ikagai.com');
        $this->command->info('   Nutritionist: nutritionist@ikagai.com');
        $this->command->info('   Client: client@ikagai.com');
    }
}
