<?php

namespace App\Http\Controllers;

use App\Location;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\Route;



class Locations extends Controller
{
    protected $locations;
    protected $CurrentUri;

    public function __construct(EventTeacher $locations)
    {
        $this->locations = $locations;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminLocations(){

        return redirect("admin/locations")->with('status','Location Save Successfully!');
    }

    public function LoadLocations(Request $request){

        $filters = Request::all();

        if(isset($filters['zone']) && ($filters['zone']!='') && ($filters['zone']!=0) ){

            $locations = DB::table('locations')
                        ->select('locations.*','zones.name as zoneName')
                        ->join('zones','zones.id','=','locations.zone_id','left outer')
                        ->where('zone_id',$filters['zone'])
                        ->orderBy('zone_id')
                        ->get();

        }else{

            $locations = DB::table('locations')
                        ->select('locations.*','zones.name as zoneName')
                        ->join('zones','zones.id','=','locations.zone_id','left outer')
                        ->orderBy('zone_id')
                        ->get();
        }


        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->locations->load($locations,'locations',$last_filters);


        }else{
            $response = $this->locations->load($locations,'locations',$filters);
        }

        $zones = DB::table('zones')->get();
        $locations = $response['records'];
        $offset = $response['offset'];
        $count_locations = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('location',compact('locations','offset','count_locations','perpage_record','pagesize','current_route','zones','filters'));

    }



    public function LocationView(){

        $status = 'add';
        $zones = DB::table('zones')->get();
        return view('location_save',compact('status','zones'));

    }

    public function LocationEditView($id){

        $status = 'update';
        $Location = DB::table('locations')->where('id',$id)->get();
        $zones = DB::table('zones')->get();
        $Location = $Location[0];
        return view('location_save',compact('status','Location','zones'));

    }

    public function DeleteLocation($id){

        $Location = Location::find($id);

        try{

            $Location->delete();

            return redirect('admin/locations')->with('status','Record Deleted Successfully!');

        }catch ( \Illuminate\Database\QueryException $e) {

            return redirect('admin/locations')->with('warning', $e->errorInfo[2]);
        }

    }

    public function LocationSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'locations' => 'required',

        ]);


        //get form data
        $Location = Request::all();
        $name = $Location['locations'];
        $id = $Location['id'];
        $zone = $Location['zone'];
        $status = $Location['status'];


        if($status=='add'){

            $Location_obj= new Location();
            $Location_obj->locations = $name;
            $Location_obj->zone_id = $zone;
            $Location_obj->created_at = date('Y-m-d H:i:s', time());
            $Location_obj->updated_at = date('Y-m-d H:i:s', time());
            $Location_obj->save();


        }else{

            $location_obj =  Location::find($id);
            $location_obj->locations = $name;
            $location_obj->zone_id = $zone;
            $location_obj->updated_at = date('Y-m-d H:i:s', time());
            $location_obj->save();

        }

        if(!empty($Location['submitbtnValue']) && $Location['submitbtnValue'] == 'saveadd'){

            return response()->json(['success' =>'saveandadd']);

        }else{

            return response()->json(['success' =>'save']);

        }


    }
}
