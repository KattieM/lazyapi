<?php

namespace App\Repositories;

use App\Event;
use App\Event_Attending;
use App\Role;
use Illuminate\Http\Request;
/**
 * Class EventRepository.
 */
class EventRepository implements EventRepositoryInterface
{
    public function model()
    {
        return Event::class;
    }

    public function validateNewEvent(Request $request)
    {
        $request->validate([
            'event_new_name' => 'required|unique:events,name|max:191',
            'event_new_description' => 'required|max:191',
            'event_new_date' => 'required|date|after:yesterday',
            'event_new_location' => 'required',
            'event_new_language' => 'required',
        ], [
            'event_new_name.unique' => 'Event name already taken',
            'event_new_date.after' => 'The event date must be today or a date after today.',
        ]);
    }

    public function addEventOrganizer($organizer, $event)
    {
        if($organizer!=null){
            $role = Role::where('project/event', 'event')->where('title', 'organizer')->first();
            $role_id = $role->id;
            $event_att = new Event_Attending;
            $event_att->event_id = $event;
            $event_att->role_id = $role_id;
            $event_att->user_id = $organizer;
            $event_att->save();
        }
    }

    public function isUserAttending($event, $user_id)
    {
        $event_attending = Event_Attending::where('user_id', $user_id)->where('event_id', $event->id)->get();
        $going = $event_attending->isEmpty() ? "not going" : "going";
        return $going;
    }

    public function getAllEventOrganizers($event)
    {
        $organizer_role = Role::where('project/event', 'event')->where('title', 'organizer')->first();
        $event_attending = Event_Attending::where([['event_id', '=', $event->id], ['role_id', '=', $organizer_role->id]])->first();
        return $event_attending != null ? $event_attending->user_id : null;
    }
}
