<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\user;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = user::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID'); // Menggunakan locale Indonesia

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->userName . '@gmail.com', // Email dengan domain @gmail.com
            'password' => bcrypt('ficky123'), // Password terenkripsi
            'no_kk' => $faker->unique()->numerify('###########'),
            'no_hp_user' => '08' . $faker->numerify('##########'), // Nomor telepon diawali dengan 08
            'alamat' => 'Kecamatan Payung Sekaki, Kelurahan Labuhbaru Timur, Pekanbaru, ' . $faker->streetAddress,
            'RT' => $faker->numerify('##'),
            'RW' => $faker->numerify('##'),
            'role' => 'Warga', // Default role, akan di-overwrite di seeder
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
