<?php

namespace App\Repositories;

use App\Event;
use App\Event_Attending;
use App\Role;

/**
 * Class EventRepository.
 */
class EventRepository implements EventRepositoryInterface
{
    public function model()
    {
        return Event::class;
    }

    public function addEventOrganizer($organizer, $event)
    {
        $role = Role::where('project/event', 'event')->where('title', 'organizer')->first();
        $role_id = $role->id;
        $event_att = new Event_Attending();
        $event_att->event_id = $event;
        $event_att->role_id = $role_id;
        $event_att->user_id = $organizer;
        $event_att->save();
    }
}
