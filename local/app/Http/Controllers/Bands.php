<?php

namespace App\Http\Controllers;

use App\teacher_band;
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

class Bands extends Controller{

    protected $band;
    protected $CurrentUri;

    public function __construct(EventTeacher $band)
    {
        $this->band = $band;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminTuitions(){

        return redirect("admin/tuitions")->with('status','Tuition Save Successfully!');
    }

    public function LoadBands(Request $request){

        $filters = Request::all();

        $bands = DB::table('teacher_bands')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->band->load($bands,'bands',$last_filters);


        }else{
            $response = $this->band->load($bands,'bands',$filters);
        }


        $bands = $response['records'];
        $offset = $response['offset'];
        $count_bands = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('band',compact('bands','offset','count_bands','perpage_record','pagesize','current_route'));

    }



    public function BandView(){
        $status = 'add';
        return view('band_save',compact('status'));

    }

    public function BandEditView($id){
        $status = 'update';
        $bands = DB::table('teacher_bands')->where('id',$id)->get();
        $band = $bands[0];
        return view('band_save',compact('status','band'));

    }

    public function DeleteBand($id){

        $this->band->delete(new teacher_band(),$id);
        return redirect("admin/bands")->with('status','Record Deleted Successfully!');


    }

    public function AdminBands(){

        return redirect("admin/bands")->with('status','Band Updated Successfully!');
    }

    public function BandSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',
            'display_order'=>'required'

        ]);
        //get form data
        $band = Request::all();
        $redirect =  $this->band->save_band($band,new teacher_band());
        return response()->json(['success' =>$redirect]);

    }
    
}
