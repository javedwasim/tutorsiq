<?php

namespace App\Http\Controllers;

use App\Institute;
use App\teacher_label;
use App\tuition_label;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\TeacherInstitutes;
use Illuminate\Support\Facades\Route;

class Institutes extends Controller
{

    protected $institute;
    protected $CurrentUri;

    public function __construct(TeacherInstitutes $institute)
    {
        $this->institute = $institute;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminInstitutes(){

        return redirect("admin/institutes")->with('status','Institute Save Successfully!');
    }

    public function LoadInstitutes(Request $request){

        $filters = Request::all();

        $institutes = DB::table('institutes')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->institute->load($institutes,'institutes',$last_filters);


        }else{
            $response = $this->institute->load($institutes,'institutes',$filters);
        }


        $institutes = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('institute',compact('institutes','offset','count','perpage_record','pagesize','current_route'));

    }



    public function InstituteView(){
        $status = 'add';
        return view('institute_save',compact('status'));

    }

    public function InstituteEditView($id){

        $status = 'update';
        $institute = DB::table('institutes')->where('id',$id)->get();
        $institute = $institute[0];
        return view('institute_save',compact('status','institute'));

    }

    public function DeleteInstitute($id){

        $this->institute->delete(new Institute(),$id);
        return redirect("admin/institutes")->with('status','Record Deleted Successfully!');

    }

    public function DeleteTeacherLabel($id){

        $this->label->delete_teacher_label(new teacher_label(),$id);
        return redirect("admin/teachers")->with('status','Record Deleted Successfully!');

    }

    public function DeleteTuitionLabel($id){

        $this->label->delete_tuition_label(new tuition_label(),$id);
        return redirect("admin/tuitions")->with('status','Record Deleted Successfully!');

    }

    public function InstituteSave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $label = Request::all();
        $redirect =  $this->institute->save($label,new Institute());
        return response()->json(['success' =>$redirect]);

    }

}
