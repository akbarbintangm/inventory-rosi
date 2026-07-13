<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = collect([
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'level' => 'owner',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ],
            [
                'name' => 'Manager PIC',
                'username' => 'manager_pic',
                'email' => 'quest@quest.com',
                'level' => 'manager_pic',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ],
            [
                'name' => 'User Admin',
                'username' => 'user_admin',
                'email' => 'user@user.com',
                'level' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ]
        ]);

        $users->each(function ($user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        });
    }
}
