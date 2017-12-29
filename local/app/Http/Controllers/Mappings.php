<?php

namespace App\Http\Controllers;

use App\class_subject_mapping;
use Auth;
use Mail;
use Request;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Illuminate\Support\Facades\Route;


class Mappings extends Controller
{

    protected $CurrentUri;
    protected $teacher;

    public function __construct(EventTeacher $teacher)
    {
        $this->teacher = $teacher;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function MappingsView(){

        $classes = DB::table('classes')->get();
        $subjects = DB::table('subjects')->get();
        $class_id = session('class_id');

        //if classid set then load screen with mapping subjects.
        if(isset($class_id) && $class_id>0 ){

            $mappings = DB::table('class_subject_mappings')
                            ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
                            ->join('classes', 'class_subject_mappings.class_id', '=', 'classes.id')
                            ->select('class_subject_mappings.*', 'subjects.id as sid', 'subjects.name as name','classes.name as c_name')
                            ->where('class_id',$class_id)->get();

        }else{

            $mappings = '';
            $class_id = '';

        }

        $current_route = $this->CurrentUri;

        return view('mapping', compact('classes','subjects','current_route','mappings','class_id'));

    }

    public function MappingsSave(Request $request){

        //get form data
        $Mappings = Request::all();
        $class = $Mappings['classes'];
        $subject = $Mappings['subjects'];

        $mapping_obj= new class_subject_mapping();
        $mapping_obj->class_id = $class;
        $mapping_obj->subject_id = $subject;
        $mapping_obj->created_at = date('Y-m-d H:i:s', time());
        $mapping_obj->updated_at = date('Y-m-d H:i:s', time());

        $mapping_obj->save();

        $mapping_id = $mapping_obj->id;

        if(isset($mapping_id)){

            $mapping_detail =  class_subject_mapping::find($mapping_id);
            $class_id = $mapping_detail->class_id;
            $subject_id = $mapping_detail->subject_id;

            $classname = DB::table('classes')->where('id',$class_id)->get();
            $subjectname = DB::table('subjects')->where('id',$subject_id)->get();

            $class_name =  $classname[0]->name;
            $subject_name = $subjectname[0]->name;

            $mappings = DB::table('class_subject_mappings')
                        ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
                        ->select('class_subject_mappings.*', 'subjects.id as sid', 'subjects.name as name')
                        ->where('class_id',$class_id)->get();



            return response()->json(['success' =>true,'classname'=>$class_name,'subjectname'=>$mappings]);
            //return response()->json(['success' =>true,'classname'=>$class_name,'subjectname'=>$subject_name,'mappingid'=>$mapping_id]);
        }

    }

    public function DeleteMapping($id){

        $Mapping = class_subject_mapping::find($id);
        $class_id = $Mapping['class_id'];


        try{

            $Mapping->delete();
            //redirect with classid to load class mapping subjects.
            return redirect('admin/class/subject/mappings')->with('class_id',$class_id);

        }catch ( \Illuminate\Database\QueryException $e) {

            return redirect('admin/classes')->with('warning', $e->errorInfo[2]);
        }

    }

    public function LoadMappings(Request $request){

        $class = Request::all();
        $class_id = $class['classid'];
        $teacherid = $class['teacherid'];

        $classes = DB::table('classes')->where('id',$class_id)->get();
        $class_name =  $classes[0]->name;
        //if All subjects already mapped with selected grade then return true
        $result = $this->teacher->getGradeSubjectsMappings($class_id,$teacherid);
        $mappings =   $result['mappings'];
        $flag =   $result['flag'];

        return view('teachers.grade_subject_mapping', compact('mappings','classes','flag'));

    }

    public function LoadMappingsForAdmin(Request $request){

        $class = Request::all();
        $class_id = $class['classid'];
        $teacherid = $class['teacherid'];

        $classes = DB::table('classes')->where('id',$class_id)->get();
        $class_name =  $classes[0]->name;
        //if All subjects already mapped with selected grade then return true
        $result = $this->teacher->getGradeSubjectsMappings($class_id,$teacherid);
        $mappings =   $result['mappings'];
        $flag =   $result['flag'];

        return view('grade_subject_mapping', compact('mappings','classes','flag'));

    }

    public function LoadGradeSubjects(Request $request){

        $class = Request::all();
        $result = $this->teacher->loadGradeSubjectsMappings($class);
        $class_name = $result['classname'];
        $mappings = $result['subjectname'];
        //print_r($mappings); die();
        return response()->json(['success' =>true,'classname'=>$class_name,'subjectname'=>$mappings]);
    }


}
