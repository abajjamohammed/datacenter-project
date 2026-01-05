<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Resource;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $resources = Resource::all();

        foreach ($users as $user) {
            foreach ($resources as $resource) {
                Reservation::create([
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'start_time' => now()->addDays(rand(1, 5)),
                    'end_time' => now()->addDays(rand(6, 10)),
                    'status' => 'Pending',
                    'justification' => 'Test booking for demo purposes',
                ]);
            }
        }
    }
}
