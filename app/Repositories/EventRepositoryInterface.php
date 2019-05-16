<?php

namespace App\Repositories;

interface EventRepositoryInterface
{
    public function model();
    public function addEventOrganizer($organizer, $event);
    public function isUserAttending($event, $user);
    public function getAllEventOrganizers($event);
}
