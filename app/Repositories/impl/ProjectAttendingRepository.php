<?php

namespace App\Repositories;

use App\Project_Attending;
use App\Role;

/**
 * Class ProjectAttendingRepository.
 */
class ProjectAttendingRepository implements ProjectAttendingRepositoryInterface
{
    public function model()
    {
        return Project_Attending::class;
    }

    public function addNewProjectAttending($openPosition, $project, $project_lead)
    {
        $project_att = new Project_Attending;
        $role = Role::where('title', $openPosition)->get();
        $project_att->team_id = $project->team->id;
        $project_att->role_id = $role->first()->id;
        $project_att->user_id = $project_lead;
        $project_att->save();
    }
}
