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
            $adminEmail = env('ADMIN_EMAIL', 'admin@unas.edu.pe');
            $adminPassword = env('ADMIN_PASSWORD', 'OpinaAdmin#2026!UNAS');

            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Administrador Opina',
                    'password' => Hash::make($adminPassword),
                    'role' => 'admin',
                    'institution_id' => $unas->id,
                ]
            );
        }
    }
}
