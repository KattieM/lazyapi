<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable=['name'];

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function events(){
        return $this->hasMany('App\Event');
    }
}
