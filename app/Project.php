<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=['name', 'description', 'sector', 'start_date', 'end_date'];

    public function language(){
        return $this->belongsTo('App\Language', 'lang_id');
    }

    public function location(){
        return $this->belongsTo('App\Location', 'loc_id');
    }

    public function team(){
        return $this->hasOne('App\Team');
    }
}
