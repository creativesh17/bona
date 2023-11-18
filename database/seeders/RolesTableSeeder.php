<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table("roles")->insert([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);
        DB::table("roles")->insert([
            'name' => 'Author',
            'slug' => 'author',
        ]);
    }
}
