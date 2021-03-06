<?php

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
        DB::table('users')->insert([
            [
            	'name' => 'admin',
            	'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
