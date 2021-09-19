<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakercard = Faker::create();
        foreach (range(1,10) as $value) {
            DB::table('cards')->insert([
                'name' => $fakercard->name,
                'desk_list_id' => $fakercard->numberBetween(1,10),
            ]);
        };
    }
}
