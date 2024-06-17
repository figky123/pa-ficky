<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat 4 pengguna dengan peran 'Jumantik'
        User::factory()->count(4)->create([
            'role' => 'Jumantik'
        ]);

        // Buat 4 pengguna dengan peran 'Puskesmas'
        User::factory()->count(4)->create([
            'role' => 'Puskesmas'
        ]);

        // Buat 1 pengguna dengan peran 'Lurah'
        User::factory()->create([
            'role' => 'Lurah'
        ]);

        // Buat 11 pengguna dengan peran 'Warga'
        User::factory()->count(11)->create([
            'role' => 'Warga'
        ]);
    }
}
