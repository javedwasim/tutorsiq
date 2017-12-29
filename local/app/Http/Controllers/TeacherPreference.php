<?php

namespace App\Http\Controllers;


use App\teacher_preference;
use Auth;
use Mail;
use Request;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Illuminate\Support\Facades\Route;

class TeacherPreference extends Controller
{
    protected $CurrentUri;
    protected $teacher;

    public function __construct(EventTeacher $teacher)
    {
        $this->teacher = $teacher;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function TeacherPreferenceEditView($pid)
    {

        $teacher_preferences = DB::table('teacher_preferences')->where('id', $pid)->get();
        $preferences = $teacher_preferences[0];
        $subjects = DB::table('subjects')->get();
        $classes = DB::table('classes')->get();
        $status = 'update';
        return view('tutor-preference', compact('preferences', 'status', 'subjects', 'classes'));

    }

    public function TeacherPreferenceSave(Request $request)
    {
        //get form data
        $Preference = Request::all();
        $result = $this->teacher->gradeSubjectsMappings($Preference);
        $teacherid = $result['teacherid'];
        $class_id = $result['classid'];
        //if All subjects already mapped with selected grade then return true
        $result = $this->teacher->getGradeSubjectsMappings($class_id,$teacherid);
        $mappings =   $result['mappings'];
        $flag =   $result['flag'];
        return view('teachers.grade_subject_mapping', compact('mappings','classes','flag'));

    }

    public function TeacherPreferenceSaveForAdmin(Request $request)
    {
        //get form data
        $Preference = Request::all();
        $result = $this->teacher->gradeSubjectsMappings($Preference);
        $teacherid = $result['teacherid'];
        $class_id = $result['classid'];
        //if All subjects already mapped with selected grade then return true
        $result = $this->teacher->getGradeSubjectsMappings($class_id,$teacherid);
        $mappings =   $result['mappings'];
        $flag =   $result['flag'];
        return view('grade_subject_mapping', compact('mappings','classes','flag'));

    }

    public function DeleteSubjectPreference()
    {

        $request = Request::all();
        $class_id = $request['id'];
        $teacherid = $request['teacherid'];

        try {

            $this->teacher->deleteSubjectPreferrences($class_id,$teacherid);

            return redirect('admin/teachers')->with('status', 'Record Deleted Successfully!');


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }

    public function TeacherPreferenceDelete($pid)
    {

        $newStr = explode("-", $pid);

        if (count($newStr)>1) {
            $pid = $newStr[0];
        } else {
            $pid = $pid;
        }

        $preference = teacher_preference::find($pid);
        try {
            $preference->delete();

            if(count($newStr)>1){
                return redirect('preferences')->with('status', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('status', 'Record Deleted Successfully!');
            }


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }

    public function TeacherPreferenceAddView($id)
    {

        $teacherid = $id;
        $subjects = DB::table('subjects')->get();
        $classes = DB::table('classes')->get();
        $status = 'add';

        return view('tutor-preference', compact('preferences', 'status', 'teacherid', 'subjects', 'classes'));
    }
}
