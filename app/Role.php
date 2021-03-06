<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = ['title', 'project/event'];

    public function event_attendings(){
        return $this->hasMany('App\Event_Attending');
    }

    public function project_attendings(){
        return $this->hasMany('App\Project_Attending');
    }

}
