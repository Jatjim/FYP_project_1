<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Staff;
use App\StaffCategory;
use App\ApprovalPurchase;
use App\HR;
use App\HRpayment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use PDF;

class ApprovalController extends Controller
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
        $Staffs = StaffCategory::all();
        $brand = Brand::all();
        return view('add_purchase',compact('Staffs'),compact('brand'));

    }
    public function save_purchase(Request $request) {
        $input = $request->all();
        $max = sizeof($input['data1']);
        $totalQT=0;
        $totalPrice=0;


        for($i = 0; $i < $max-1;$i++)
        {
            $data = new Staff();
            $data['pName'] = $input['data1'][$i]['Staff'];
            $data['price'] = $input['data1'][$i]['price'];
            $data['quantity'] = $input['data1'][$i]['quantity'];

            $data['availableQty'] = $input['data1'][$i]['quantity'];

            $data['color'] = $input['data1'][$i]['color'];
            $data['size'] =  $input['data1'][$i]['size'];
            $data['boxID']=  $input['data1'][$max-1]['boxID'];
            $data['brandID'] =  $input['data1'][$i]['Brand'];
            $data['styleID'] =  $input['data1'][$i]['style'];
            $data->save();

            $totalQT=$totalQT+$input['data1'][$i]['quantity'];
            $totalPrice=$totalPrice+(ceil($input['data1'][$i]['price']*$input['data1'][$i]['quantity']));

        }
        $data2 = array();

        $data2['availableApproval']=$totalQT;
        $data2['HRID']=$input['data1'][$max-1]['HR'];
        $data2['boxID']=$input['data1'][$max-1]['boxID'];
        $data2['price']=$totalPrice;
        DB::table('purchase')->insert($data2);


        $HRID = $input['data1'][$max-1]['HR'];




        $HRB = HR::find($HRID);
        $HR = HR::where('id', $HRID)
            ->update(['total_balance' => $HRB->total_balance+ $totalPrice]);


        Session::put('message', 'Purchase Successfully !');
        //return Redirect::to('add-Staff');

    }
    public function save_purchaseOLD(Request $request) {
        $input = $request->all();
        $max = sizeof($input['data1']);
        $totalQT=0;
        $totalPrice=0;


        for($i = 0; $i < $max-1;$i++)
        {
            $data = new Staff();
            $data['pName'] = $input['data1'][$i]['Staff'];
            $data['price'] = $input['data1'][$i]['price'];
            $data['quantity'] = $input['data1'][$i]['quantity'];

            $data['availableQty'] = $input['data1'][$i]['quantity'];

            $data['color'] = $input['data1'][$i]['color'];
            $data['size'] =  $input['data1'][$i]['size'];
            $data['boxID']=  $input['data1'][$max-1]['boxID'];
            $data['brandID'] =  $input['data1'][$i]['Brand'];
            $data['styleID'] =  $input['data1'][$i]['style'];

            $data->save();

            $totalQT=$totalQT+$input['data1'][$i]['quantity'];
            $totalPrice=$totalPrice+(ceil($input['data1'][$i]['price']*$input['data1'][$i]['quantity']));

        }

        $dataPurhcase = ApprovalPurchase::where('boxID',$input['data1'][$max-1]['boxID'])->get();
        $data2 = array();

        $data2['availableApproval']=$totalQT+$dataPurhcase[0]->availableApproval;
        $data2['price']=$totalPrice+$dataPurhcase[0]->price;
        $data2['statusPaid']=-1;
        ApprovalPurchase::where('boxID',$input['data1'][$max-1]['boxID'])->update($data2);

        $HRID = $dataPurhcase[0]->HRID;

        $HRB = HR::find($HRID);
        $HR = HR::where('id', $HRID)
            ->update(['total_balance' => $HRB->total_balance+ $totalPrice]);

       return $data2;

    }
    public function view()
    {
        $Staffs = ApprovalPurchase::with('HR')->orderBy('boxID','desc')->get();

       // return $Staffs;

        return view('Staff',compact('Staffs'));

    }
    public function pdf($ID){
        $Invoice = ApprovalPurchase::with(['Staffs.styles','HR'])->where('boxID',$ID)->get();
        $paymenthist= HRpayment::where('boxID',$ID)->sum('amount');
        //return view("PDF.pdfApproval",compact(['Invoice','paymenthist']));

        $pdf = PDF::loadView('PDF.pdfApproval',compact(['Invoice','paymenthist']));
        return $pdf->stream('Purchase_invoice_'.$ID.'.pdf');
    }
    public function viewDetails($ID)
    {
        $Staffs = Staff::where('boxID',$ID)->with(['brand','styles'])->get();

         //return $Staffs;

        return $Staffs;

    }
    public function updateStaffDetails(Request $request)
    {
        $input = $request->all();

        //  return $input['data'];

            $data = array();
            $data['availableQty'] = $input['data']['saleQuantity'];
            $data['quantity'] = $input['data']['Purchase'];
            $data['price'] = $input['data']['salePrice'];
            $data['color'] = $input['data']['color'];
            $data['size'] = $input['data']['size'];
            $data['styleID'] = $input['data']['style'];
            $data['pName'] = $input['data']['pName'];

            $total = ceil($input['data']['Purchase'] * $input['data']['salePrice']);

            $totalOLD = ceil($input['data']['oldQty'] * $input['data']['oldPrice']);


            Staff::where('ID', $input['data']['pID'])->update($data);

            $dataPurchase = ApprovalPurchase::where('boxID',$input['data']['invoiceID'])->get();
            //return $dataPurchase[0]->price;
            if($totalOLD>=$total){
            ApprovalPurchase::where('boxID', $input['data']['invoiceID'])
            ->update(['price' => $dataPurchase[0]->price - ($totalOLD- $total),
            'availableApproval' => $dataPurchase[0]->availableApproval - ($input['data']['oldQty']-$input['data']['Purchase'])]);
            }
            else{
                ApprovalPurchase::where('boxID', $input['data']['invoiceID'])
                    ->update(['price' => $dataPurchase[0]->price - ($totalOLD- $total),'statusPaid'=>-1,
                        'availableApproval' => $dataPurchase[0]->availableApproval - ($input['data']['oldQty']-$input['data']['Purchase'])]);
            }

            $HR = HR::find($dataPurchase[0]->HRID);
            HR::where('id', $dataPurchase[0]->HRID)
                ->update(['total_balance' => $HR->total_balance - ($totalOLD - $total)]
                );


            Session::put('message', 'Staff Invoice#' . $input['data']['invoiceID'] . ' Updated Successfully!');



    }


}
