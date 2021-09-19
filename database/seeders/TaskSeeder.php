<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakertask = Faker::create();
        foreach (range(1,10) as $value) {
            DB::table('tasks')->insert([
                'name' => $fakertask->name,
                'card_id' => $fakertask->numberBetween(1,10),
            ]);
        };
    }
}
