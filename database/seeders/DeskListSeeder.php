<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakerlist = Faker::create();
        foreach (range(1,10) as $value) {
            DB::table('desk_lists')->insert([
                'name' => $fakerlist->name,
                'desk_id' => $fakerlist->numberBetween(1,5),
            ]);
        };
    }
}
