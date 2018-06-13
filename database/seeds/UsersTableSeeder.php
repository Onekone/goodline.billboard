<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $faker = \Faker\Factory::create();
        foreach (range(1,5) as $ad) {
            $user->create([
                'name' => $faker->name(20),
                'email' => $faker->email(),
                'password' => $faker->password(),
            ]);
        }

        $user->create([
            'name' => 'root',
            'email' =>  'admin@mail.ru',
            'password' => '$2y$10$34B7PD0IDkoZpVP9./Ufvuw6q9.ug6zC4Zh5PUwXvsBT7GmMTEvSq',
        ]);
    }
}