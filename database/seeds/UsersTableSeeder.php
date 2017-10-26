<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*model::unguard();
        DB::table('users')->truncate();*/
        
        //Using file content
        /*DB::unprepared(fil_get_contents(__DIR__.'/users.sql'));*/
        
        //Using direct query
        /*DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);*/
        
        //Using factory function
        //$user = factory(App\User::class)->make();
        $user = factory(App\User::class)->create();
    }
}
