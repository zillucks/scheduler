<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->insert([
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'label' => 'Dashboard',
                'urls' => 'url("/")',
                'order_priority' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'label' => 'General Settings',
                'urls' => '#',
                'order_priority' => 2,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'label' => 'Master Users',
                'urls' => '#',
                'order_priority' => 3,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'label' => 'Views',
                'urls' => '#',
                'order_priority' => 4,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'id' => Str::uuid(),
                'parent_id' => null,
                'label' => 'Reports',
                'urls' => '#',
                'order_priority' => 5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);
    }
}
