<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Invité
        Role::create([
            'name' => 'invite', 
            'description' => 'Visiteur externe (Lecture seule et demande de compte)'
        ]);

        // 2. Utilisateur Interne
        Role::create([
            'name' => 'utilisateur_interne', 
            'description' => 'Personnel interne (Ingénieur, Enseignant, Doctorant)'
        ]);

        // 3. Responsable Technique
        Role::create([
            'name' => 'responsable_technique', 
            'description' => 'Gestionnaire responsable d\'un ensemble de ressources'
        ]);

        // 4. Administrateur
        Role::create([
            'name' => 'admin', 
            'description' => 'Super-Administrateur avec tous les droits'
        ]);
    }
}