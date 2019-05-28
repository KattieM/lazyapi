<?php

namespace App\Http\Controllers;

use App\Event;
use App\Project;
use App\Repositories\ProjectRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->middleware('auth');
        $this->projectRepository = $projectRepository;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function returnEventsAndProjects(){

        $user=Auth::user();

        $users=User::where([['id', '!=', 1], ['id', '!=', Auth::id()]])->take(12)->get();

        $events = Event::with('location', 'language')->orderBy('date', 'desc')->take(4)->get();
        $projects = Project::with('location', 'language')->orderBy('start_date', 'desc')->take(4)->get();

        //foreach event check the user's going/not going status
//        $goings=array();
//        foreach ($events as $event){
//            $goings[$event->name]=$this->isUserAttending($event, $user);
//            $organizers[$event->name]=$this->getAllEventOrganizers($event);
//
//        }

        $teams=array();
        foreach ($projects as $project){
            $teams[$project->name]= $this->projectRepository->getProjectsTeams($project);
        }


        return response()->json(["projects"=>$projects, "events"=>$events, "users"=>$users, "teams"=> $teams, "user"=>$user, "message"=>"Success"]);


    }
}
