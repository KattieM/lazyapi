<?php

namespace App\Http\Controllers;

use App\Event;
use App\Event_Attending;
use App\Language;
use App\Repositories\EventRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    private $locationRepository;
    private $languageRepository;
    private $eventRepository;

    public function __construct(LocationRepository $locationRepository, LanguageRepository $languageRepository, EventRepository $eventRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->languageRepository = $languageRepository;
        $this->eventRepository = $eventRepository;
    }

    //Returns all necessary data
    public function showDetails()
    {
        $events = Event::all()->sortByDesc('date');
        $button = "No button";
        $events_language = Language::all();
        $goings = array();
        $organizers=array();
        $user = Auth::user();

        if($user!=null){
            if(!$events->isEmpty()){
                foreach ($events as $event) {
                    $goings[$event->name] = $this->eventRepository->isUserAttending($event, $user->id);
                    $organizers[$event->name]=$this->eventRepository->getAllEventOrganizers($event);
                }
                return response()->json(['events'=>$events, 'button'=>$button, 'events_language'=>$events_language, 'goings'=>$goings, 'user'=>$user, 'organizers'=>$organizers]);
            } else {
                return response()->json(['events'=>$events, 'button'=>$button, 'events_language'=>$events_language, 'user'=>$user]);
            }
        }
        return response()->json("User not logged in.");
    }

    //Function for update of an event and save of a new event
    public function saveNewEvent(Request $request)
    {
        $this->eventRepository->validateNewEvent($request);
        $event = null;
        if($request->has('id')){
            $event = Event::find($request['id']);
        } else {
            $event = new Event;
        }
        if($event!=null){
            $event->name = $request['event_new_name'];
            $event->description = $request['event_new_description'];
            $event->date = $request['event_new_date'];
            $event->time = $request['event_new_time'];
            $event->loc_id = $this->locationRepository->findOrCreateLocation($request['event_new_location']);
            $event->lang_id = $this->languageRepository->addLanguage($request['event_new_language']);
            $event->save();
            //Kakav token, cemu token?
            $token = csrf_token();
            $user = Auth::user() != null ? Auth::id() : null;
            $this->eventRepository->addEventOrganizer($user, $event->id);
            return response()->json(['event' => $event]);
        }
        return response()->json(['message' => 'Something went wrong.']);

    }

//    public function deleteOrUnattendEvent(Request $request)
//    {
//        if ($request->has('attend')) {
//            return $this->unattendEvent($request);
//        } else {
//            return $this->deleteEvent($request);
//        }
//    }

    public function deleteEvent(Request $request)
    {
        Event_Attending::where('event_id', $request['id'])->forceDelete();
        Event::where('id', $request['id'])->forceDelete();
        $num_of_events = Event::count();
        return response()->json(['num_of_events' => $num_of_events]);
    }

//    public function updateEvent(Request $request)
//    {
//        $event = Event::where('id', $request['id'])->forceDelete();
//        $event->name = $request['event_new_name'];
//        $event->description = $request['event_new_description'];
//        $event->date = $request['event_new_date'];
//        $event->time = $request['event_new_time'];
//        $event->loc_id = $this->locationRepository->findOrCreateLocation($request['event_new_location']);
//        $event->lang_id = $this->languageRepository->addLanguage($request['event_new_language']);
//        $event->save();
//        $num_of_events = Event::count();
//        return response()->json(['num_of_events' => $num_of_events]);
//    }

//    public function attendEvent(Request $request)
//    {
//        $msg = "";
//        $event_id = $request['id'];
//        $user_id = Auth::id();
//        $role = Role::where('title', 'attendee')->where('project/event', 'event')->get()->first();
//        $event_attendings = Event_Attending::where('user_id', $user_id)->where('event_id', $event_id)->get();
//        if ($event_attendings->isEmpty()) {
//            Event_Attending::create(['event_id' => $event_id, 'role_id' => $role->id, 'user_id' => $user_id]);
//        } else {
//            $msg = "You have already attended this event!";
//        }
//        return response()->json(['msg' => $msg]);
//    }
//
//    public function unattendEvent(Request $request)
//    {
//        $user_id = Auth::id();
//        $role = Role::where('title', 'attendee')->where('project/event', 'event')->get()->first();
//        $event_id = $request['id'];
//        $event_attendings = Event_Attending::where('user_id', $user_id)->where('event_id', $event_id)->where('role_id', $role->id)->get()->first();
//        $event_attendings->delete();
//    }
}
