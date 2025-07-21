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
        // Créer les priorités de base
        \App\Models\Priority::create(['name' => 'Urgente']);
        \App\Models\Priority::create(['name' => 'Importante']);
        \App\Models\Priority::create(['name' => 'Moyenne']);
        \App\Models\Priority::create(['name' => 'Faible']);
    }
}
