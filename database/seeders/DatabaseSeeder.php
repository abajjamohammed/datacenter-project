<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  //  use WithoutModelEvents;

    
    public function run(): void
    {
        $this->call(RolesTableSeeder::class);


       /** User::factory()->create([
        *    'name' => 'Test User',
        *    'email' => 'test@example.com',
        *    'role_id' => 4, 
        *]); */ //  we have to create a user for every type 
         // the users are in the userSeeder


        // 2. Ensuite les Utilisateurs (car les Ressources ont besoin d'un Responsable)
        $this->call(UserSeeder::class);

        // 3. Ensuite les Catégories (car les Ressources en ont besoin)
        $this->call(ResourceCategorySeeder::class);

        // 4. Enfin les Ressources
        $this->call(ResourcesTableSeeder::class);

        //we can add later here the reservation seeders  :mohammed

        

    }
}
