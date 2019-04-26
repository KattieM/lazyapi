<?php

namespace App\Repositories;

interface EventRepositoryInterface
{
    public function model();
    public function addEventOrganizer($organizer, $event);
}
