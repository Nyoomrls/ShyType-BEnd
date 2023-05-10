<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(10)->create();
        DB::table('admins')->insert([
            'name' => 'admin',
            'email' => 'admin',
            'password' => Hash::make('123'),
            "email_verified_at" => Carbon::now()->toDateTimeString(),
        ]);

        // \App\Models\Admin::table('admins') ->insert([
        //     'name' => 'admin',
        //     'email' => '@dmin',
        //     'password' => '123',
        // ]);
    }
}
