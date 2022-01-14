<?php

namespace App\Http\Controllers;

use App\Brand;
use App\ApprovalPurchase;
use App\HR;
use App\HRpayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

class HRController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $HR = HR::all();


        return view('HR',compact('HR'));

    }
    public function view_HR($ID) {
        $HR = HR::find($ID);
        return $HR;
    }
    public function update_info(Request $request) {
        $data = array();
        if($request->cat!=-1)
        $data['type'] = $request->cat;

        $data['contact'] = $request->phone;
        $data['Adress'] = $request->adress;
        $data['name'] = $request->name;
        $data['total_balance'] = $request->balance;
        $data['paid'] = $request->paid;

        HR::where('id',$request->ID)
        ->update($data);



        Session::put('message','Save information successfully');
        return Redirect::back();
    }
    public function published_HR($ID) {

        HR::find($ID)->update(['publication_status' => 1]);

        return Redirect::to('HR');
    }

    public function unpublished_HR($ID) {
        HR::find($ID)->update(['publication_status' => 0]);

        return Redirect::to('HR');
    }

    public function save_HR(Request $request) {

            $data = new HR();

            if($request->cat==-1) {
                Session::put('info', 'Error! please select HR type!');
                return Redirect::to('HR');

            }

            $data['type'] = $request->cat;
            $data['name'] = $request->name;
            $data['total_balance'] = $request->balance;
            $data['paid'] = $request->paid;
            $data['contact'] = $request->phone;
            $data['Adress'] = $request->adress;
            $data['publication_status'] = 1;

            $data->save();


             Session::put('message', 'Save Information Successfully !');
            return Redirect::to('HR');

    }
    public function save_HRAJAX(Request $request) {

        $data = new HR();

        $data['type'] = $request->cat;
        $data['name'] = $request->name;
        $data['total_balance'] = $request->balance;
        $data['paid'] = $request->paid;
        $data['contact'] = $request->phone;
        $data['Adress'] = $request->address;
        $data['publication_status'] = 1;

        $data->save();
    }
    public function getAllHR(){
        $all_published_HR = \App\HR::all()->where('publication_status',1);
        return $all_published_HR;

    }
    public function paymentDetails($ID)
    {
        $HRPayment =  HRpayment::where('boxID',$ID)->with('HR')->with('paymentMethod')->get();
       return $HRPayment;


    }
    public function viewPayment()
    {
        $Approval = ApprovalPurchase::with('HR')->orderBy('statusPaid','asc')->get();

        // return $Staffs;

        return view('HRPayment',compact('Approval'));

    }
    public function save_HR_payment(Request $request) {


        $data = new HRpayment();

        $data['amount'] = $request->data['newpaid'];
        $data['HRsID'] = $request->data['HRID'];
        $data['paymentMethod'] = $request->data['paymentMethod'];
        $data['boxID'] = $request->data['ID'];
        if($data['remarks']!="")
        $data['remarks'] = $request->data['remarks'];

        $total_paid = $request->data['paid']+$data['amount'];

        $data->save();

        $HRB = HR::find($data['HRsID']);

        $HR = HR::where('id', $data['HRsID'])
            ->update(['paid' => $HRB->paid+ $data['amount']]);


        if($request->data['total']==$total_paid) {
            $dataApproval = array();
            $dataApproval['statusPaid'] = 0;

            ApprovalPurchase::where('boxID',$data['boxID'])->update($dataApproval);
        }
        else{
            $dataApproval = array();
            $dataApproval['statusPaid'] = -1;

            ApprovalPurchase::where('boxID',$data['boxID'])->update($dataApproval);
        }


    }




}
