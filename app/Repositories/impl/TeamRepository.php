<?php

namespace App\Repositories;

use App\Team;

/**
 * Class EventRepository.
 */
class TeamRepository implements TeamRepositoryInterface
{
    public function model()
    {
        return Team::class;
    }

    public function findOrCreateTeam($name, $project)
    {
        $team = Team::where('name', $name)->get();
        $msg="";
        if ($team->first()) {
            $msg="Team allready exists";
        } else {
            $team = new Team;
            $team->name = $name;
            $team->project_id=$project;
            $team->save();
        }
        return $msg;
    }
}
