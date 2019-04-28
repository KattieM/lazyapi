<?php

namespace App\Repositories;

use App\Project;
use App\Project_Attending;
use App\Team;

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
    ) {
        $this->languageRepository = $languageRepository;
        $this->locationRepository = $locationRepository;
        $this->projectAttendingRepository = $projectAttendingRepository;
    }

    public function model()
    {
        return Project::class;
    }

    public function createNewProject($project, $name, $description, $sector, $start_date, $end_date, $location, $language)
    {
        $project->name = $name;
        $project->description = $description;
        $project->sector = $sector;
        $project->start_date = $start_date;
        $project->end_date = $end_date;
        $project->loc_id = $this->locationRepository->findOrCreateLocation($location);
        $project->lang_id = $this->languageRepository->addLanguage($language);
        $project->save();
    }

    public function addOpenPositions($openPositions, $project, $project_lead, $lazybot)
    {
        $this->projectAttendingRepository->addNewProjectAttending("Lead", $project, $project_lead);
        foreach ($openPositions as $openPosition) {
            $this->projectAttendingRepository->addNewProjectAttending($openPosition, $project, $lazybot);
        }
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
}
