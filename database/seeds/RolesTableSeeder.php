<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => Str::uuid(),
                'role_name' => 'Admin',
                'slug' => 'admin',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'user_log' => 'System'
            ],
            [
                'id' => Str::uuid(),
                'role_name' => 'Manager',
                'slug' => 'manager',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'user_log' => 'System'
            ],
            [
                'id' => Str::uuid(),
                'role_name' => 'Helpdesk',
                'slug' => 'helpdesk',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'user_log' => 'System'
            ],
            [
                'id' => Str::uuid(),
                'role_name' => 'User',
                'slug' => 'user',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'user_log' => 'System'
            ]
        ]);
    }
}
