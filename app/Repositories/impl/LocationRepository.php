<?php

namespace App\Repositories;

use App\Location;

/**
 * Class LocationRepository.
 */
class LocationRepository implements LocationRepositoryInterface
{
    public function model()
    {
        return Location::class;
    }

    public function findOrCreateLocation($name)
    {
        $location = Location::where('name', $name)->get();
        if ($location->first()) {
            $id = $location->first()->id;
        } else {
            $location = new Location;
            $location->name = $name;
            $location->save();
            $id = $location->id;
        }
        return $id;
    }
}
