<?php

namespace App\Http\Controllers;


use App\application_status;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\Route;

class ApplicationStatus extends Controller
{
    protected $application;
    protected $CurrentUri;

    public function __construct(EventTeacher $application)
    {
        $this->application = $application;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminApplicationStatus(){

        return redirect("admin/application/status")->with('status','Application Status Save Successfully!');
    }

    public function LoadApplicationStatus(Request $request){

        $filters = Request::all();

        $applicationStatus = DB::table('application_status')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->application->load($applicationStatus,'application/status',$last_filters);


        }else{

            $response = $this->application->load($applicationStatus,'application/status',$filters);
        }


        $applicationStatus = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('application_status',compact('applicationStatus','offset','count','perpage_record','pagesize','current_route'));

    }



    public function StatusView(){
        $status = 'add';
        return view('application_status_save',compact('status'));

    }

    public function StatusEditView($id){
        $status = 'update';
        $astatus = DB::table('application_status')->where('id',$id)->get();
        $astatus = $astatus[0];
        return view('application_status_save',compact('status','astatus'));

    }

    public function DeleteStatus($id){
        $this->application->delete(new application_status(),$id);
        return redirect("admin/application/status")->with('status','Record Deleted Successfully!');

    }

    public function StatusSave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'sms_description' => 'required',

        ]);
        //get form data
        $status = Request::all();
        $redirect =  $this->application->saveApplicationStatus($status,new application_status());
        return response()->json(['success' =>$redirect]);

    }
}
