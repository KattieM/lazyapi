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
    public function saveEvent(Request $request)
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

    public function deleteEvent(Request $request)
    {
        Event_Attending::where('event_id', $request['id'])->forceDelete();
        Event::where('id', $request['id'])->forceDelete();
        $num_of_events = Event::count();
        return response()->json(['num_of_events' => $num_of_events]);
    }

    public function showEventDetails($id){
        return Event::findOrFail($id);
    }
}
