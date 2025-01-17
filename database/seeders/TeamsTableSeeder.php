<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    public function run(): void
    {
        Team::create([
            'name' => 'Madhouse Infra',
            'description' => "We're developing the software that powers Madhouse Miners.",
            'role' => 'I develop for the web site of the infrastructure.',
            'github' => 'madhouseplatform',
            'website' => 'https://madhouseminers.com',
            'logo' => '/images/maddie.png',
        ]);

        Team::create([
            'name' => 'Madhouse Miners',
            'description' => 'We are a friendly bunch of people who run and play on a network of modded Minecraft servers.',
            'role' => 'I help administer the minecraft servers and moderate the Discord server.',
            'github' => 'madhouseminers',
            'website' => 'https://madhouseminers.com',
            'logo' => '/images/maddie.png',
        ]);
    }
}
