<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            array(
                'name' => 'Lazy',
                'surname'=>'Member',
                'username'=>'lazybot',
                'email'=>'lazy@bot.com',
                'password'=>bcrypt('lazybot'),
                'photo_link'=>'img/user_icon.png',
                'join_date'=>Carbon::now(),
                'status'=>'active'
            )
        );
    }
}
