<?php

namespace App\Repositories;

interface TeamRepositoryInterface
{
    public function model();
    public function findOrCreateTeam($name, $project);

}
