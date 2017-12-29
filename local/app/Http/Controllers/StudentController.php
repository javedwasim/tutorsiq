<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Request;
use App\User;
use Validator;
use Mail;
use Hash;
use Flash;
use Acme\Mailers\Mailers;
use App\student;
use Auth;
use Acme\StudentEvent\EventStudent;


class StudentController extends Controller
{
    protected $mailers;
    protected $student;
    protected $flag;
    protected $studentid;

    public function __construct(Mailers $mailers, EventStudent $teacher)
    {
        $this->student = $teacher;
        $this->mailers = $mailers;

        $user = Auth::user();

        if (isset($user)) {
            $this->studentid = $user->id;
            $this->flag = $user->can('student postal');
        }

    }

    public function AppliedTuitionView(){


        $flag = $this->flag;
        $student = "student portal";

        $studentid  = $this->studentid;
        $data = $this->student->GetTuitionDetail($studentid);

        //return $data;

        return view('students.applied_tuitions',compact('flag','student'));
    }

    public function AppliedTuition(){

        $studentid  = $this->studentid;
        $data = $this->student->GetTuitionDetail($studentid);

        return $data;
    }

    public function showStudentPortal()
    {

        $student = "student portal";

        $filters = Request::all();

        if (isset($filters['page'])) {

            $last_filters = session('last_filters');//dd($last_filters);
            $response = $this->student->LoadTeacchersForStudents($last_filters);
            $filters = $last_filters;

        } else {

            $response = $this->student->LoadTeacchersForStudents($filters);
        }

        $teachers = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];

        $labels = DB::table('tlabels')->select('id', 'name')->get();

        $teacher_band = DB::table('teacher_bands')->get();
        $marital_status = DB::table('marital_status')->get();
        $genders = DB::table('gender')->get();

        $tuition_categories = DB::table('tuition_categories')->get();
        $institutes = DB::table('institutes')->get();
        $classes = DB::table('classes')->get();
        $zones = DB::table('zones')->get();

        if (isset($filters['class'])) {
            $classid = $filters['class'];
            $result = $this->GradeSubjects($classid);
            $subjects = $result['subjects'];
        } else {
            $subjects = DB::table('subjects')->select('subjects.id as sid', 'subjects.name')->get();

        }

        if (isset($filters['zone'])) {

            $zoneid = $filters['zone'];
            $result = $this->ZoneLocations($zoneid);
            $locations = $result['locations'];

        } else {
            $locations = DB::table('locations')->select('id', 'locations')->get();

        }

        if (isset($filters['reset'])) {

            $filters = '';

        }

