<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffCategory extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'Staffstyles';

    protected $fillable = [
        'name','status','created_at','update_at'
    ];
    public function Staffs()
    {
        return $this->hasMany('App\Staff', 'styleID', 'id');
    }



}
