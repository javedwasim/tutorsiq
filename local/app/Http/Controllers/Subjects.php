<?php

namespace App\Http\Controllers;

use App\Subject;
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

class Subjects extends Controller
{
    protected $subject;
    protected $CurrentUri;

    public function __construct(EventTeacher $subject)
    {
        $this->subject = $subject;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminSjubets(){

        return redirect("admin/subjects")->with('status','Subject Save Successfully!');
    }

    public function LoadSubjects(Request $request){

        $filters = Request::all();

        $subjects = DB::table('subjects')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->subject->load($subjects,'subjects',$last_filters);


        }else{
            $response = $this->subject->load($subjects,'subjects',$filters);
        }


        $subjects = $response['records'];
        $offset = $response['offset'];
        $count_subjects = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('subject',compact('subjects','offset','count_subjects','perpage_record','pagesize','current_route'));

    }



    public function SubjectView(){
        $status = 'add';
        return view('subject_save',compact('status'));

    }

    public function SubjectEditView($sid){
        $status = 'update';
        $subject = DB::table('subjects')->where('id',$sid)->get();
        $subject = $subject[0];
        return view('subject_save',compact('status','subject'));

    }

    public function DeleteSubject($id){

        $this->subject->delete(new Subject(),$id);
        return redirect("admin/subjects")->with('deleted','Subject Deleted Successfully!');

    }

    public function SubjectSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $Subject = Request::all();
        $redirect =  $this->subject->save($Subject,new Subject());
        return response()->json(['success' =>$redirect]);

    }

}
