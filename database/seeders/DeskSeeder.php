<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakerdesk = Faker::create();
        foreach (range(1,5) as $value) {
            DB::table('desks')->insert([
                'name' => $fakerdesk->name,
            ]);
        };
    }
}
