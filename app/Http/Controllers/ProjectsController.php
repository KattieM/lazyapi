<?php

namespace App\Http\Controllers;

use App\Language;
use App\Project;
use App\Project_Attending;
use App\Repositories\LanguageRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ProjectAttendingRepository;
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
    private $languageRepository;
    private $locationRepository;
    private $projectAttendingRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        TeamRepository $teamRepository,
        LanguageRepository $languageRepository,
        LocationRepository $locationRepository,
        ProjectAttendingRepository $projectAttendingRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->teamRepository = $teamRepository;
        $this->languageRepository = $languageRepository;
        $this->locationRepository = $locationRepository;
        $this->projectAttendingRepository = $projectAttendingRepository;
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

    public function saveProject(Request $request)
    {
        $this->projectRepository->validateNewProject($request);
        if($request->has('id')){
            $loc_id = $this->locationRepository->findOrCreateLocation($request['project_new_location']);
            $lang_id = $this->languageRepository->addLanguage($request['project_new_language']);
            $project = $this->projectRepository->createNewProject($request['project_new_name'],
                $request['project_new_description'], $request['project_new_sector'],
                $request['project_new_start_date'], $request['project_new_end_date'],
                $loc_id , $lang_id, $request['id'] );
        }
        else{
            $loc_id = $this->locationRepository->findOrCreateLocation($request['project_new_location']);
            $lang_id = $this->languageRepository->addLanguage($request['project_new_language']);
            $project = $this->projectRepository->createNewProject($request['project_new_name'],
                $request['project_new_description'], $request['project_new_sector'],
                $request['project_new_start_date'], $request['project_new_end_date'],
                $loc_id, $lang_id);
        }

        $team=$this->teamRepository->findOrCreateTeam($request['project_new_team'], $project->id);

        return response()->json(['project'=>$project, $team]);
    }



    public function deleteProject(Request $request)
    {
        $response = $this->projectRepository->deleteProjectData($request['id']);
        if($response){
            $num_of_projects = Project::count();
            return response()->json(['num_of_projects' => $num_of_projects]);
        }
        return response()->json(['message'=>'Something went wrong']);
    }

    public function showProjectDetails($id){
        return Project::findOrFail($id);
    }
}
