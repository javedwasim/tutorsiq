<?php

namespace App\Http\Controllers;

use App\teacher_institute_preference;
use App\teacher_tuition_category;
use App\user_has_permission;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Teacher\EventTeacher;
use Session;
use App\Teacher;
use App\Tuition;
use App\teacher_application;
use Illuminate\Support\Facades\Route;
use Acme\Config\Constants;
use App\city;
use Illuminate\Support\Facades\Redirect;
use Acme\Mailers\Mailers;

class TeacherPortal extends Controller
{

    protected $teacher;
    protected $user_id;
    protected $teacher_id;
    protected $CurrentUri;
    protected $Followup;
    protected $teacherLocations;
    protected $teacherDetail;
    protected $teacherPreferences;
    protected $mailers;

    public function __construct(EventTeacher $teacher,Constants $constants,Mailers $mailers)
    {

        session::forget('location_list');
        session::forget('subject_list');

        $this->teacher = $teacher;
        $this->mailers = $mailers;

        $user = Auth::user();

        if(isset($user)){

            $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
            $teacherid = $teacher[0]->id;
            $this->teacher_id = $teacherid;
            $this->user_id = $user->id;

            //initialization for tuitions 4 me
            $response = $this->teacher->TeacherPreferences($teacherid);
            $this->teacherPreferences = $response;
            $this->teacherDetail=$teacher[0];
        }



        //initialize current uri.
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
        //initialize location object
        $this->teacherLocations = new LocationPreference($this->teacher);

    }

    public function getDownload()
    {
        $request = Request::all();
        $pathToFile = $request['path'] ;
        $name = $request['filename'] ;

        $headers = array(
            'Content-Type: application/octet-stream',
        );



        if (File::exists($pathToFile))
        {
            return response()->download($pathToFile, $name, $headers);
        }else{

            return Redirect::back()->with('status', 'File Not Found!');
        }

    }




    public function DeleteTuitionCategories($id){

        $obj = teacher_tuition_category::where('id', $id)->first();

        try {

            $obj->delete();
            return redirect('tuition/categories')->with('status', 'Tuition Categories Deleted Successfully!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('tuition/categories')->with('warning', $e->errorInfo[2]);
        }

    }


    public function LoadPreferedInstitutes(){

        $institutes = DB::table('teacher_institute_preferences')
                        ->join('institutes','institutes.id','=','teacher_institute_preferences.institute_id')
                        ->select('teacher_institute_preferences.*', 'institutes.name')
                        ->where('teacher_id', '=', $this->teacher_id)->orderby('institutes.name')->get();
        $current_route = $this->CurrentUri;

        return view('teachers.institutes', compact('institutes','current_route'));

    }

    public function PreferedInstituteView(){

        $institutes = DB::table('institutes')
                        ->leftJoin('teacher_institute_preferences', function($join)
                        {
                            $join->on('teacher_institute_preferences.institute_id', '=', 'institutes.id');
                            $join->on('teacher_institute_preferences.teacher_id', '=', DB::raw("'$this->teacher_id'"));

                        })
                        ->select('teacher_institute_preferences.*', 'institutes.name','institutes.id as instituteid')
                        ->orderby('institutes.name')->groupby('institutes.id')->get();

        $teacher_id= $this->teacher_id;
        $current_route = $this->CurrentUri;


        return view('teachers.prefered_institute_view', compact('institutes','current_route','teacher_id'));
    }


    public function SavePreferedInstitutes(){

        $request = Request::all();
        $institutes = $request['institutes'];
        $teacher_id= $request['teacher_id'];
        $this->teacher->preferedInstitutes($institutes,$teacher_id);

        return redirect('prefered/institutes')->with('status', 'Institutes Save Successfully!');

    }

    public function DeletePreferedInstitute($id){

        $obj = teacher_institute_preference::where('id', $id)->first();

        try {

            $obj->delete();
            return redirect('prefered/institutes')->with('status', 'Prefered Institute Deleted Successfully!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('tuition/categories')->with('warning', $e->errorInfo[2]);
        }

    }


    public function LoadTuitionCategories(){


        $categories = DB::table('teacher_tuition_categories')
                            ->join('tuition_categories','tuition_categories.id','=','teacher_tuition_categories.tuition_category_id')
                            ->select('teacher_tuition_categories.*', 'tuition_categories.name')
                            ->where('teacher_id', '=', $this->teacher_id)->orderby('tuition_categories.name')->get();

        $current_route = $this->CurrentUri;
        return view('teachers.categories', compact('categories','current_route'));

    }

    public function TuitionCategoriesView(){

        $categories = DB::table('tuition_categories')
        ->leftJoin('teacher_tuition_categories', function($join)
        {
            $join->on('teacher_tuition_categories.tuition_category_id', '=', 'tuition_categories.id');
            $join->on('teacher_tuition_categories.teacher_id', '=', DB::raw("'$this->teacher_id'"));

        })
        ->select('teacher_tuition_categories.*', 'tuition_categories.name','tuition_categories.id as categoryid')
        ->orderby('tuition_categories.name')->groupby('tuition_categories.id')->get();

        $teacher_id= $this->teacher_id;
        $current_route = $this->CurrentUri;
        return view('teachers.tuition_categories_view', compact('categories','current_route','teacher_id'));

    }

    public function SaveTuitionCategories(){

    	$request = Request::all();
    	$categories = $request['categories'];
    	$teacher_id= $request['teacher_id'];
    	$this->teacher->tuitionCategories($categories,$teacher_id);

        return redirect('tuition/categories')->with('status', 'Tuition Categories Save Successfully!');

    }

    public function LoadLocations()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $locations = DB::table('teacher_location_preferences')
                    ->join('locations', 'teacher_location_preferences.location_id', '=', 'locations.id')
                    ->join('zones', 'zones.id', '=', 'locations.zone_id')
                    ->select('teacher_location_preferences.*', 'locations.locations','zones.name as zone_name','zones.id as zone_id')
                    ->where('teacher_id', '=', $teacherid)->orderby('locations')->get();

        $current_route = $this->CurrentUri;
        return view('teachers.locations', compact('locations','current_route'));

    }

    public function LoadTuitions()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;
        $status_id = [1,2,14];
        $tuitions = DB::table('tuition_history')
                    ->join('tuition_details', 'tuition_details.id', '=', 'tuition_history.tuition_detail_id')
                    ->join('tuitions', 'tuitions.id', '=', 'tuition_details.tuition_id')
                    ->join('class_subject_mappings', 'class_subject_mappings.id', '=', 'tuition_details.class_subject_mapping_id')
                    ->join('classes', 'class_subject_mappings.class_id', '=', 'classes.id')
                    ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
                    ->join('locations', 'locations.id', '=', 'tuitions.location_id')
                    ->select(
                        'tuitions.tuition_code as tuition_code',
                        'tuition_history.assign_date as assign_date',
                        'tuition_history.feedback_rating as feedback_rating',
                        'tuition_history.feedback_comment as reason',
                        'tuition_details.is_trial as is_trial',
                        'classes.name as class_name',
                        'subjects.name as subject_name',
                        'locations.locations as location_name',
                        'tuitions.id as tuition_id'
                        )
                    ->where('tuition_history.teacher_id', '=', $teacherid)
                    ->get();
//        dd($tuitions);
        $current_route = $this->CurrentUri;
        return view('teachers.tuitions', compact('tuitions','current_route'));

    }

