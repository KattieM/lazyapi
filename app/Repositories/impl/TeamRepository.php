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
        $team = Team::where('name', $name)->first();
        if ($team == null) {
            $team = new Team;
            $team->name = $name;
            $team->project_id=$project;
            $team->save();
        }
        return $team;
    }
}
