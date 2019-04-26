<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable=['name'];

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function events(){
        return $this->hasMany('App\Event');
    }
}
