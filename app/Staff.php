<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tbl_Staffs';



    public function Approval()
    {
        return $this->belongsTo('App\ApprovalPurchase', 'id', 'boxID');
    }
    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brandID', 'ID')->select(array('ID', 'name'));
    }
    public function styles()
    {
        return $this->belongsTo('App\StaffCategory', 'styleID', 'id')->select(array('id', 'name'));
    }
    public function ApprovalID()
    {
        return $this->belongsTo('App\ApprovalPurchase', 'id', 'boxID')->select(array('boxID'));
    }






}
