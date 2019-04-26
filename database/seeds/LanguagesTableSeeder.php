<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = ['serbian', 'english'];
        foreach ($languages as $language){
            DB::table('languages')->insert(
                array(
                    'name' => $language,
                )
            );
        }
    }
}
