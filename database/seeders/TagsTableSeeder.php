<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        Tag::create(['name' => 'General', 'slug' => 'general', 'type' => 'post']);
        Tag::create(['name' => 'PHP', 'slug' => 'php', 'type' => 'post']);
        Tag::create(['name' => 'Laravel', 'slug' => 'laravel', 'type' => 'post']);
        Tag::create(['name' => 'Javascript', 'slug' => 'javascript', 'type' => 'post']);
        Tag::create(['name' => 'Typescript', 'slug' => 'typescript', 'type' => 'post']);
        Tag::create(['name' => 'Linux Mint', 'slug' => 'linux-mint', 'type' => 'post']);
        Tag::create(['name' => 'NixOS', 'slug' => 'nixos', 'type' => 'post']);
        Tag::create(['name' => 'Windows', 'slug' => 'windows', 'type' => 'post']);
        Tag::create(['name' => 'iOS', 'slug' => 'ios', 'type' => 'post']);
    }
}
