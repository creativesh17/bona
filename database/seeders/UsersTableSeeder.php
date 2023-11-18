<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        DB::table("users")->insert([
            'role_id' => 1,
            'name' => 'Meela Khan',
            'username' => 'meela',
            'email' => 'meela@voila.von',
            'password' => bcrypt('12345678'),
        ]);

        DB::table("users")->insert([
            'role_id' => 2,
            'name' => 'Sheela Khan',
            'username' => 'sheela',
            'email' => 'sheela@voila.von',
            'password' => bcrypt('12345678'),
        ]);
    }

}
