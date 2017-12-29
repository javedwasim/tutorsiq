<?php

namespace App\Http\Controllers;

use App\Label;
use App\teacher_label;
use App\tuition_label;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\TeacherLabels;
use Illuminate\Support\Facades\Route;

class Labels extends Controller
{
    protected $label;
    protected $CurrentUri;

    public function __construct(TeacherLabels $label)
    {
        $this->label = $label;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminLabels(){

        return redirect("admin/labels")->with('status','Label Save Successfully!');
    }

    public function LoadLabels(Request $request){

        $filters = Request::all();

        $labels = DB::table('tlabels')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->label->load($labels,'labels',$last_filters);


        }else{
            $response = $this->label->load($labels,'labels',$filters);
        }


        $labels = $response['records'];
        $offset = $response['offset'];
        $count_labels = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('label',compact('labels','offset','count_labels','perpage_record','pagesize','current_route'));

    }



    public function ClassView(){
        $status = 'add';
        return view('label_save',compact('status'));

    }

    public function LabelEditView($id){
        $status = 'update';
        $Label = DB::table('tlabels')->where('id',$id)->get();
        $label = $Label[0];
        return view('label_save',compact('status','label'));

    }

    public function DeleteLabel($id){
        $this->label->delete(new Label(),$id);
        return redirect("admin/labels")->with('status','Record Deleted Successfully!');

    }

    public function DeleteTeacherLabel($id){

        $this->label->delete_teacher_label(new teacher_label(),$id);
        return redirect("admin/teachers")->with('status','Record Deleted Successfully!');

    }

    public function DeleteTuitionLabel(){

        $request = Request::all();
        $id = $request['id'];
        $this->label->delete_tuition_label(new tuition_label(),$id);
        return response()->json(['success' => true]);

    }

    public function LabelSave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $label = Request::all();
        $redirect =  $this->label->save($label,new Label());
        return response()->json(['success' =>$redirect]);

    }

}
