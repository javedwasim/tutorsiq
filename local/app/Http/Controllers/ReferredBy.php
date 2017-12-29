<?php

namespace App\Http\Controllers;

use App\referrer;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Tuition\TuitionEvents;
use Illuminate\Support\Facades\Route;

class ReferredBy extends Controller
{
    protected $referrer;
    protected $CurrentUri;

    public function __construct(TuitionEvents $referrer)
    {
        $this->referrer = $referrer;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminReferrers(){

        return redirect("admin/referrers")->with('status','Referrer Save Successfully!');
    }

    public function LoadReferrs(Request $request){

        $filters = Request::all();

        $referrers = DB::table('referrers')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->referrer->loads($referrers,'referrers',$last_filters);


        }else{

            $response = $this->referrer->loads($referrers,'referrers',$filters);
        }


        $referrers = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('referrer',compact('referrers','offset','count','perpage_record','pagesize','current_route'));

    }



    public function ReferrerView(){
        $status = 'add';
        return view('referrer_save',compact('status'));

    }

    public function ReferrerEditView($id){

        $status = 'update';
        $referrers = DB::table('referrers')->where('id',$id)->get();
        $referrer = $referrers[0];
        return view('referrer_save',compact('status','referrer'));

    }

    public function DeleteReferrer($id){

        $this->referrer->delete(new referrer(),$id);
        return redirect("admin/referrers")->with('status','Record Deleted Successfully!');


    }

    public function ReferrerSave(Request $request){

        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $referrer = Request::all();
        $redirect =  $this->referrer->save_referrer($referrer,new referrer());
        return response()->json(['success' =>$redirect]);

    }
}
