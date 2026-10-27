<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        DB::table('users')->insert([
            'name' => "phunghanh",
            'email' => 'phunghanh@gmail.com',
            'level'=> 1,
            'password' => Hash::make('05112000'),
        ]);
    }
}