        $flag = $this->flag;
//dd($user->can('student postal'));
        return view('students.welcome', compact('teachers', 'offset', 'count', 'perpage_record', 'zones', 'flag',
            'pagesize', 'student', 'filters', 'institutes', 'tuition_categories', 'classes', 'subjects', 'locations'));

    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $gender = DB::table('gender')->get();
        return view('students.registers',compact('gender'));
    }

    public function register(Request $request)
    {
        try {
            $this->create(Request::all());

        } catch ( \Illuminate\Database\QueryException $e) {
            return redirect('/')->with('status', 'This email has already been taken');
        }


        return redirect('/')->with('status', 'Register Successfully! Please check you email to get login!');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $confirmation_code = str_random(30);

        //create user and assign role and permissions
        $user = $this->CreateUser($data, $confirmation_code);
        $userid = $user['userid'];
        $user_pwd = $user['password'];

        //create student
        $studentid = $this->CreateStudent($data, $userid);

        $name = $data['name'];
        $email = $data['email'];
        $link = url('/') . "/register/verify/$confirmation_code";

        $body = "Dear Teacher <br>";
        $body .= " Thank you for registration. Your login credentials: email: $email, password: $user_pwd <br>";
        $body .= " Kindly click on below link to activate your account <br>";
        $body .= "<a href='$link'>Click</a> <br>";
        $body .= "Regards <br>";
        $body .= "Home Tuition <br>";

        $data = [
            'confirmation_code' => $confirmation_code,
            'emailTo' => $email,
            'name' => $name,
            'subject' => 'Student Registration',
            'body' => $body,
        ];

        //send verification email.
        $this->mailers->EmailToStudent($data);


    }

    public function TutorRequestForm()
    {

        $request = Request::all();
        $teacherid = $request['teacherid'];
        $studentid = $request['studentid'];
        $instututes = DB::table('institutes')->get();
        $locations = DB::table('locations')->get();
        $classes = DB::table('class_subject_mappings')
            ->join('classes', 'classes.id', '=', 'class_subject_mappings.class_id')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', DB::raw('CONCAT(classes.name, " - ", subjects.name) as name '))
            ->orderBy(DB::raw('CONCAT(classes.name, " - ", subjects.name)'))
            ->get();

        return view('students.request-tutor', compact('teacherid', 'studentid', 'instututes', 'locations','classes'));

    }

    public function TutorRequested()
    {

        $request = Request::all();
        $teacherid = $request['teacherid'];
        $studentid = $request['studentid'];

        //save teacher for student
        $this->student->SaveStudentTeachers($teacherid, $studentid);
        //create tutiion for student
        $this->student->SaveTuition($request,$teacherid, $studentid);



    }

    public function CreateUser($data, $confirmation_code)
    {

        $name = $data['name'];
        $email = $data['email'];
        $password = str_random(8);
        $created_at = date('Y-m-d H:i:s', time());
        $updated_at = date('Y-m-d H:i:s', time());

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->confirmation_code = $confirmation_code;
        $user->created_at = $created_at;
        $user->updated_at = $updated_at;
        $user->save();

        //adding roles to a user
        $user->assignRole('student');
        //adding permissions to a user
        $user->givePermissionTo('student postal');
        //user id
        return array('userid' => $user->id, 'password' => $password);

    }

    public function CreateStudent($data, $userid)
    {

        $name = $data['name'];
        $email = $data['email'];
        $gender = $data['gender'];
        $mobile1 = $data['phone'];
        $address1 = $data['address_line1'];
        $city = $data['city'];
        $created_at = date('Y-m-d H:i:s', time());
        $updated_at = date('Y-m-d H:i:s', time());

        $student = new student;
        $student->firstname = $name;
        $student->user_id = $userid;
        $student->email = $email;
        $student->gender_id = $gender;
        $student->mobile1 = $mobile1;
        $student->address_line1 = $address1;
        $student->city = $city;
        $student->created_at = $created_at;
        $student->updated_at = $updated_at;
        $student->save();

        return $student->id;


    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath($email)
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/registered/' . $email;
    }

    public function showTeacherDetails($id)
    {

        $student = "student portal";
        $flag = $this->flag;
        $studentid = $this->studentid;

        $teacherDetails = $this->student->TeacherDetails($id);
        $details = $teacherDetails['details'];
        $qualifications = $teacherDetails['qualifications'];
        $subjects = $teacherDetails['subjects'];
        $institutes = $teacherDetails['institutes'];
        $locations = $teacherDetails['locations'];
        $qdetails = $teacherDetails['qdetails'];
        $tuitionCategories = $teacherDetails['tuitionCategories'];
        return view('students.teacher-details', compact('student', 'flag', 'details', 'id', 'studentid',
            'qualifications', 'subjects', 'institutes', 'locations', 'qdetails', 'tuitionCategories'));

    }

    public function GradeSubjects($class_id)
    {

        $options = "<option value=''></option>";

        if ($class_id == '') {

            $subjects = DB::table('subjects')->select('subjects.name', 'subjects.id as sid')->orderBy('subjects.name', 'ASC')->get();
            foreach ($subjects as $subject) {

                $options .= "<option value='$subject->sid' >$subject->name</option> ";

            }
        } else {

            $subjects = DB::table('class_subject_mappings')
                ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
                ->select('class_subject_mappings.*', 'subjects.name', 'subjects.id as sid')
                ->where('class_id', '=', $class_id)
                ->orderBy('subjects.name', 'ASC')
                ->get();

            foreach ($subjects as $subject) {

                $options .= "<option value='$subject->sid' >$subject->name</option> ";

            }

        }

        return array('options' => $options, 'subjects' => $subjects);
    }

    public function ZoneLocations($zone_id)
    {

        $options = "<option value=''></option>";

        if ($zone_id == '') {

            $locations = DB::table('locations')->select('locations', 'id', 'zone_id')->orderBy('locations', 'ASC')->get();
            foreach ($locations as $location) {

                $options .= "<option value='$location->id' >$location->locations</option> ";

            }
        } else {

            $locations = DB::table('locations')
                ->select('locations', 'id', 'zone_id')
                ->where('zone_id', '=', $zone_id)
                ->orderBy('locations', 'ASC')
                ->get();

            foreach ($locations as $location) {

                $options .= "<option value='$location->id' >$location->locations</option> ";

            }

        }

        return array('options' => $options, 'locations' => $locations);
    }

    public function SaveCallTuition(){

        $Tuition = Request::all();
        //create tuition
        $tuition_id =  $this->student->SaveCallAcademyTuition($Tuition);
        //save tuition subjects
        $this->student->SaveCallAcademyTuitionSubjects($Tuition,$tuition_id);
        //save tuition institutes
        $this->student->SaveCallAcademyTuitionInstitute($Tuition,$tuition_id);

        if (!empty($Tuition['submitbtnValue']) && $Tuition['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }

    }

    public function ConvertDateFormat($date){

        $var = $date;
        $date = str_replace('/', '-', $var);
        $tuition_start_date = date('Y-m-d', strtotime($date));
        return $tuition_start_date;
    }

    public function CallAcademy(){

        $student = "student portal";
        $status = 'add';
        $latest_code= $this->CreateTuitionCode();
        $student_id = $this->studentid;

        $locations = DB::table('locations')->get();
        $classes = DB::table('class_subject_mappings')
            ->join('classes', 'classes.id', '=', 'class_subject_mappings.class_id')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', DB::raw('CONCAT(classes.name, " - ", subjects.name) as name '))
            ->orderBy(DB::raw('CONCAT(classes.name, " - ", subjects.name)'))
            ->get();

        $assign_status = DB::table('tution_status')->where('id', 13)->get();
//        echo '<pre>'; print_r($assign_status); die();
        $tuition_category = DB::table('tuition_categories')->get();
        $notes = DB::table('special_notes')->get();
        $labels = DB::table('tlabels')->get();
        $gender = DB::table('gender')->get();
        $bands = DB::table('teacher_bands')->get();
        $instututes = DB::table('institutes')->get();
        $referrers = DB::table('referrers')->get();

        $preferredinstitute   = array();
        $selected_classes   = array();
        $tuition_details = '';
        //dd($gender);
        return view('students.call-academy', compact('locations','tuition', 'latest_code','classes','assign_status','bands','selected_classes'
            ,'tuition_category','notes','status','tuition_details','labels','gender','instututes','preferredinstitute','referrers','student','student_id'));

    }

    public function CreateTuitionCode(){

        $tuition_code = DB::table('tuitions')
            ->select('tuition_code')
            ->orderBy('id', 'desc')
            ->first();
        $code = "T".date('ym');
        //dd($tuition_code);
        $newStr = ++$tuition_code->tuition_code;
        $last_part = substr($newStr, 5);
        $latest_code = substr_replace($last_part,$code,0,0);

        return $latest_code;

    }


}
