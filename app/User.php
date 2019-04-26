<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'username', 'email', 'password', 'position', 'sector', 'bio', 'join_date', 'status', 'strength'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function generateToken(){
        $this->remember_token = Str::random(60);
        $this->save();
        return $this->remember_token;
    }

    public function experiences(){
        return $this->hasMany('App\Experience');
    }

    public function event_attendings(){
        return $this->hasMany('App\Event_Attending');
    }

    public function project_attendings(){
        return $this->hasMany('App\Project_Attending');
    }
}
