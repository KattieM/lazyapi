<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=['name', 'description', 'sector', 'start_date', 'end_date'];

    public function language(){
        return $this->belongsTo('App\Language');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function team(){
        return $this->hasOne('App\Team');
    }
}
