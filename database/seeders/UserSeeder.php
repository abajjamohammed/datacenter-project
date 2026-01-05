<?php

//created by mohammed 05/01

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Récupération des IDs des rôles
        // On utilise firstOrFail() pour être sûr que le rôle existe (sinon ça plante et on sait qu'il y a un bug)
        $role_admin = Role::where('name', 'admin')->firstOrFail();
        $role_manager = Role::where('name', 'responsable_technique')->firstOrFail();
        $role_user = Role::where('name', 'utilisateur_interne')->firstOrFail();
        $role_guest = Role::where('name', 'invite')->firstOrFail();

        // 2. Création de l'Administrateur
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('password'), // Mot de passe : password
            'role_id' => $role_admin->id,
            'is_active' => true,
        ]);

        // 3. Création du Responsable Technique
        User::create([
            'name' => 'Responsable IT',
            'email' => 'manager@datacenter.com',
            'password' => Hash::make('password'),
            'role_id' => $role_manager->id,
            'department' => 'Infrastructure',
            'phone' => '0612345678',
            'is_active' => true,
        ]);

        // 4. Création de l'Utilisateur Interne
        User::create([
            'name' => 'Etudiant Test',
            'email' => 'user@datacenter.com',
            'password' => Hash::make('password'),
            'role_id' => $role_user->id,
            'profile' => 'ingenieur', // Sans accent
            'department' => 'Génie Logiciel',
            'is_active' => true,
        ]);

        // 5. Création de l'Invité
        User::create([
            'name' => 'Visiteur Externe',
            'email' => 'guest@datacenter.com',
            'password' => Hash::make('password'),
            'role_id' => $role_guest->id,
            'is_active' => true,
        ]);
    }
}
