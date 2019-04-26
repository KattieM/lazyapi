<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable=['name', 'description', 'date', 'time'];

    public function event_attendings(){
        return $this->hasMany('App\Event_Attending');
    }

    public function language(){
        return $this->belongsTo('App\Language');
    }
    public function location(){
        return $this->belongsTo('App\Location');
    }


}
