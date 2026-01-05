<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;

//created by mohammed 05/01

class ResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             // 1. Serveurs
        ResourceCategory::create([
            'name' => 'Serveurs',
            'description' => 'Serveurs Physiques Rackables',
            'icon' => 'server',
        ]);

        // 2. VM
        ResourceCategory::create([
            'name' => 'Machines Virtuelles',
            'description' => 'Instances Cloud / Virtualisées',
            'icon' => 'desktop',
        ]);

        // 3. Stockage
        ResourceCategory::create([
            'name' => 'Stockage',
            'description' => 'Disques SSD / HDD / NAS',
            'icon' => 'hdd',
        ]);

        // 4. Réseau
        ResourceCategory::create([
            'name' => 'Réseau',
            'description' => 'Switchs, Routeurs, Pare-feu',
            'icon' => 'network-wired',
        ]);
    }
    }

