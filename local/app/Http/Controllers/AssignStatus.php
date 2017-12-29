<?php

namespace App\Http\Controllers;

use App\AssignmentStatus;
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

class AssignStatus extends Controller
{
    protected $assign;
    protected $CurrentUri;

    public function __construct(EventTeacher $assign)
    {
        $this->assign = $assign;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function LoadTuitionAssignStatus(Request $request){

        $filters = Request::all();

        $astatus = DB::table('tuition_assignment_status')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->assign->load($astatus,'assignstatus',$last_filters);


        }else{
            $response = $this->assign->load($astatus,'assignstatus',$filters);
        }


        $astatus = $response['records'];
        $offset = $response['offset'];
        $count_assign = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;


        return view('assignstatus',compact('astatus','offset','count_assign','perpage_record','pagesize','current_route'));

    }



    public function StatusView(){
        $status = 'add';
        return view('astatus_save',compact('status'));

    }

    public function StatusEditView($cid){
        $status = 'update';
        $astatus = DB::table('tuition_assignment_status')->where('id',$cid)->get();
        $astatus = $astatus[0];
        return view('astatus_save',compact('astatus','status'));

    }

    public function DeleteStatus($id){

        $astatus = AssignmentStatus::find($id);

        try{

            $astatus->delete();

            return redirect('admin/assignstatus')->with('status','Record Deleted Successfully!');

        }catch ( \Illuminate\Database\QueryException $e) {

            return redirect('admin/assignstatus')->with('warning', $e->errorInfo[2]);
        }

    }

    public function StatusSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);

        //get form data
        $astatus = Request::all();
        $name = $astatus['name'];
        $id = $astatus['id'];
        $status = $astatus['status'];

        $assign_obj= new AssignmentStatus();
        $assign_obj->name = $name;
        $assign_obj->created_at = date('Y-m-d H:i:s', time());
        $assign_obj->updated_at = date('Y-m-d H:i:s', time());

        if($status=='add'){
            //print_r($astatus); die();
            $assign_obj->save();

        }else{

            $assign_obj =  AssignmentStatus::find($id);
            $assign_obj->name = $name;
            $assign_obj->updated_at = date('Y-m-d H:i:s', time());
            $assign_obj->save();

        }

        if(!empty($astatus['submitbtnValue']) && $astatus['submitbtnValue'] == 'saveadd'){

            return response()->json(['success' =>'saveandadd']);

        }else{

            return response()->json(['success' =>'save']);

        }


    }
}
