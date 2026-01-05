<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

//edited by mohammed 05/01

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< Updated upstream
        $roles = [
            'invité',
            'utilisateur_interne',
            'responsable_technique',
            'admin',
        ];
=======
    
        /**
     *   $roles = [
     *       'Guest',
     *       'Internal User',
     *       'Technical Resource Manager',
     *       'Data Center Administrator',
     *   ];
*
     *   foreach ($roles as $role) {
     *       DB::table('roles')->insert([
     *           'name' => $role,
     *           'created_at' => now(),
     *           'updated_at' => now(),
     *       ]);
      *  }
            */

        //mohammed: the code u did misses description and uses wrong roles names 
         // (Guest)
        Role::create([
            'name' => 'invite', 
            'description' => 'Visiteur externe (Lecture seule et demande de compte)'
        ]);

        // (Internal User)
        Role::create([
            'name' => 'utilisateur_interne', 
            'description' => 'Personnel interne (Ingénieur, Enseignant, Doctorant)'
        ]);

        // (Technical Manager)
        Role::create([
            'name' => 'responsable_technique', 
            'description' => 'Gestionnaire responsable d\'un ensemble de ressources'
        ]);

        // (Admin)
        Role::create([
            'name' => 'admin', 
            'description' => 'Super-Administrateur avec tous les droits'
        ]);
>>>>>>> Stashed changes

    }
}
