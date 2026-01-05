<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;

//changed the whole things, bcs u was working woth the wrong columns :mohammed  05/01
class ResourcesTableSeeder extends Seeder
{
    public function run(): void
    {
     // 1. On RÉCUPÈRE les catégories existantes ( ResourceCategorySeeder)
        $cat_server = ResourceCategory::where('name', 'Serveurs')->first();
        $cat_vm = ResourceCategory::where('name', 'Machines Virtuelles')->first();
        $cat_storage = ResourceCategory::where('name', 'Stockage')->first();
        $cat_network = ResourceCategory::where('name', 'Réseau')->first();


        //  Récupérer un responsable (le premier trouvé dans la base)
        $responsable = User::where('role_id', 3)->first() ?? User::first(); 
        // Si aucun user n'existe encore, on met null (autorisé par la migration)
        $responsable_id = $responsable ? $responsable->id : null;

        // 3. Créer les Ressources
        Resource::create([
            'category_id' => $cat_server->id,
            'name' => 'Server Alpha',
            'description' => 'Serveur principal de calcul',
            'specifications' => ['CPU' => 'Intel Xeon', 'RAM' => '64GB', 'OS' => 'Linux'],
            'resource_status' => 'disponible',
            'location' => 'Rack A1',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);

        Resource::create([
            'category_id' => $cat_vm->id,
            'name' => 'VM Beta',
            'description' => 'VM pour les TP étudiants',
            'specifications' => ['CPU' => '4 vCores', 'RAM' => '16GB', 'OS' => 'Ubuntu 22.04'],
            'resource_status' => 'disponible',
            'location' => 'Cluster Virtuel 1',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);

        Resource::create([
            'category_id' => $cat_storage->id,
            'name' => 'Storage Gamma',
            'description' => 'Stockage de sauvegarde',
            'specifications' => ['Capacity' => '10TB SSD', 'Type' => 'RAID 10'],
            'resource_status' => 'disponible',
            'location' => 'Salle Serveur B',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);

        Resource::create([
            'category_id' => $cat_network->id,
            'name' => 'Switch Delta',
            'description' => 'Switch principal étage 1',
            'specifications' => ['Ports' => '48', 'Speed' => '10GbE'],
            'resource_status' => 'disponible',
            'location' => 'Couloir C',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);

         Resource::create([
            'category_id' => $cat_server->id,
            'name' => 'Backup Server Z',
            'description' => 'Serveur de backup pour les données critiques',
            'specifications' => ['CPU' => '32 Cores', 'RAM' => '64GB', 'HDD' => '10TB'],
            'resource_status' => 'disponible',
            'location' => 'Rack B2',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);

        
        Resource::create([
            'category_id' => $cat_network->id,
            'name' => 'Cisco Router Core',
            'description' => 'Routeur de bordure pour la fibre optique',
            'specifications' => ['Ports' => '8', 'Throughput' => '10Gbps'],
            'resource_status' => 'maintenance', // On en met un en maintenance pour tester !
            'location' => 'Salle Réseau 1',
            'responsable_id' => $responsable_id,
            'is_active' => true,
        ]);
    }
}