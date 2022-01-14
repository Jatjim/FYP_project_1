<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffResource;
use App\Staff;
use App\StaffCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {


    }


    public function get()
    {
        $Staffs = StaffCategory::all();
        return $Staffs;

    }
    public function save(Request $request)
    {
        $style = new StaffCategory();
        $style['name'] = $request->data;
        $style->save();

    }
    public function update(Request $request)
    {
        $id = $request->data['id'];
        $name = $request->data['name'];

        StaffCategory::find($id)->update(['name' => $name]);

    }
    public function publish($ID)
    {
        StaffCategory::find($ID)->update(['status' => 1]);
        return Redirect::to('settings');

    }
    public function unpublish($ID)
    {
        StaffCategory::find($ID)->update(['status' => 0]);
        return Redirect::to('settings');

    }
    public function getStaffAll(){
        $all_published_Staff = \App\Staff::where('availableQty','!=',0)->with(['brand','styles','ApprovalID'])->get();
        return $all_published_Staff;
    }
}
