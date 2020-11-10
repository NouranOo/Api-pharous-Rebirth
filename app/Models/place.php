<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;


class place extends Model
{

     protected $table="places";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rate' ,'city_id','photo','video'
    ];
    public function city(){
        return $this->belongsTo('App\Models\city','city_id');
    }




}
