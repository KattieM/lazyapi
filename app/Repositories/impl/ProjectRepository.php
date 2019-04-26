<?php

namespace App\Repositories;

use App\Project;

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
}
