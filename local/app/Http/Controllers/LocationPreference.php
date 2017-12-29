<?php

namespace App\Http\Controllers;

use App\location_preference;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;

class LocationPreference extends Controller{

    protected $location;

    public function __construct(EventTeacher $location)
    {
        $this->location = $location;
    }

    public function LoadPreferedLocation(Request $request){

        $preference = DB::table('locations')->get();
        $response = $this->location->load($preference,'locationpreference');
        $tstatus = $response['$records'];
        $offset = $response['offset'];
        $count_status = $response['count'];
        $perpage_record = $response['perpage_record'];
        return view('status',compact('tstatus','offset','count_status','perpage_record'));

    }

    public function LocationView($id){

        $teacher_id = $id;
        $status = 'add';
        $zoneLocations  = $this->location->TeacherLocations($teacher_id);
        $zones = DB::table('zones')->orderBy('id')->get();
        return view('location_preferences', compact('zoneLocations', 'status', 'teacher_id','zones','filters'));

    }

    public function StatusEditView($id){
        $status = 'update';
        $preference = DB::table('tution_status')->where('id',$id)->get();
        $t_status = $preference[0];
        return view('status_save',compact('status','t_status'));

    }

    public function DeleteZoneLocations(){

        $request= Request::all();
        $id = $request['id'];

        try {

            location_preference::where('zoneid', $id)->delete();
            return response()->json(['success' => true]);

        }catch ( \Illuminate\Database\QueryException $e) {

            return response()->json(['success' => false]);
        }

    }

    public function DeleteLocations($id){

        $newStr = explode("-", $id);

        if (count($newStr)>1) {
            $id = $newStr[0];
        } else {
            $id = $id;
        }

        $location = location_preference::find($id);

        try{

            $location->delete();

            if(count($newStr)>1){
                return redirect('locations')->with('status', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('status','Record Deleted Successfully!');
            }



        }catch ( \Illuminate\Database\QueryException $e) {

            if(count($newStr)>1){
                return redirect('locations')->with('status', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('status','Record Deleted Successfully!');
            }
        }

    }

    public function StatusPreferedLocations(Request $request){

        //get form data
        $preference = Request::all();
        $this->location->SaveLocationPreferrence($preference);
        return response()->json(['success' =>'save']);

    }

}
