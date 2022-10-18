<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\Role::create([
             'name' => 'user',
             'description' => 'A simple User'
        ]);
        \App\Models\Role::create([
            'name' => 'creator',
            'description' => 'Manage a resource'
       ]);
       \App\Models\Role::create([
             'name' => 'admin',
             'description' => 'Manage all system and users'
        ]);
        \App\Models\Setting::create([
            'key' => 'first langage',
            'value' => 'Espagnol'
        ]);
    }
}
