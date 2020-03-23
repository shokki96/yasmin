<?php

namespace MY\Service\Database\Seeders;

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
        $this->call(ServiceTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
    }
}
