<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalPurchase extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'purchase';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'price','availableApproval','boxID','HRID'
    ];

    public function Staffs()
    {
        return $this->hasMany('App\Staff', 'boxID', 'boxID');
    }
    public function HR()
    {
        return $this->belongsTo('App\HR', 'HRID', 'id')->select(array('id', 'name','Adress','contact','total_balance','paid'));
    }

}
