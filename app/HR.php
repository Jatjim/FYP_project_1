<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HR extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey ='id';


    protected $fillable = [
        'name','publication_status','total_balance','paid','Adress','contact','type'
    ];
    public function payment()
    {
        return $this->hasMany('App\HRpayment', 'HRsID', 'id');
    }
    public function Approval()
    {
        return $this->hasMany('App\ApprovalPurchase', 'HRID', 'id');
    }
    public function ApprovalUnpaid()
    {
        return $this->hasMany('App\ApprovalPurchase', 'HRID', 'id')->where('statusPaid',-1);
    }



}
