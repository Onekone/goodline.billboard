<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (app()->environment() !== 'testing') {
            DB::statement("SET foreign_key_checks=0");
        }
//        }

        DB::table('email_verifies')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('social_providers')->truncate();
        DB::table('users')->truncate();
        $user = new User();
        $faker = \Faker\Factory::create();
        foreach (range(1,5) as $ad) {
            $user->create([
                'name' => $faker->name(20),
                'email' => $faker->email(),
                'password' => $faker->password(),
            ]);
        }

        if (app()->environment() !== 'testing') {
            DB::statement("SET foreign_key_checks=1");
        }
//
    }
}
