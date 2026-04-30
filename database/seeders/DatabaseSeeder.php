<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(InstitutionsTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(ProfesorsTableSeeder::class);
        $this->call(EvaluationsTableSeeder::class);
        $this->call(ReseniasTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
    }
}