    public function LoadTuitionDetails()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $response = $this->teacher->TeacherPreferences($teacherid);

        $tuitions = $response['tuitions'];
        $location_list = $response['location_list'];
        $subject_list = $response['subject_list'];

        $categories = DB::table('tuition_categories')->get();
        $locations = DB::table('locations')->get();
        $subjects = DB::table('subjects')->get();
        $classes = DB::table('classes')->get();
        $current_route = $this->CurrentUri;

        return view('teachers.tuitiondetails', compact('tuitions', 'categories', 'locations', 'subjects'
            , 'classes', 'location_list', 'subject_list','current_route'));

    }

    public function LoadTuitionSearch(){

        $filters = Request::all();
        //dd($filters);
        //load tables data for filters
        $classes = DB::table('classes')->get();
        $subjects = DB::table('subjects')->get();
        $genders = DB::table('gender')->get();
        $labels = DB::table('tlabels')->get();
        $categories = DB::table('tuition_categories')->get();
        $institutes = DB::table('institutes')->get();



        if(isset($filters['reset'])){

            $filters = "";

            $records = $this->teacher->AdvanceSearchTuitions($filters);
            $tuitions = $records['tutiions'];

            $response = $this->teacher->LoadMatchedTuitions($tuitions,9);

        }
        elseif(isset($filters['page'])){

            $last_filters = session('last_filters');
            $records = $this->teacher->AdvanceSearchTuitions($last_filters);
            $tuitions = $records['tutiions'];

            $response = $this->teacher->LoadMatchedTuitions($tuitions,9);
            $filters = $last_filters;

        }
        else{

            $records = $this->teacher->AdvanceSearchTuitions($filters);
            $tuitions = $records['tutiions'];
            $response = $this->teacher->LoadMatchedTuitions($tuitions,9);

        }

        $tuitions = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;

        //if zone filter selected then load related locations
        if(isset($filters['zone']) && $filters['zone']!=0 ){
            $zoneid = $filters['zone'];
        }else{
            $zoneid = 0;
        }

        $result = $this->teacher->getZoneLocations($zoneid);
        $locations = $result['locations'];
        $location_list = $result['locations_list'];

        //if zone filter selected then load related locations
        if(isset($filters['class']) ){
            $classid = $filters['class'];
            $result = $this->teacher->getGradeSubjects($classid);
            $subjects = $result['subjects'];
        }

        $zones = DB::table('zones')->get();
        $current_route = $this->CurrentUri;

        return view('teachers.advanced_tuition_search',compact('tuition_start_date','tuition_end_date','classes','count','filters','teacher_id','zones','location_list'
                    ,'subjects','genders','labels','categories','locations','tuitions','pagesize','institutes',
                    'offset','perpage_record','tuition_filter','current_route'));

    }

    public function ZoneLocations($zone_id){

        $result = $this->teacher->getZoneLocations($zone_id);
        $options = $result['options'];
        $location_list = $result['locations_list'];

        return response()->json(['options' => $options,'locations_list'=>$location_list]);
    }

    public function GradeSubjects($class_id){

        $result = $this->teacher->getGradeSubjects($class_id);
        $options = $result['options'];
        $subjects_list = $result['subjects_list'];

        return response()->json(['options' => $options,'subjects_list'=>$subjects_list]);

    }

    public function LoadTuitionBYAutoMatched(Request $request)
    {

        $filters = Request::all();
        $teacherDetail = $this->teacherDetail;
        $teacherPreferences = $this->teacherPreferences;
        $response = $this->teacher->LoadTuitionForMe($filters,$teacherDetail,$teacherPreferences);

        $tuitions = $response['records'];
        $offset = $response['offset'];
        $totlalRecords = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;
        $flag=true;
        $tuitionByCategory='';

        return view('teachers.matchedtuitions', compact('tuitions', 'offset', 'totlalRecords','flag','tuitionByCategory',
                    'perpage_record', 'pagesize','teacher_id','current_route','teacherDetail','teacherPreferences'));


    }

    public function LoadTuitionByCategory($cate_id)
    {


        $tuitions = DB::select("call  matched_tuitions('','$cate_id','','','')");

        if(!empty($tuitions)){
            $category = $tuitions[0]->c_name;
        }else{
            $category='';
        }

        $response = $this->teacher->LoadMatchedTuitions($tuitions,9);

        $tuitions = $response['records'];
        $offset = $response['offset'];
        $totlalRecords = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;

        if($current_route=='tuition/matched/{id}'){
            $tuitionByCategory = "tuition/matched/$cate_id";
        }else{
            $tuitionByCategory = "";
        }
        return view('teachers.matchedtuitions', compact('tuitions', 'offset', 'totlalRecords',
                    'perpage_record', 'pagesize','category','teacher_id','current_route','tuitionByCategory'));


    }

    public function LoadTuitionByClassSubject()
    {

        $filter = Request::all();


        if (isset($filter['page'])) {

            if (!empty(session('class_name'))) {

                $class_name = session('class_name');
                $tuitions = DB::select("call  matched_tuitions('','','','$class_name','')");

            } else {

                $subject_name = session('subject');
                $tuitions = DB::select("call  matched_tuitions('','','$subject_name','','')");
            }


        } else {


            if (isset($filter['class_name']) && !empty($filter['class_name'])) {

                $class_name = $filter['class_name'];
                session(['class_name' => $class_name]);
                session::forget('subject');
                $tuitions = DB::select("call  matched_tuitions('','','','$class_name','')");

            } else {

                $subject_name = $filter['subject'];
                session(['subject' => $subject_name]);
                session::forget('class_name');
                $tuitions = DB::select("call  matched_tuitions('','','$subject_name','','')");
            }

        }


        $response = $this->teacher->LoadMatchedTuitions($tuitions,9);
        $tuitions = $response['records'];
        $offset = $response['offset'];
        $totlalRecords = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;

        return view('teachers.matchedtuitions', compact('tuitions', 'offset', 'totlalRecords',
            'perpage_record', 'pagesize','subject_name','class_name','teacher_id','current_route'));

    }

    public function TuitionDetail(){
        $request        = Request::all();
        $tuition_id     = $request['tuition_id'];
        $teacher_id     = $request['teacher_id'];
        $class_name     = $request['class_name'];
        $subject_name   = $request['subject_name'];
        $location       = $request['location'];
        $special_notes  = $request['special_notes'];

        $teacher= Teacher::find($teacher_id);
        $application =  $teacher->applications()->where('tuition_id',$tuition_id)->where('teacher_id',$teacher_id)->get()->toArray();
        $application_count = count($application);

        $details = DB::table('tuitions')
            ->leftjoin('tuition_institute_preferences', 'tuitions.id', '=', 'tuition_institute_preferences.tuition_id')
            ->leftjoin('institutes', 'tuition_institute_preferences.institute_id', '=', 'institutes.id')
            ->select(DB::raw("GROUP_CONCAT(DISTINCT(institutes.name) SEPARATOR ',') as `institute_names`"),
                'tuitions.suitable_timings as suitable_timings','tuitions.teaching_duration as teaching_duration',
                'tuitions.tuition_fee as tuition_fee','tuitions.tuition_max_fee as tuition_max_fee',
                'tuitions.no_of_students as no_of_students')
            ->where('tuitions.id', $tuition_id)
            ->get();

        if($application_count>0){
            $application = $application[0]['notes'];
        }else{
            $application='';
        }

        return response()->json(['success' => true, 'teacher_id' => $teacher_id,
            'tuition_id' => $tuition_id, 'class_name' => $class_name,'subject_name'=>$subject_name,
            'location' => $location, 'special_notes' => $special_notes,'application_count'=>$application_count,
            'application'=>$application, 'details' => $details]);


    }

    public function TuitionApplied(){

        $request = Request::all();

        $application = new teacher_application();
        $application->teacher_id = $request['teacher_id'];
        $application->tuition_id = $request['tuition_id'];
        $application->notes = $request['application_note'];
        $application->application_status_id = 1;
        $application->save();


        if(isset($request['tuitionbycategory']) && !empty($request['tuitionbycategory'])){

            return redirect($request['tuitionbycategory'])->with('message', 'Applied Successfully!');

        }elseif(isset($request['advance_search']) && !empty($request['advance_search']) ){

            return redirect('tuition/search')->with('message', 'Applied Successfully!');

        }elseif(isset($request['my_tuitions']) && !empty($request['my_tuitions'])){

            return redirect('tuition/automatched')->with('message', 'Applied Successfully!');

        }else{

            return redirect('tuition/details')->with('message', 'Applied Successfully!');

        }

    }

    public function LoadTuitionByLocation()
    {

        $location = Request::all();


        if (isset($location['page'])) {

            $location_name = session('location');
            $tuitions = DB::select("call  matched_tuitions('$location_name','','','','')");



        } else {

            $location_name = $location['location'];
            session(['location' => $location_name]);
            $tuitions = DB::select("call  matched_tuitions('$location_name','','','','')");

        }


        $response = $this->teacher->LoadMatchedTuitions($tuitions,9);
        $tuitions = $response['records'];
        $offset = $response['offset'];
        $totlalRecords = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;

        return view('teachers.matchedtuitions', compact('tuitions', 'offset', 'totlalRecords', 'perpage_record', 'pagesize',
            'location_name','teacher_id','current_route'));

    }

    public function LoadTuitionByGender()
    {

        $gender = Request::all();


        if (isset($gender['page'])) {

            $gender = session('gender');
            $tuitions = DB::select("call  matched_tuitions('','','','','$gender')");



        } else {

            $gender = $gender['gender'];
            session(['gender' => $gender]);
            $tuitions = DB::select("call  matched_tuitions('','','','','$gender')");
        }

        $response = $this->teacher->LoadMatchedTuitions($tuitions,9);
        $tuitions = $response['records'];
        $offset = $response['offset'];
        $totlalRecords = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;

        return view('teachers.matchedtuitions', compact('tuitions', 'offset', 'totlalRecords', 'perpage_record', 'pagesize',
            'gender','teacher_id','current_route'));

    }


    public function LoadReferences()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $references = DB::table('teacher_references')->where('teacher_id', '=', $teacherid)->get();
        $current_route = $this->CurrentUri;

        return view('teachers.references', compact('references','current_route'));


    }

    public function LoadSubjectPreferences()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $preferences = DB::select("call  teacher_subject_preferences('$teacherid')");
        $current_route = $this->CurrentUri;

        return view('teachers.subjectpreferences', compact('preferences','current_route'));


    }

    public function TeacherPreferenceDelete($pid)
    {

        $newStr = explode("-", $pid);
        $teacherid = $newStr[0];
        $class_id = $newStr[1];

        try {
            $this->teacher->deleteSubjectPreferrences($class_id,$teacherid);

            return redirect('preferences')->with('status', 'Subject Preferrence Deleted Successfully!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }


    public function TeacherReferenceAddView()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $status = 'add';
        return view('teachers.tutor-reference', compact('references', 'status', 'teacherid'));
    }

    public function TeacherLocationAddView()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacher_id = $teacher[0]->id;

        $status = 'add';
        $zones = DB::table('zones')->orderBy('id')->get();
        $zoneLocations  = $this->teacher->TeacherLocations($teacher_id);
        $current_route = $this->CurrentUri;


        return view('teachers.location_preferences', compact('zoneLocations', 'status', 'teacher_id','zones','filters','current_route'));
    }

    public function TeacherPreferenceAddView()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $subjects = DB::table('subjects')->get();
        $classes = DB::table('classes')->get();
        $status = 'add';

        return view('teachers.tutor-preference', compact('preferences', 'status', 'teacherid', 'subjects', 'classes'));
    }

    public function LoadExperiences()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $experiences = DB::table('teacher_experiences')
                        ->select('teacher_experiences.*')
                        ->where('teacher_id', '=', $teacherid)->get();

        $current_route = $this->CurrentUri;

        return view('teachers.experiences', compact('experiences','current_route'));

    }


    public function showTeacherPortal()
    {
        $user = Auth::user();
        $roles = $user->roles()->pluck('name');
        //dd($user->email);
        $user_name = explode(' ', $user->name);
        if (count($user_name) > 1) {
            $first_name = $user_name[0];
            $last_name = $user_name[1];
        }

       $teacher_detail = DB::table('teachers')->where('user_id', $user->id)->get();

        if(isset($teacher_detail[0]->dob)){
            $dob = date('d/m/Y', strtotime($teacher_detail[0]->dob )); // d/m/y

        }else{

            $dob='';
        }

//dd($dob);

        if (!empty($teacher_detail)) {

            $teacher_detail = $teacher_detail[0];
            $teacher_id = $teacher_detail->id;
        }

        //view data to be passed
        $genders = DB::table('gender')->get();
        $maritals = DB::table('marital_status')->get();
        $teacher_band = DB::table('teacher_bands')->get();
        $labels = DB::table('labels')->get();
        $tuition_categories = DB::table('tuition_categories')->get();
        $instututes = DB::table('institutes')->get();
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();

        $result = DB::table('teacher_tuition_categories')->where('teacher_id','=',$teacher_id)->get();
        $result2 = DB::table('teacher_institute_preferences')->where('teacher_id','=',$teacher_id)->get();

        $tuitioncategories = array();
        foreach($result as $r){
            $tuitioncategories[] =  $r->tuition_category_id;
        }

        //build array for edit case to select  preferred institute.
        $preferredinstitute   = array();
        foreach($result2 as $r){
            $preferredinstitute[] =  $r->institute_id;
        }

        $current_route = $this->CurrentUri;

        return view('teachers.teacher_portal', compact('genders', 'maritals', 'teacher_band', 'labels', 'user','instututes','provinces','cities',
            'teacher_detail', 'first_name', 'last_name','current_route','tuition_categories','tuitioncategories','preferredinstitute','dob'));

    }


    public function TeacherSignup(){

        //view data to be passed
        $genders = DB::table('gender')->get();
        $maritals = DB::table('marital_status')->get();
        $teacher_band = DB::table('teacher_bands')->get();
        $labels = DB::table('labels')->get();
        $tuition_categories = DB::table('tuition_categories')->get();
        $instututes = DB::table('institutes')->get();
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();
        $step = 1;
        $step1Data = session('step1');
        return view('tutorsignup', compact('genders', 'maritals', 'teacher_band', 'labels', 'user','instututes','provinces','cities','step','step1Data',
            'teacher_detail', 'first_name', 'last_name','current_route','tuition_categories','tuitioncategories','preferredinstitute','dob'));

    }

    public function Step2(Request $request){
        //save step1 data
        $teacher_profile = Request::all();

        if(!empty($teacher_profile)){

            //save step1 data in session
            session(['step1' => $teacher_profile]);
            //if user already exist.
            $result = DB::table('users')->where('email', '=', $teacher_profile['email'])->get();
            if (!empty($result)) {
                return Redirect::back()->with('status', 'This email has already been taken.');
            }

            //get latest userid and save in session
            $user = $user = DB::table('users')->orderBy('id', 'desc')->first();
            $latestUserId = $user->id;
            $userid = $latestUserId+1;
            session(['tuid' => $userid]);

            $teacher =  DB::table('teachers')->orderBy('id', 'desc')->first();
            $latestTeacherId = $teacher->id;
            $teacherid = $latestTeacherId+1;
            session(['teacherid' => $teacherid]);


        }else{

            $teacherid = session('teacherid');
            $userid = session('tuid');
        }

        $step = 2;
        $dob='';
        $provinces = DB::table('provinces')->get();
        $cities = DB::table('cities')->get();
        $step2Data = session('step2');

        return view('tutorsignup', compact('dob','step','provinces','cities','teacherid','userid','step2Data'));

    }


    public function Step3(){

        $contactInfo = Request::all();

        $rules = array(
            'cnic_front_image'  => 'required|mimes:jpeg,jpg,png,bmp|max:1000',
            'cnic_back_image'   => 'required|mimes:jpeg,jpg,png,bmp|max:1000',
        );

        if(!empty($contactInfo)) {

            $step2Data = $this->Step2FormData($contactInfo);
            session(['step2' => $step2Data]);
            $teacherid = $contactInfo['teacherid'];

            $cnic_front = Request::file('cnic_front_image');
            $cnic_back = Request::file('cnic_back_image');
            $teacher_photo = Request::file('teacher_photo');
            $electricity_bill = Request::file('electricity_bill');

            $path = base_path() . "/teachers/$teacherid";
            if (File::exists($path)) {
                $success = File::deleteDirectory($path);
            }

            $validator = Validator::make(Request::all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()->with('status', 'The image must be a file of type: jpg, png.');
            }

            //create directory for teacher's document
            if (!empty($contactInfo['cnic_front_image']) && !empty($contactInfo['cnic_back_image'])) {

                //$this->upload_teacher_cnic_image($cnic_front,$cnic_back, $teacherid);

                $cnicfront = $cnic_front->getClientOriginalName();
                $cnicback = $cnic_back->getClientOriginalName();

                session(['cnic_front' => $cnicfront]);
                session(['cnic_back' => $cnicback]);

                $path = base_path() . "/teachers/$teacherid/cnic/";

                if (file_exists($path . $cnicfront) && file_exists($path . $cnicback)) {
                    $cnicfront = time() . '-' . $cnicfront;
                    $cnicback = time() . '-' . $cnicback;

                    //update database again for cnic image
                    $teacher = Teacher::find($teacherid);
                    $teacher->cnic_front_image = $cnicfront;
                    $teacher->cnic_back_image = $cnicback;
                    $teacher->save();

                }

                $this->upload_teacher_cnic_image($cnic_front, $cnic_back, $cnicfront, $cnicback, $teacherid);

            }
            //create directory for teaher photo
            if (!empty($contactInfo['teacher_photo'])) {

                $teacherphoto = $teacher_photo->getClientOriginalName();
                session(['teacher_photo' => $teacherphoto]);

                $path = base_path() . "/teachers/$teacherid/photo/";

                if (file_exists($path . $teacherphoto)) {
                    $teacherphoto = time() . '-' . $teacherphoto;
                    //update database again for cnic image
                    $teacher = Teacher::find($teacherid);
                    $teacher->teacher_photo = $teacherphoto;
                    $teacher->save();
                }

                $this->upload_teacher_photo_image($teacher_photo, $teacherphoto, $teacherid);

            }
            //create directory for teaher electricity bill
            if (!empty($contactInfo['electricity_bill'])) {

                $electricitybill = $electricity_bill->getClientOriginalName();
                session(['electricitybill' => $electricitybill]);
                $path = base_path() . "/teachers/$teacherid/bill/";

                if (file_exists($path . $electricitybill)) {
                    $electricitybill = time() . '-' . $electricitybill;
                    //update database again for cnic image
                    $teacher = Teacher::find($teacherid);
                    $teacher->electricity_bill = $electricitybill;
                    $teacher->save();
                }

                $this->upload_teacher_electricity_bill($electricity_bill, $electricitybill, $teacherid);

            }

        }

        $step = 3;
        $step3Data = session('step3');
        $step32Data = session('step32');

        return view('tutorsignup', compact('step','teacherid','step3Data','step32Data'));

    }

    public function Step4(){

        $qualification = Request::all();
        if(!empty($qualification['teacherid'])) {

            $step3Data = $this->Step3FormData($qualification);
            $step32Data = $this->Step32FormData($qualification);

            //save step2 data in session
            if (!empty($qualification)) {
                session(['step3' => $step3Data]);
                session(['step32' => $step32Data]);
                $teacherid = $qualification['teacherid'];
            }

            $degree_document = Request::file('degree_document');
            $degree_document2 = Request::file('degree_document2');

            //create qualification documents
            if (isset($degree_document)) {

                $filename = $degree_document->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/qualification/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }
                session(['qualification1' => $filename]);
                $this->teacher->upload_teacher_documents($degree_document, $path, $filename);

            }

            if (isset($degree_document2)) {

                $filename = $degree_document2->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/qualification/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }

                session(['qualification2' => $filename]);
                $this->teacher->upload_teacher_documents($degree_document2, $path, $filename);

            }

        }

        $step = 4;
        $classes = DB::table('class_subject_mappings')
            ->join('classes', 'classes.id', '=', 'class_subject_mappings.class_id')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', DB::raw('CONCAT(classes.name, " - ", subjects.name) as name '))
            ->orderBy(DB::raw('CONCAT(classes.name, " - ", subjects.name)'))
            ->get();
        $tuition_categories = DB::table('tuition_categories')->get();
        $step4Data = session('step4');

        return view('tutorsignup', compact('step','teacherid','classes','tuition_categories','step4Data'));
    }

    public function Step5(){

        $preferences = Request::all();

        //save teacher preferences
        if(!empty($preferences['teacherid'])) {
            //save step2 data in session
            if (!empty($preferences)) {
                session(['step4' => $preferences]);
                $teacherid = $preferences['teacherid'];
            }
            //save categories and subject preferences.

        }else{
            $teacherid = session('teacherid');
        }

        $step = 5;
        $institutes = DB::table('institutes')->get();
        $zones = DB::table('zones')->orderBy('id')->get();
        $zoneLocations  =  DB::table('locations')
                            ->join('zones', 'zones.id', '=', 'locations.zone_id')
                            ->select('locations.id','zones.id as zid',DB::raw("CONCAT(zones.name, \"-\",locations) as zonelocations"))
                            ->get();
        $step5Data = session('step5');

        //send email if message
        if(!empty($preferences['body'])) {

            $emailData = array();
            $emailData['body'] = $preferences['body'];
            $emailData['subject'] = $preferences['subject'];
            $this->mailers->EmailToAdmin($emailData);

        }

        return view('tutorsignup', compact('step','teacherid','institutes','zones','zoneLocations','step5Data'));
    }

    public function Step6(){

        $preferences = Request::all();
        if(!empty($preferences)){

            //save step5 data in session
            session(['step5' => $preferences]);
            $teacherid = $preferences['teacherid'];
            //save categories and subject preferences.


        }else{

            $teacherid = session('teacherid');
        }

        $step = 6;
        //send email if message
        if(!empty($preferences['body'])) {

            $emailData = array();
            $emailData['body'] = $preferences['body'];
            $emailData['subject'] = $preferences['subject'];
            $this->mailers->EmailToAdmin($emailData);

        }

        return view('tutorsignup', compact('step','teacherid','classes','tuition_categories'));

    }

    public function finish(){

        $terms = Request::all();
        if(!empty($terms)){

            //save step5 data in session
            session(['step6' => $terms]);
            $teacherid = $terms['teacherid'];
            //save categories and subject preferences.
            $this->teacher->SaveStep6($terms);

        }


        return response()->json(['stage'=>'finish']);
    }

    public function thankyou(){

        return view('thankyou');
    }

    public function SendEmailToUser(){

        $terms = Request::all();
        $confirmation_code = $terms['confirmation_code'];
        $teacherid = $terms['teacherid'];

        $teacher = DB::table('teachers')->where('id', $teacherid)->first();

        $this->teacher->SendTroubleEmail($teacher,$confirmation_code);
        return response()->json(['stage'=>'mail sent']);
    }

    public function Step32FormData($qualification){

        $step3Data = array();

        $step3Data['step'] = $qualification['step'];
        $step3Data['teacherid'] = $qualification['teacherid'];
        $step3Data['qualification_name'] = $qualification['qualification_name2'];
        $step3Data['highest_degree'] = $qualification['highest_degree2'];
        $step3Data['continue'] = $qualification['continue2'];
        $step3Data['higher_degree'] = $qualification['higher_degree2'];
        $step3Data['elective_subjects'] = $qualification['elective_subjects2'];
        $step3Data['institution'] = $qualification['institution2'];
        $step3Data['passing_year'] = $qualification['passing_year2'];
        $step3Data['grade'] = $qualification['grade2'];

        return $step3Data;
    }

    public function Step3FormData($qualification){

        $step3Data = array();

        $step3Data['step'] = $qualification['step'];
        $step3Data['teacherid'] = $qualification['teacherid'];
        $step3Data['qualification_name'] = $qualification['qualification_name'];
        $step3Data['highest_degree'] = $qualification['highest_degree'];
        $step3Data['continue'] = $qualification['continue'];
        $step3Data['higher_degree'] = $qualification['higher_degree'];
        $step3Data['elective_subjects'] = $qualification['elective_subjects'];
        $step3Data['institution'] = $qualification['institution'];
        $step3Data['passing_year'] = $qualification['passing_year'];
        $step3Data['grade'] = $qualification['grade'];

        return $step3Data;
    }

    public function Step2FormData($contactInfo){

        $step2Data = array();

        $step2Data['step'] = $contactInfo['step'];
        $step2Data['teacherid'] = $contactInfo['teacherid'];
        $step2Data['dob'] = $contactInfo['dob'];
        $step2Data['landline'] = $contactInfo['landline'];
        $step2Data['mobile1'] = $contactInfo['mobile1'];
        $step2Data['personal_contactno2'] = $contactInfo['personal_contactno2'];
        $step2Data['guardian_contact_no'] = $contactInfo['guardian_contact_no'];
        $step2Data['mobile2'] = $contactInfo['mobile2'];
        $step2Data['cnic_number'] = $contactInfo['cnic_number'];
        $step2Data['address_line1'] = $contactInfo['address_line1'];
        $step2Data['province'] = $contactInfo['province'];
        $step2Data['city'] = $contactInfo['city'];
        $step2Data['zip_code'] = $contactInfo['zip_code'];
        $step2Data['country'] = $contactInfo['country'];
        $step2Data['address_line1_p'] = $contactInfo['address_line1_p'];
        $step2Data['province_p'] = $contactInfo['province_p'];
        $step2Data['city_p'] = $contactInfo['city_p'];
        $step2Data['zip_code_p'] = $contactInfo['zip_code_p'];
        $step2Data['country_p'] = $contactInfo['country_p'];

        return $step2Data;
    }

    // create directory for teacher documents
    public function upload_teacher_cnic_image($cnic_front, $cnic_back, $cnicfront, $cnicback, $teacherid)
    {

        $path = base_path() . "/teachers/$teacherid/cnic/";

        if (!File::exists($path)) {

            File::makeDirectory($path, 0777, true);
            $cnic_front->move($path, $cnicfront);
            $cnic_back->move($path, $cnicback);

        } else {

            $cnic_front->move($path, $cnicfront);
            $cnic_back->move($path, $cnicback);

        }
    }

    // create directory for teacher photo
    public function upload_teacher_photo_image($teacher_photo, $photo, $teacherid)
    {

        $path = base_path() . "/teachers/$teacherid/photo/";

        if (!File::exists($path)) {

            File::makeDirectory($path, 0777, true);
            $teacher_photo->move($path, $photo);


        } else {

            $teacher_photo->move($path, $photo);

        }
    }

    // create directory for teacher electricity bill
    public function upload_teacher_electricity_bill($electricity_bill, $photo, $teacherid)
    {

        $path = base_path() . "/teachers/$teacherid/bill/";

        if (!File::exists($path)) {

            File::makeDirectory($path, 0777, true);
            $electricity_bill->move($path, $photo);


        } else {

            $electricity_bill->move($path, $photo);

        }
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

    public function saveTeacherProfile(Request $request)
    {

        $teacher_profile = Request::all();
        $teacherid = $this->teacher->saveProfile($teacher_profile);

        return response()->json(['success' => 'save', 'teacherid' => $teacherid]);

    }

    public function TuitionApplications(Request $request)
    {

        $filters = Request::all(); //dd($filters);

        $user = Auth::user();
        $userid = $user->id;
        $teacher = DB::table('teachers')->where('user_id', '=', $userid)->get();
        $teacherid = $teacher[0]->id;

        $tuitions = DB::table('teacher_applications')
                        ->join('tuitions','tuitions.id','=','teacher_applications.tuition_id')
                        ->join('tuition_details','tuition_details.tuition_id','=','tuitions.id')
                        ->join('class_subject_mappings','class_subject_mappings.id','=','tuition_details.class_subject_mapping_id')
                        ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
                        ->join('classes', 'class_subject_mappings.class_id', '=', 'classes.id')
                        ->join('application_status', 'application_status.id', '=', 'teacher_applications.application_status_id')
                        ->join('teachers', 'teachers.id', '=', 'teacher_applications.teacher_id')
                        ->join('teacher_location_preferences', 'teachers.id', '=', 'teacher_location_preferences.teacher_id')
                        ->join('locations', 'teacher_location_preferences.location_id', '=', 'locations.id')
                        ->select(DB::raw("GROUP_CONCAT(DISTINCT(subjects.name) SEPARATOR '-') as `s_names`"),
                            'classes.name as class_name','tuitions.*','teacher_applications.notes',
                            'application_status.name as status','application_status.description as description',
                            'application_status.id as description_id','locations.locations',
                            'tuitions.id as tuition_id', 'teacher_applications.id as application_id')
                        ->groupBy('tuitions.id')
                        ->where('teacher_applications.teacher_id', '=', $teacherid)->get();

        $response = $this->teacher->Load($tuitions,'applications',$filters);
//        echo '<pre>'; print_r($response); die();
        $tuitions = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $teacher_id = $this->teacher_id;
        $current_route = $this->CurrentUri;

        //dd($response);

        return view('teachers.applications', compact('tuitions', 'offset', 'count', 'perpage_record', 'pagesize',
            'location_name','teacher_id','current_route'));


        //return view('teachers.applications', compact('tuitions'));


    }

    public function DeleteApplicationTuition ($id){

       $query = DB::table('teacher_applications')->where('id',$id)->delete();
       if ($query == true){
            return redirect('applications')->with('deleted', 'Record Deleted Successfully!');
       }else{
            return redirect('applications')->with('error', 'Process Failed. Please Try Again');
       }
    }

    public function TeacherQualificationAddView()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $status = 'add';
        $teacherid = $teacher[0]->id;
        $register = 'front';

        return view('teachers.tutor-qualification', compact('teachers', 'status', 'teacherid', 'register'));

    }

    public function TeacherExperienceAddView()
    {
        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $status = 'add';
        $teacherid = $teacher[0]->id;
        $register = 'front';

        return view('teachers.tutor-experience', compact('teachers', 'status', 'teacherid', 'register'));

    }

    public function TeacherQualificationEditView($qid)
    {

        $teacher_qualifications = DB::table('teacher_qualifications')->where('id', $qid)->get();
        $qualifications = $teacher_qualifications[0];
        $status = 'update';
        $current_route = $this->CurrentUri;

        return view('teachers.tutor-qualification', compact('qualifications', 'status','current_route'));

    }

    public function TeacherExperienceEditView($id)
    {
        $teacher_experience = DB::table('teacher_experiences')->where('id', $id)->get();
        $experiences = $teacher_experience[0];
        $status = 'update';
        return view('teachers.tutor-experience', compact('experiences', 'status'));

    }

    public function TeacherReferenceEditView($id)
    {

        $teacher_reference = DB::table('teacher_references')->where('id', $id)->get();
        $references = $teacher_reference[0];
        $status = 'update';
        return view('teachers.tutor-reference', compact('references', 'status'));
    }

}
