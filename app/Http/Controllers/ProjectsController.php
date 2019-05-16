<?php

namespace App\Http\Controllers;

use App\Language;
use App\Project;
use App\Project_Attending;
use App\Repositories\ProjectRepository;
use App\Repositories\TeamRepository;
use App\Role;
use App\Team;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    private $projectRepository;
    private $teamRepository;

    public function __construct(ProjectRepository $projectRepository, TeamRepository $teamRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->teamRepository = $teamRepository;
    }

    public function showDetails()
    {
        $projects = Project::all()->sortByDesc('start_date');
        $positions = Role::where('project/event', 'project')->get();
        $project_language = Language::all();
        $teams=array();
        $organizers=array();
        $button = "No button";
        $user=Auth::user();
        if($user!=null){
            if(!$projects->isEmpty()) {
                foreach ($projects as $project){
                    $teams[$project->name]= $this->projectRepository->getProjectsTeams($project);
                    $organizers[$project->name]=$this->projectRepository->getProjectOrganizer($project);
                }
                return response()->json(['projects' => $projects, 'positions' => $positions, 'button' => $button, 'project_language' => $project_language, 'teams' => $teams, 'organizers'=>$organizers, 'user'=>$user]);
            }
            else {
                return response()->json(['projects' => $projects, 'positions' => $positions, 'button' => $button, 'project_language' => $project_language]);
            }
        }
        return response()->json("User not logged in.");
    }

    public function saveNewProject(Request $request)
    {
        $project_lead_id=Auth::user()!=null?Auth::id():null;
        $lazybot_id=User::where('username', 'lazybot')->first()->id;
        $this->projectRepository->validateNewProject($request);
        $project = $this->projectRepository->createNewProject($request['project_new_name'], $request['project_new_description'], $request['project_new_sector'], $request['project_new_start_date'], $request['project_new_end_date'], $request['project_new_location'], $request['project_new_language']);
        $team=$this->teamRepository->findOrCreateTeam($request['project_new_team'], $project->id);
        $attendies = $this->projectRepository->addOpenPositions($request->input('project_new_cbox'), $project, $project_lead_id, $lazybot_id, $team->id);
        $users = array();
        if($attendies!=null){
            $users = $this->projectRepository->returnAttendies($attendies);
        }
        return response()->json(["project"=>$project, "team"=>$users]);
    }



    public function deleteProject(Request $request)
    {
        $num_of_projects = Project::count();
        return response()->json(['num_of_projects' => $num_of_projects]);
    }
}
