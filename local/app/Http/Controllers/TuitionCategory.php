<?php

namespace App\Http\Controllers;

use App\tuition_category;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Tuition\CategoryEvents;
use Illuminate\Support\Facades\Route;

class TuitionCategory extends Controller
{

    protected $category;
    protected $CurrentUri;

    public function __construct(CategoryEvents $category)
    {
        $this->category = $category;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminTuitionCategories(){

        return redirect("admin/tuition/categories")->with('status','Category Save Successfully!');
    }

    public function LoadCategories(Request $request){

        $filters = Request::all();

        $categories = DB::table('tuition_categories')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->category->load($categories,'',$last_filters);


        }else{
            $response = $this->category->load($categories,'',$filters);
        }

        $categories = $response['records'];
        $offset = $response['offset'];
        $count_categories = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('tuition_category',compact('categories','offset','count_categories','perpage_record','pagesize','current_route'));

    }



    public function CategoryView(){
        $status = 'add';
        return view('category_save',compact('status'));

    }

    public function CategoryEditView($cid){
        $status = 'update';
        $Category = DB::table('tuition_categories')->where('id',$cid)->get();
        $category = $Category[0];
        return view('category_save',compact('status','category'));

    }

    public function DeleteCategory($id){
        $this->category->delete(new tuition_category(),$id);
        return redirect("admin/tuition/categories")->with('status','Record Deleted Successfully!');

    }

    public function CategorySave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $category = Request::all();
        $redirect =  $this->category->save($category,new tuition_category());
        return response()->json(['success' =>$redirect]);

    }

}
