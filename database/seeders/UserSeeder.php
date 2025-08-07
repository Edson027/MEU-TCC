<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // 1. Criar perfis (roles)
        $roles = [
            'super-admin' => 'Edson Chissaluquila',
            'admin' => 'Administrador',
            'manager' => 'Gerente',
            'doctor' => 'Médico',
            'technician' => 'Técnico',
            'user' => 'Usuário Padrão'
        ];

        foreach ($roles as $key => $name) {
            Role::firstOrCreate([
                'name' => $key,
                'guard_name' => 'web'
            ], [
                'name' => $key,
                'guard_name' => 'web'
            ]);
        }

        // 2. Criar usuários de exemplo password12
        $users = [
            [
                'name' => 'Edson Carlos',
                'email' => 'edsoncarlos027@outlook.com',
                'password' => Hash::make('password123'),
                'roles' => ['super-admin'],
                'painel'=>'administrador'
            ],
            [
                'name' => 'Administrador',
                'email' => 'edsoncarlos@teste.com',
                'password' => Hash::make('password123'),
                'roles' => ['admin'],
                'painel'=>'enfermeira',
            ],
            [
                'name' => 'E Carlos Chissaluquila',
                'email' => 'gerente@example.com',
                'password' => Hash::make('password123'),
                'roles' => ['manager'],
                'painel'=>'gerente'
            ],
            [
                'name' => 'Carlos Celestino',
                'email' => 'medico@example.com',
                'password' => Hash::make('password123'),
                'roles' => ['doctor'],
                'painel'=>'médico'
            ],
            [
                'name' => 'Técnico Carlos',
                'email' => 'tecnico@example.com',
                'password' => Hash::make('password123'),
                'roles' => ['technician'],
                'painel'=>'tecnico'
            ],
            [
                'name' => 'Edson carlos',
                'email' => 'usuario@example.com',
                'password' => Hash::make('password123'),
                'roles' => ['user'],
                'painel'=>'tecnico'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                    'is_active' => true
                ]
            );

            // Atribuir roles ao usuário
            if (isset($userData['roles'])) {
                $user->syncRoles($userData['roles']);
            }
        }

        $this->command->info('Usuários e perfis criados com sucesso!');
    }
}
