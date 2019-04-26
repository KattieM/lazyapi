<?php

namespace App\Repositories;

interface ProjectAttendingRepositoryInterface
{
    public function model();
    public function addNewProjectAttending($openPosition, $project, $project_lead);
}
