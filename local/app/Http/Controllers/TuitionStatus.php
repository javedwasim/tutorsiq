<?php

namespace App\Http\Controllers;

use App\TStatus;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\Route;

class TuitionStatus extends Controller
{
    protected $status;
    protected $CurrentUri;

    public function __construct(EventTeacher $status)
    {
        $this->status = $status;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminTuitionStatus(){

        return redirect("admin/tuition/status")->with('status','Status Save Successfully!');
    }

    public function LoadTuitionStatus(Request $request){

        $filters = Request::all();

        $tstatus = DB::table('tution_status')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->status->load($tstatus,'status',$last_filters);


        }else{
            $response = $this->status->load($tstatus,'status',$filters);
        }


        $tstatus = $response['records'];
        $offset = $response['offset'];
        $count_status = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('status',compact('tstatus','offset','count_status','perpage_record','pagesize','current_route'));

    }



    public function StatusView(){
        $status = 'add';
        return view('status_save',compact('status'));

    }

    public function StatusEditView($id){
        $status = 'update';
        $tstatus = DB::table('tution_status')->where('id',$id)->get();
        $t_status = $tstatus[0];
        return view('status_save',compact('status','t_status'));

    }

    public function DeleteStatus($id){

        $this->status->delete(new TStatus(),$id);
        return redirect("admin/tuition/status")->with('status','Record Deleted Successfully!');

    }

    public function TuitionStatusSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',
            'color' => 'required',

        ]);

        //get form data
        $Tstatus = Request::all();
        $redirect =  $this->status->save($Tstatus,new TStatus());
        return response()->json(['success' =>$redirect]);

    }
}
