<?php

namespace App\Http\Controllers;


use App\SClasses;
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

class Classes extends Controller
{
    protected $class;
    protected $CurrentUri;

    public function __construct(EventTeacher $class)
    {
        $this->class = $class;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminClasses(){

        return redirect("admin/classes")->with('status','Grade Save Successfully!');
    }

    public function LoadClasses(Request $request){

        $filters = Request::all();

        $classes = DB::table('classes')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->class->load($classes,'classes',$last_filters);


        }else{

            $response = $this->class->load($classes,'classes',$filters);
        }


        $classes = $response['records'];
        $offset = $response['offset'];
        $count_classes = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('class',compact('classes','offset','count_classes','perpage_record','pagesize','current_route'));

    }



    public function ClassView(){
        $status = 'add';
        return view('class_save',compact('status'));

    }

    public function ClassEditView($cid){
        $status = 'update';
        $Class = DB::table('classes')->where('id',$cid)->get();
        $class = $Class[0];
        return view('class_save',compact('status','class'));

    }

    public function DeleteClass($id){
        $this->class->delete(new SClasses(),$id);
        return redirect("admin/classes")->with('status','Record Deleted Successfully!');

    }

    public function ClassSave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
       $Class = Request::all();
       $redirect =  $this->class->save($Class,new SClasses());
       return response()->json(['success' =>$redirect]);

    }
}
