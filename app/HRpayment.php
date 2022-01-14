<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRpayment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'amount','HRsID','remarks','paymentMethod','boxID','statusPaid'
    ];


    public function HR()
    {
        return $this->belongsTo('App\HR', 'HRsID', 'id')->select(array('id', 'name','total_balance','paid'));
    }
    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod', 'paymentMethod', 'ID')->select(array('ID', 'Type'));
    }



}
