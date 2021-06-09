<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(30)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@editor.com',
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Viewer',
            'email' => 'viewer@viewer.com',
            'role_id' => 3,
        ]);
    }
}
