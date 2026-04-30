<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        $unas = Institution::where('name', 'UNAS')->first();

        if ($unas) {
            User::firstOrCreate([
                'email' => 'admin@unas.edu.pe'
            ],[
                'name' => 'Admin UNAS',
                'password' => Hash::make('12345678'), // Default simple password for testing
                'role' => 'admin',
                'institution_id' => $unas->id,
            ]);
        }
    }
}
