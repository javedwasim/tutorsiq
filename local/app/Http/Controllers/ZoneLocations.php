<?php

namespace App\Http\Controllers;

use App\Zone;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\Route;



class ZoneLocations extends Controller
{
    protected $zone;
    protected $CurrentUri;

    public function __construct(EventTeacher $zone)
    {
        $this->zone = $zone;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminZones(){

        return redirect("admin/zones")->with('status','Zone Save Successfully!');
    }

    public function LoadZones(Request $request){

        $filters = Request::all();

        $zones = DB::table('zones')
                    ->orderBy('id')
                    ->get();


        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->zone->load($zones,'zones',$last_filters);


        }else{
            $response = $this->zone->load($zones,'zones',$filters);
        }


        $zones = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('zone',compact('zones','offset','count','perpage_record','pagesize','current_route','filters'));

    }



    public function ZoneView(){

        $status = 'add';
        return view('zone_save',compact('status'));

    }

    public function ZoneEditView($id){

        $status = 'update';
        $zone = DB::table('zones')->where('id',$id)->get();
        $zone = $zone[0];
        return view('zone_save',compact('status','zone','zones'));

    }

    public function DeleteZone($id){

        $Zone = Zone::find($id);

        try{

            $Zone->delete();

            return redirect('admin/zones')->with('status','Record Deleted Successfully!');

        }catch ( \Illuminate\Database\QueryException $e) {

            return redirect('admin/zones')->with('warning', $e->errorInfo[2]);
        }

    }

    public function ZoneSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);


        //get form data
        $zone = Request::all();
        $name           =   $zone['name'];
        $description    =   $zone['description'];
        $id             =   $zone['id'];
        $status         =   $zone['status'];


        if($status=='add'){

            $obj= new Zone();
            $obj->name = $name;
            $obj->description = $description;
            $obj->created_at = date('Y-m-d H:i:s', time());
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();


        }else{

            $obj =  Zone::find($id);
            $obj->name = $name;
            $obj->description = $description;
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if(!empty($zone['submitbtnValue']) && $zone['submitbtnValue'] == 'saveadd'){

            return response()->json(['success' =>'saveandadd']);

        }else{

            return response()->json(['success' =>'save']);

        }


    }
}
