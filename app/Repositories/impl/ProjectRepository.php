<?php

namespace App\Repositories;

use App\Project;
use App\Project_Attending;
use App\Role;
use App\Team;
use App\User;
use Illuminate\Http\Request;

/**
 * Class ProjectRepository.
 */
class ProjectRepository implements ProjectRepositoryInterface
{
    private $languageRepository;
    private $locationRepository;
    private $projectAttendingRepository;

    public function construct(
        LanguageRepository $languageRepository,
        LocationRepository $locationRepository,
        ProjectAttendingRepository $projectAttendingRepository
    )
    {
        $this->languageRepository = $languageRepository;
        $this->locationRepository = $locationRepository;
        $this->projectAttendingRepository = $projectAttendingRepository;
    }

    public function model()
    {
        return Project::class;
    }

    public function validateNewProject(Request $request)
    {
        $request->validate([
            'project_new_name' => 'required|unique:projects,name|max:191',
            'project_new_description' => 'required|max:191',
            'project_new_sector' => 'required|max:191',
            'project_new_start_date' => 'required|date|after:yesterday',
            'project_new_end_date' => 'required|date|after_or_equal:project_new_start_date',
            'project_new_location' => 'required',
            'project_new_language' => 'required',
            'project_new_team' => 'required|unique:teams,name|max:191',
        ], [
            'project_new_name.unique' => 'Project name already taken',
            'project_new_start_date.after' => 'The project start date must be today or a date after today.',
            'project_new_end_date.after_or_equal' => 'The project end date must be equal to start date or later.',
            'project_new_team.unique' => 'Team name must be unique.',
        ]);
    }

    public function createNewProject($name, $description, $sector, $start_date, $end_date, $location, $language)
    {
        $project = new Project;
        $project->name = $name;
        $project->description = $description;
        $project->sector = $sector;
        $project->start_date = $start_date;
        $project->end_date = $end_date;
        $project->loc_id = $this->locationRepository->findOrCreateLocation($location);
        $project->lang_id = $this->languageRepository->addLanguage($language);
        $project->save();

        return $project;
    }

    public function addOpenPositions($openPositions, $project, $project_lead, $lazybot, $team_id)
    {
        $this->projectAttendingRepository->addNewProjectAttending("Lead", $project, $project_lead);
        foreach ($openPositions as $openPosition) {
            $this->projectAttendingRepository->addNewProjectAttending($openPosition, $project, $lazybot);
        }

        return Project_Attending::where('team_id', $team_id)->get();
    }

    public function elementExists($array, $element)
    {
        foreach ($array as $el) {
            if ($el == $element) {
                return true;
            }
        }
        return false;
    }

    public function getUserProjects($user)
    {
        $project_attendings = Project_Attending::where('user_id', $user->id)->get();
        $user_teams = array();

        foreach ($project_attendings as $project_attending) {
            array_push($user_teams, $project_attending->team_id);
        }

        $projects = array();
        $teams = array();
        foreach ($user_teams as $user_team) {
            if (!$this->elementExists($teams, $user_team)) {
                $team = Team::where('id', $user_team)->get()->first();
                array_push($projects, $team->project);
                array_push($teams, $user_team);
            } else {
                continue;
            }
        }
        return $projects;
    }

    public function getProjectsTeams($project)
    {
        $team_id = $project->team->id;
        $team = Project_Attending::where('team_id', $team_id)->get();
        return $team;
    }

    public function getProjectOrganizer($project)
    {
        $team = $project->team;
        $role = Role::where('title', 'lead')->get()->first()->id;
        $project_attending = Project_Attending::where('team_id', $team->id)->where('role_id', $role)->get()->first();
        return $project_attending->user_id;
    }

    public function returnAttendies($attendies)
    {
        $users = array();
        foreach ($attendies as $attendy) {
            $user = User::where('id', $attendy->user_id)->first();
            array_push($users, $user);
        }
        return $users;
    }

    public function deleteProjectData($project_id){
        $project = Project::where('id', $project_id)->first();
        if($project!=null){
            $team = $project->team != null ? $project->team : null;
            $attendings = $team!=null ? Project_Attending::where('team_id', $team->id)->get() : null;
            foreach ($attendings as $attending){
                $attending->delete();
            }
            $team->delete();
            $reviews=$project->reviews;
            if(!$reviews==null){
                foreach($reviews as $review){
                    $review ->delete();

                }
            }
            $applications=$project->project_applications;
            if($applications!=null){
                foreach($applications as $application){
                    $application->delete();
                }
            }
            $project->delete();
        }




    }
}
