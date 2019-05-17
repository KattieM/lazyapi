<?php

namespace App\Repositories;

interface ProjectRepositoryInterface
{
    public function model();
    public function createNewProject($name, $description, $sector, $start_date, $end_date, $location, $language);

}
