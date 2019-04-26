<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event_roles = ['organizer', 'attendee'];
        $project_roles = ['lead', 'HR', 'FR', 'IT', 'PR'];

        foreach ($event_roles as $role){
            DB::table('roles')->insert(
                array(
                    'title' => $role,
                    'project/event'=>'event'
                )
            );
        }

        foreach ($project_roles as $role){
            DB::table('roles')->insert(
                array(
                    'title' => $role,
                    'project/event'=>'project'
                )
            );
        }
    }
}
