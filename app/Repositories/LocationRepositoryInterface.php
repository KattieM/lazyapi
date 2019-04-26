<?php

namespace App\Repositories;

interface LocationRepositoryInterface
{
    public function model();
    public function findOrCreateLocation($name);
}
