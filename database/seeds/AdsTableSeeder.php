<?php

use Illuminate\Database\Seeder;
use App\Ad;
use App\User;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        DB::table('ads')->truncate();
        $ads = new Ad();
        $faker = \Faker\Factory::create();
        $users = User::all();
        $count = sizeof($users)-2;

        foreach (range(0,$count) as $user) {
            $p = $users[$user]->id;
            $this->command->getOutput()->writeln("<info>($p)</info>");
            foreach (range(1, 5) as $ad) {
                $ads->create([
                    'title' => $faker->text(20),
                    'content' => $faker->text(500),
                    'contact' => $faker->text(20),
                    'user_id' => $users[$user]->id,
                ]);
            }
        }
        DB::statement("SET foreign_key_checks=1");
    }
}
