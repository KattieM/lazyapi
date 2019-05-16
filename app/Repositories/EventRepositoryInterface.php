<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface EventRepositoryInterface
{
    public function model();
    public function validateNewEvent(Request $request);
    public function addEventOrganizer($organizer, $event);
    public function isUserAttending($event, $user);
    public function getAllEventOrganizers($event);
}
