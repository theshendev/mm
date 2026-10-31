<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the site content and, when ADMIN_EMAIL and ADMIN_PASSWORD are set, an admin account.
     */
    public function run(): void
    {
        $this->call(ContentSeeder::class);

        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if ($email && $password) {
            User::query()->updateOrCreate(
                ['email' => $email],
                ['name' => env('ADMIN_NAME', 'Admin'), 'password' => $password],
            );
            $this->command?->info("Admin account ready: {$email}");
        } else {
            $this->command?->warn('No admin created. Set ADMIN_EMAIL and ADMIN_PASSWORD, or run: php artisan make:filament-user');
        }
    }
}
