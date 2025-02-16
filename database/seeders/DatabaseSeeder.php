<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this
            ->call(RolesAndPermissionsTableSeeder::class)
            ->call(SupportTicketStatusSeeder::class);

        User::factory()->create([
            'name' => 'Sketch',
            'email' => 'test@example.com',
        ])->syncRoles(['super-admin']);
        // User::factory(10)->create();
    }
}
