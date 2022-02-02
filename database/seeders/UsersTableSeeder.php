<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                "name" => "Administrador",
                "email" => "admin@ecommerce.com",
                "email_verified_at" => now(),
                "password" => bcrypt("admin"),
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ],
        ]);
        User::where('email', 'admin@ecommerce.com')->first()->assignRole('Admin');
    }
}
