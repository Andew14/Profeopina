<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionsTableSeeder extends Seeder
{
    public function run()
    {
        // Solo Universidad Nacional Agraria de la Selva
        Institution::firstOrCreate(['name' => 'UNAS']);
    }
}
