<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Álvaro Tavares de Oliveira',
            'email' => 'ato.cdf@gmail.com',
        ]);

        $this->call(ProjectSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(TagSeeder::class);
    }
}
