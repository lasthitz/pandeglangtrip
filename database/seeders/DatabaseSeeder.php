<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder lain (kalau ada) biarin tetap
        $this->call([
            PublicT5Seeder::class,
        ]);
    }
}
