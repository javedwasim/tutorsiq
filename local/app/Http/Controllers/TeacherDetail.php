<?php

namespace App\Http\Controllers;

use App\city;
use App\User;
use App\Teacher;
use App\user_has_permission;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use App\teacher_global;
use Acme\Teacher\EventTeacher;
use Illuminate\Support\Facades\Route;
use App\teacher_qualification;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class TeacherDetail extends Controller
{
    protected $teacher;
    protected $CurrentUri;

    public function __construct(EventTeacher $teacher)
    {
        $this->teacher = $teacher;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();

    }

    public function getQualificationDocument()
    {
        $request = Request::all();
        $id = $request['id'];
        $qualification = teacher_qualification::where('id',$id)->first();
        $fileName      = $qualification->degree_document;
        $teacherid     = $qualification->teacher_id;
        $pathToFile    = base_path()."/teachers/$teacherid/qualification/$fileName";


        if (File::exists($pathToFile))
        {
            return response()->json(['success' => true, 'pathToFile' => $pathToFile,'fileName'=>$fileName ]);

        }else{

            return Redirect::back()->with('status', 'File Not Found!');
        }


    }



    public function getDownLoad(){

        $request = Request::all();

        $pathToFile = $request['path'];
        $fileName = $request['filename'];

        $headers = array(
            'Content-Type: application/octet-stream',
        );



        if (File::exists($pathToFile))
        {
            return response()->download($pathToFile, $fileName, $headers);
        }else{

            return Redirect::back()->with('status', 'File Not Found!');
        }
    }

    public function showTeacherDetail()
    {

        $user = Auth::user();
        $userid = $user->id;
        $teachers = DB::table('teachers')
            ->where('user_id', $userid)->get();
        //get teacher id of current login user.
        $teacherid = $teachers[0]->id;

        return view('teacherdetails', compact('teacherid'));

    }

    public function TeacherDetails(){

        return view('teacher-details');
    }

    public function BulkEmailView(){

        $templates = DB::table("email_templates")->where("is_active","!=",1)->get();
        $email_type = 'bulk';
        $current_route = $this->CurrentUri;

        return view('bulk_email',compact('templates','email_type','current_route'));

    }

    public function GlobalList(Request $request){

        $request = Request::all();

        if(isset($request['pagesize'])){

            $page_size = $request['pagesize'];
        }else{

            $page_size='50';
        }

        $teachers = $this->teacher->getBroadCastTeacher();
        $filters = array('pagesize'=>$page_size);
        $response = $this->teacher->load($teachers,'teachers',$filters);

        $teachers = $response['records'];
        $offset = $response['offset'];
        $count_teachers = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        //get tuition id's for broadcast list.
        $contactNos = '';
        foreach ($teachers as $teacher){

            $contactNos .= $teacher->mobile1.';';
        }


        return view('teacher_global', compact('teachers','count_teachers', 'perpage_record', 'offset','pagesize','current_route','contactNos'));

    }

    public function CreateGlobalList(Request $request){

        $request = Request::all();

        //single selected tuition to be added in broadcast list
        if(isset($request['teacherid'])){
            $teacherid = $request['teacherid'];
            $teacher = teacher_global::where('teacher_id','=',$teacherid)->get()->toArray();

            if(!isset($teacher[0]['id']) ){

                $this->teacher->CreateGlobalList($teacherid);

            }
        }
        //bulk tuition to be added in broadcast list
        else{

            $ids = $request['ids'];
            $teachersids = explode(',',$ids);
            for($j=0;$j<count($teachersids);$j++){

                $teacher = teacher_global::where('teacher_id','=',$teachersids[$j])->get()->toArray();
                if(!isset($teacher[0]['id']) ){

                    $this->teacher->CreateGlobalList($teachersids[$j]);

                }
            }


        }

        return response()->json(['success' => true, 'teacher' => $teacher ]);

    }

    public function LoadTeachers(Request $request)
    {

        $filters = Request::all();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');//dd($last_filters);
            $response = $this->teacher->loads($last_filters);
            $filters = $last_filters;

        }else{

            $response = $this->teacher->loads($filters);
        }

        $teachers = $response['records'];
        $offset = $response['offset'];
        $count_teachers = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];

        $labels = DB::table('tlabels')->select('id','name')->get();

        $teacher_band = DB::table('teacher_bands')->get();
        $marital_status = DB::table('marital_status')->get();
        $genders = DB::table('gender')->get();

        $tuition_categories = DB::table('tuition_categories')->get();
        $institutes = DB::table('institutes')->get();
        $classes = DB::table('classes')->get();
        $zones = DB::table('zones')->get();

        if(isset($filters['class'])){
            $classid = $filters['class'];
            $result =  $this->GradeSubjects($classid);
            $subjects=  $result['subjects'];
        }else{
            $subjects = DB::table('subjects')->select('subjects.id as sid','subjects.name')->get();

        }

        if(isset($filters['zone'])){

            $zoneid = $filters['zone'];
            $result =  $this->ZoneLocations($zoneid);
            $locations=  $result['locations'];

        }else{
            $locations = DB::table('locations')->select('id','locations')->get();

        }

        $current_route = $this->CurrentUri;
        //dd($teachers);
        return view('teacher', compact('teachers', 'tutor', 'teacher_band', 'marital_status', 'genders', 'filters','current_route'
            , 'count_teachers', 'perpage_record', 'offset','subjects','locations','labels','pagesize','tuition_categories'
            ,'institutes','classes','zones'));

    }

    public function upload_teacher_documents($document, $path, $filename)
    {


        if (!File::exists($path)) {

            File::makeDirectory($path, 0777, true);
            $document->move($path, $filename);

        } else {
            $document->move($path, $filename);
        }
    }

    public function TeacherTuitionHistoryAddView($teacherid)
    {
        $status = 'add';
        $tuition_assign = DB::table('tuition_assign')->get();
        return view('tutor-tuitionhistory', compact('history', 'status', 'teacherid', 'tuition_assign'));
    }

    public function TeacherCNICDocs($teacherid, $docname)
    {

        $status = "CNIC";
        return view('tutor-cnic-docs', compact('status', 'teacherid', 'docname'));
    }

    public function detail(Request $request)
    {
        $teacher = Request::all();

        $teacher_id = $teacher['teacherid'];

        session()->put('teacherid', $teacher_id);

        $qualification = DB::select("call  teacher_qualifications('$teacher_id')");
        $institutes = DB::select("call  teacher_institutes('$teacher_id')");
        $preference = DB::select("call  teacher_subject_preferences('$teacher_id')");
        $tuition_history =DB::select("call  teacher_tuitionhistory('$teacher_id')");
        $locations = DB::select("call  teacher_locations('$teacher_id')");
        $labels = DB::select("call  teacher_labels('$teacher_id')");
        $tuitions_applied = DB::select("call  tuitions_applied('$teacher_id')");
        $grade_subjects = DB::select("call  teacher_grades_categories('$teacher_id')");

        return response()->json(['success' => true, 'teacherid' => $teacher_id, 'teacher_institutes' => $institutes,
                'teacher_references' => '','teacher_preference' => $preference, 'teacher_qualification' => $qualification,
                'tuitions_applied'=>$tuitions_applied, 'tuition_history' => $tuition_history,'teacher_locations'=>$locations,
                'teacher_labels'=>$labels,'grade_subjects'=>$grade_subjects]);

    }

    public function TeacherProfile()
    {
        //pass gender data to view
        $genders = DB::table('gender')->get();
        $maritals = DB::table('marital_status')->get();
        $teacher_band = DB::table('teacher_bands')->get();
        $teacher_detail = '';
        $labels = DB::table('tlabels')->get();
        $tuition_categories = DB::table('tuition_categories')->get();
        $instututes = DB::table('institutes')->get();
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();
        $dob='';

        $teacher_labels = array();
        $tuitioncategories = array();
        $preferredinstitute = array();

        return view('tutorprofile', compact('genders', 'maritals', 'teacher_detail', 'teacher_band','provinces','cities','dob',
                    'preferredinstitute', 'labels','teacher_labels','tuition_categories','tuitioncategories','instututes'));

    }

    public function EidtTeacherProfile($teacher_id)
    {
        //pass gender data to view
        $genders = DB::table('gender')->get();
        $maritals = DB::table('marital_status')->get();
        $teacher_band = DB::table('teacher_bands')->get();

        $teacher_detail = DB::table('teachers')
                            ->where('id', $teacher_id)
                            ->get();
        $teacher_detail = $teacher_detail[0];

        if(isset($teacher_detail->dob)){
            $dob = date('d/m/Y', strtotime($teacher_detail->dob )); // d/m/y

        }else{

            $dob='';
        }

        $labels = DB::table('tlabels')->get();
        $tuition_categories = DB::table('tuition_categories')->get();
        $instututes = DB::table('institutes')->get();
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();

        $result = DB::table('teacher_labels')->where('teacher_id','=',$teacher_id)->get();
        $result1 = DB::table('teacher_tuition_categories')->where('teacher_id','=',$teacher_id)->get();
        $result2 = DB::table('teacher_institute_preferences')->where('teacher_id','=',$teacher_id)->get();


        //build array for edit case to select  labels
        $teacher_labels = array();
        foreach($result as $r){
            $teacher_labels[] =  $r->label_id;
        }

        //build array for edit case to select  tuition categories.
        $tuitioncategories  = array();
        foreach($result1 as $r){
            $tuitioncategories[] =  $r->tuition_category_id;
        }

        //build array for edit case to select  preferred institute.
        $preferredinstitute   = array();
        foreach($result2 as $r){
            $preferredinstitute[] =  $r->institute_id;
        }
       //dd($tuitioncategories);

        return view('tutorprofile', compact('genders', 'maritals', 'teacher_detail', 'teacher_band','instututes','cities','dob',
                    'labels','teacher_labels','tuition_categories','tuitioncategories','preferredinstitute','provinces'));

    }

    public function DeleteTeacher($teacher_id)
    {

        $teacher = Teacher::find($teacher_id);
        try {

            $Teacher =  Teacher::find($teacher_id);
            $user =  User::find($Teacher->user_id);
            $path = base_path() . "/teachers/$teacher_id";



            if(isset($user)&&($user->hasRole('teacher'))){
                $user->removeRole('teacher');
            }

            if(isset($user)&&$user->hasPermissionTo('teacher postal')){

                DB::table('user_has_permissions')->where('user_id', '=', $Teacher->user_id)->delete();
            }

            if (File::exists($path)) {
                $success = File::deleteDirectory($path);
            }

            $teacher->delete();
            if(isset($user)){
                $user->delete();
            }

            return redirect('admin/teachers')->with('deleted', 'Teacher Profile deleted Successfully!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }

    public function DeleteGlobalTeacher($id){

        $teacher = teacher_global::find($id);

        try {
            $teacher->delete();
            return redirect('admin/global/teachers')->with('deleted', 'Record Deleted Successfully!');
        }catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/global/teachers')->with('deleted', $e->errorInfo[2]);
        }

    }

    public function DeleteSelectedGlobalTeachers(){

        $request = Request::all();
        $this->teacher->DeleteGT($request);
        return redirect("admin/global/teachers")->with('status', 'Selected Teachers Deleted Successfully!');

    }


    public function LoadProvinceCities(){

        $request = Request::all();
        $provinceid = $request['provinceid'];

        $cities = city::where('province_id', '=', $provinceid)
                        ->select('id','name','province_id')
                        ->get();


        $selectedCity = 999;
        $options = '<select class="form-control select2"  id="city" name="city" data-placeholder="Select City"> ' ;
        $options .= '<option value=""></option> ' ;
        foreach ($cities as $city){

            $options .= "<option value='$city->id'";
            if($city->id == $selectedCity) {
                $options .="selected";
            }
            $options .= ">$city->name</option> ";

        }
        $options .= '</select>';

        //Load province cities for present address
        $options1 = '<select class="form-control select2"  id="city_p" name="city_p" data-placeholder="Select City"> ' ;
        $options1 .= '<option value=""></option> ' ;
        foreach ($cities as $city){

            $options1 .= "<option value='$city->id'";
            if($city->id == $selectedCity) {
                $options1 .="selected";
            }
            $options1 .= ">$city->name</option> ";

        }
        $options1 .= '</select>';

        return response()->json(['options'=>$options,'options_p'=>$options1]);
    }

    public function LoadTeachersWithMessage(){

        return redirect('admin/teachers')->with('status', 'Teacher Profile Save Successfully!');
    }

    public function TuitionHistoryReason(){

        $request = Request::all();
        $teacherid = $request['th_tid'];
        $historyid = $request['th_id'];
        $reason = $request['reason'];

        $this->teacher->SaveTuitionHistoryReason($historyid,$reason);
        return response()->json(['success' => true,'teacherid'=>$teacherid]);
    }

    public function DeletePreferredInstitute(){

        $request = Request::all();
        $id = $request['id'];
        $this->teacher->DeletePreferredInstitute($id);
        return response()->json(['success' => true]);
    }

    public function DeleteGradeSubjectsCategory(){

        $request = Request::all();
        $id = $request['id'];
        $this->teacher->DeleteGradeSubjectsCategory($id);
        return response()->json(['success' => true]);
    }

    public function DeleteTeacherLabel(){

        $request = Request::all();
        $id = $request['id'];
        $this->teacher->DeleteLabel($id);
        return response()->json(['success' => true]);
    }

    public function GradeSubjects($class_id){

        $options = "<option value=''></option>" ;

        if($class_id == '' ){

            $subjects = DB::table('subjects')->select('subjects.name','subjects.id as sid')->orderBy('subjects.name', 'ASC')->get();
            foreach ($subjects as $subject){

                $options .= "<option value='$subject->sid' >$subject->name</option> ";

            }
        }else{

            $subjects = DB::table('class_subject_mappings')
                        ->join('subjects','subjects.id','=','class_subject_mappings.subject_id')
                        ->select('class_subject_mappings.*', 'subjects.name','subjects.id as sid')
                        ->where('class_id','=',$class_id)
                        ->orderBy('subjects.name', 'ASC')
                        ->get();

            foreach ($subjects as $subject){

                $options .= "<option value='$subject->sid' >$subject->name</option> ";

            }

        }

        return array('options'=>$options,'subjects'=>$subjects);
    }

    public function ZoneLocations($zone_id){

        $options = "<option value=''></option>" ;

        if($zone_id == '' ){

            $locations = DB::table('locations')->select('locations','id','zone_id')->orderBy('locations', 'ASC')->get();
            foreach ($locations as $location){

                $options .= "<option value='$location->id' >$location->locations</option> ";

            }
        }else{

            $locations = DB::table('locations')
                        ->select('locations','id','zone_id')
                        ->where('zone_id','=',$zone_id)
                        ->orderBy('locations', 'ASC')
                        ->get();

            foreach ($locations as $location){

                $options .= "<option value='$location->id' >$location->locations</option> ";

            }

        }

        return array('options'=>$options,'locations'=>$locations);
    }

    public function LoadTeacherShorView($id){
        $experience = DB::table('teachers')
                        ->select('past_experience')
                        ->where('id', $id)
                        ->first();
        $aboutus = DB::table('teachers')
            ->select('strength')
            ->where('id', $id)
            ->first();

        $institutes = DB::select("call  teacher_institutes('$id')");
        $subjects = DB::select("call  teacher_subject_preferences('$id')");
        $locations = DB::select("call  teacher_locations('$id')");

        $institute_list = '';
        $totalInstitute = count($institutes);
        $count=1;
        foreach ($institutes as $institute){
            $institute_list.= $institute->name;
            if($totalInstitute>$count){
                $institute_list.=',';
            }
            $count++;
        }
        if(isset($experience->past_experience)){

            $experiences = preg_split('/\r\n|[\r\n]/', $experience->past_experience);

        }else{
            $experience='';
        }

        if(isset($aboutus->strength)){

            $about_us = preg_split('/\r\n|[\r\n]/', $aboutus->strength);

        }else{
            $aboutus='';
        }

        return view('teachershortview',compact('experiences','institute_list','subjects','locations','about_us'));
    }

}
