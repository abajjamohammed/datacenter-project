<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource; // Make sure the Resource model exists

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example resources
        Resource::create([
            'name' => 'Server Alpha',
            'type' => 'Server',
            'specs' => 'Intel Xeon, 64GB RAM, 1TB SSD, Linux',
            'capacity' => 1,
            'status' => 'available'
        ]);

        Resource::create([
            'name' => 'VM Beta',
            'type' => 'Virtual Machine',
            'specs' => '4 CPU cores, 16GB RAM, Ubuntu 22.04',
            'capacity' => 1,
            'status' => 'available'
        ]);

        Resource::create([
            'name' => 'Storage Gamma',
            'type' => 'Storage',
            'specs' => '10TB SSD Array, RAID 10',
            'capacity' => 10,
            'status' => 'available'
        ]);

        Resource::create([
            'name' => 'Network Switch Delta',
            'type' => 'Network Equipment',
            'specs' => '48 ports, 10GbE, managed',
            'capacity' => 1,
            'status' => 'available'
        ]);
    }
}
