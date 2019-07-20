<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'user_name' => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => $faker->password,
                'email' => $faker->email,
                'visitor' => $faker->ipv4
            ]);
        }
    }
}
