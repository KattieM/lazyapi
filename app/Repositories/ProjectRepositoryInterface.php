<?php

namespace App\Repositories;

interface ProjectRepositoryInterface
{
    public function model();
    public function createNewProject($project, $name, $description, $sector, $start_date, $end_date, $location, $language);
    public function addOpenPositions($openPositions, $project, $project_lead, $lazybot);

}
