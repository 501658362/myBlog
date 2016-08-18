<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(PostTableSeeder::class);

        Model::reguard();
    }
}

class PostTableSeeder extends Seeder
{
    public function run()
    {
        \App\Http\Model\Post::truncate();
        factory(\App\Http\Model\Post::class, 20)->create();
    }
}