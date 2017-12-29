<?php

namespace Acme\Teacher;

use App\User;
use App\Teacher;
use App\teacher_label;
use App\teacher_preference;
use App\Http\Controllers\AdminController;
use App\tuition_history;
use Auth;
use Mail;
use App\teacher_global;
use Request;
use Validator;
use File;
use Storage;
use Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\teacher_tuition_category;
use App\teacher_institute_preference;
use Acme\Mailers\Mailers;
use Acme\Config\Constants;
use App\location_preference;
use App\teacher_tuition_categorie;
use App\teacher_qualification;
use Session;


class EventTeacher
{
    protected $teacherProfile;
    protected $mailers;


    public function __construct(Mailers $mailers, Constants $constants)
    {
        $this->teacherProfile = new AdminController($mailers, $constants);
        $this->mailers = $mailers;

    }

    public function CreateUserForTeacher($teacher_profile,$userid){

        $confirmation_code = str_random(30);

        //create user with role and permisssion
        $user = new User;
        $user->id = $userid;
        $user->name = $teacher_profile['fullname'];
        $user->email = $teacher_profile['email'];
        $user->password = Hash::make($teacher_profile['password']);
        $user->confirmation_code = $confirmation_code;
        $user->confirmed = 0;
        $user->save();
        //adding roles to a user
        $user->assignRole('teacher');
        //adding permissions to a user
        $user->givePermissionTo('teacher postal');
        //user id
        return  array('userid'=>$user->id,'confirmation_code'=>$confirmation_code);


    }

    public function SignupTeacher($teacher_profile,$user_id,$teacher_uid){

        $created_by = 'admin';
        $created_at = date('Y-m-d H:i:s', time());
        $updated_by = 'admin';
        $updated_at = date('Y-m-d H:i:s', time());

        //save teacher
        $obj = new Teacher();
        $obj->id = $teacher_uid;
        $obj->fullname = $teacher_profile['fullname'];
        $obj->added_by = 'tutor';
        $obj->user_id = $user_id;
        $obj->marital_status_id = $teacher_profile['marital_status_id'];
        $obj->gender_id = $teacher_profile['gender_id'];
        $obj->father_name = $teacher_profile['father_name'];
        $obj->expected_minimum_fee = $teacher_profile['expected_minimum_fee'];
        $obj->religion = $teacher_profile['religion'];
        $obj->strength = $teacher_profile['strength'];
        $obj->email = $teacher_profile['email'];
        $obj->password = $teacher_profile['password'];
        $obj->is_active = 0;
        $obj->is_approved = 1;
        $obj->experience = $teacher_profile['experience'];
        $obj->suitable_timings = $teacher_profile['suitable_timings'];
        $obj->age = $teacher_profile['age'];
        $obj->livingin = $teacher_profile['livingin'];
        $obj->reference_for_rent = $teacher_profile['reference_for_rent'];
        $obj->reference_gurantor = $teacher_profile['reference_gurantor'];
        $obj->about_us = $teacher_profile['about_us'];
        $obj->past_experience = $teacher_profile['past_experience'];
        $obj->created_by = $created_by;
        $obj->created_at = $created_at;
        $obj->updated_by = $updated_by;
        $obj->updated_at = $updated_at;
        $obj->save();

        session(['teacherid' => $obj->id]);
        return $obj->id;
        // return teacherid

    }

    public function UpdateTeaacher($teacher_profile,$teacher_session_id){

        $created_by = 'admin';
        $created_at = date('Y-m-d H:i:s', time());
        $updated_by = 'admin';
        $updated_at = date('Y-m-d H:i:s', time());

        $obj = Teacher::find($teacher_session_id);
        $obj->fullname = $teacher_profile['fullname'];
        $obj->added_by = 'tutor';
        $obj->marital_status_id = $teacher_profile['marital_status_id'];
        $obj->gender_id = $teacher_profile['gender_id'];
        $obj->father_name = $teacher_profile['father_name'];
        $obj->expected_minimum_fee = $teacher_profile['expected_minimum_fee'];
        $obj->religion = $teacher_profile['religion'];
        $obj->strength = $teacher_profile['strength'];
        $obj->is_active = 0;
        $obj->is_approved = 1;
        $obj->experience = $teacher_profile['experience'];
        $obj->suitable_timings = $teacher_profile['suitable_timings'];
        $obj->age = $teacher_profile['age'];
        $obj->livingin = $teacher_profile['livingin'];
        $obj->reference_for_rent = $teacher_profile['reference_for_rent'];
        $obj->reference_gurantor = $teacher_profile['reference_gurantor'];
        $obj->about_us = $teacher_profile['about_us'];
        $obj->past_experience = $teacher_profile['past_experience'];
        $obj->created_by = $created_by;
        $obj->created_at = $created_at;
        $obj->updated_by = $updated_by;
        $obj->updated_at = $updated_at;
        $obj->save();


    }


    public function SaveStep2($contactInfo,$cnic_front, $cnic_back, $teacher_photo,$electricity_bill){

        $teacherid = $contactInfo['teacherid'];
        //format date to mysql
        $var = $contactInfo['dob'];
        $date = str_replace('/', '-', $var);
        $dob = date('Y-m-d', strtotime($date));

//        if(!empty($teacher_photo)){
//
//            $teacher_photo =  $teacher_photo->getClientOriginalName();
//        }
//
//        if(!empty($electricity_bill)){
//
//            $electricity_bill =  $electricity_bill->getClientOriginalName();
//        }
//
//        if(!empty($cnic_front)){
//
//            $cnic_front = $cnic_front->getClientOriginalName();
//        }
//
//        if(!empty($cnic_front)){
//
//            $cnic_back = $cnic_back->getClientOriginalName();
//        }

        $obj = Teacher::find($teacherid);

        $obj->cnic_number = $contactInfo['cnic_number'];
        $obj->cnic_front_image = $cnic_front;
        $obj->cnic_back_image = $cnic_back;
        $obj->dob = $dob;
        $obj->landline = $contactInfo['landline'];
        $obj->mobile1 = $contactInfo['mobile1'];
        $obj->personal_contactno2 = $contactInfo['personal_contactno2'];
        $obj->mobile2 = $contactInfo['mobile2'];
        $obj->address_line1 = $contactInfo['address_line1'];
        $obj->address_line1_p = $contactInfo['address_line1_p'];
        $obj->city = $contactInfo['city'];
        $obj->city_p = $contactInfo['city_p'];
        $obj->province = $contactInfo['province'];
        $obj->province_p = $contactInfo['province_p'];
        $obj->zip_code = $contactInfo['zip_code'];
        $obj->zip_code_p = $contactInfo['zip_code_p'];
        $obj->country = $contactInfo['country'];
        $obj->country_p = $contactInfo['country_p'];
        $obj->teacher_photo = $teacher_photo;
        $obj->electricity_bill = $electricity_bill;
        $obj->guardian_contact_no = $contactInfo['guardian_contact_no'];

        $obj->save();


    }

    public function SaveTeacherQualification($qualification){

        $teacherid = $qualification['teacherid'];
        $teacher_qualification = new teacher_qualification();
        $teacher_qualification->teacher_id = $qualification['teacherid'];
        $teacher_qualification->passing_year = $qualification['passing_year'];
        $teacher_qualification->institution = $qualification['institution'];
        $teacher_qualification->grade = $qualification['grade'];
        $teacher_qualification->qualification_name = $qualification['qualification_name'];
        $teacher_qualification->highest_degree = $qualification['highest_degree'];
        $teacher_qualification->elective_subjects = $qualification['elective_subjects'];
        $teacher_qualification->status = $qualification['continue'];
        $teacher_qualification->higher_degree = $qualification['higher_degree'];
        $teacher_qualification->degree_document = session('qualification1');
        $teacher_qualification->created_at = date('Y-m-d H:i:s', time());
        $teacher_qualification->updated_at = date('Y-m-d H:i:s', time());
        $teacher_qualification->save();
    }




    public function SaveStep4($preferences){

        $TuitionCategories = $preferences['categories'];
        $teacherid = $preferences['teacherid'];
        //save tuition categories.
        $this->tuitionCategories($TuitionCategories,$teacherid);
        //save subject preferences
        $this->SaveSubjectPreferrences($preferences,$teacherid);
    }


    public function SaveStep5($preferences){

        $institutes = $preferences['institutes'];
        $locations = $preferences['locations'];
        $teacherid = $preferences['teacherid'];

        $this->SaveLocations($locations,$teacherid);
        $this->SaveInstitues($institutes,$teacherid);

    }

    public function SaveStep6($terms){

        //save stage1 form data
        $teacher_profile = session('step1');
        $teacher_uid  = session('teacherid');

        $session_userid = session('tuid');
        $result = DB::table('users')->where('id', '=',$session_userid )->first();

        if (empty($result)) {

            $userData =  $this->CreateUserForTeacher($teacher_profile,$session_userid);
            $userid  = $userData['userid'];

            $confirmation_code  = $userData['confirmation_code'];

            session(['tuid' => $userid]);
            session(['confirmation_code' => $confirmation_code]);
            session(['user_email' => $teacher_profile['email']]);
        }
        //create or update teacher profile
        $result = DB::table('teachers')->where('id', '=', $teacher_uid)->first();
        if (!empty($result)) {
            $this->UpdateTeaacher($teacher_profile,$teacher_uid);
        }else{

             $this->SignupTeacher($teacher_profile,$userid,$teacher_uid);
        }

        //save teacher profile.
        $contactInfo = session('step2');
        $cnic_front = session('cnic_front');
        $cnic_back = session('cnic_back');
        $teacher_photo = session('teacher_photo');
        $electricity_bill = session('electricity_bill');
        $this->SaveStep2($contactInfo,$cnic_front, $cnic_back, $teacher_photo,$electricity_bill);

        //save Qualification Data
        $qualification = session('step3');
        $qualification2 = session('step32');
        //delete saved qualification
        $qual = DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacher_uid)->get();
        if (!empty($qual)) {

            DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacher_uid)->delete();

        }
        //add new qualifications
        $this->SaveTeacherQualification($qualification);
        $this->SaveTeacherQualification($qualification2);

        //save subjects preferences
        $preferences = session('step4');
        $this->SaveStep4($preferences);

        //save institue preferences
        $preferences = session('step5');
        $this->SaveStep5($preferences);

        //save terms data
        $terms = session('step6');
        $teacherid = $terms['teacherid'];
        //$confirmation_code = $terms['confirmation_code'];

        $obj = Teacher::find($teacherid);
        $obj->visited = $terms['visited'];
        $obj->accept = $terms['accept'];
        $obj->save();

        $teacher = DB::table('teachers')->where('id', $teacherid)->first();
        $this->SendEmail($teacher,session('confirmation_code'));

        //cear seesion data
//        session::forget('teacherid');
//        session::forget('tuid');
//        session::forget('step1');
//        session::forget('step2');
//        session::forget('step3');
//        session::forget('step4');
//        session::forget('step5');

    }

    public function SendTroubleEmail($teacher,$confirmation_code){

        $this->SendEmail($teacher,$confirmation_code);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function SendEmail($teacher,$confirmation_code)
    {

        $name = $teacher->fullname;
        $email = $teacher->email;
        $user_pwd = $teacher->password;
        $link = url('/') . "/register/verify/$confirmation_code";

        $body = "Dear Teacher <br>";
        $body .= " Thank you for registration. Your login credentials: email: $email, password: $user_pwd <br>";
        $body .= " Kindly click on below link to activate your account <br>";
        $body .= " <a href='$link'>Click</a> <br>";
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

    public function SaveLocations($locations,$teacherid){

        $location = DB::table('teacher_location_preferences')->where('teacher_id', $teacherid)->first();
        if(isset($location) && !empty($location->id)){
            $deletedRows = location_preference::where('teacher_id', $teacherid)->delete();

        }
        //save teacher locations
        $locations_length = count($locations);
        for ($j = 0; $j < $locations_length; $j++) {

            //save selected subjects
            $teacher_preference = new location_preference();
            $teacher_preference->teacher_id = $teacherid;
            $loc = explode("-",$locations[$j]);
            $teacher_preference->location_id = $loc[0];
            $teacher_preference->zoneid = $loc[1];

            $teacher_preference->created_at = date('Y-m-d H:i:s', time());
            $teacher_preference->updated_at = date('Y-m-d H:i:s', time());
            $teacher_preference->save();
        }

    }

    public function SaveInstitues($institutes,$teacherid){

        $inst = DB::table('teacher_institute_preferences')->where('teacher_id', $teacherid)->first();
        if(isset($inst) && !empty($inst->id)){
            $deletedRows = teacher_institute_preference::where('teacher_id', $teacherid)->delete();

        }
        //save teacher institute
        $institute_length = count($institutes);
        for ($j = 0; $j < $institute_length; $j++) {

            //save selected subjects
            $teacher_preference = new teacher_institute_preference();
            $teacher_preference->teacher_id = $teacherid;
            $teacher_preference->institute_id = $institutes[$j];

            $teacher_preference->created_at = date('Y-m-d H:i:s', time());
            $teacher_preference->updated_at = date('Y-m-d H:i:s', time());
            $teacher_preference->save();
        }


    }

    public function SaveSubjectPreferrences($preferences,$teacherid){

        $pid = DB::table('teacher_subject_preferences')->where('teacher_id', $teacherid)->first();
        if(isset($pid) && !empty($pid->id)){
            $deletedRows = teacher_preference::where('teacher_id', $teacherid)->delete();

        }
        //save subject preferences
        $subject_length = count($preferences['csm']);
        for ($j = 0; $j < $subject_length; $j++) {

            //save selected subjects
            $teacher_preference = new teacher_preference();
            $teacher_preference->teacher_id = $teacherid;
            $teacher_preference->class_subject_mapping_id = $preferences['csm'][$j];

            $teacher_preference->created_at = date('Y-m-d H:i:s', time());
            $teacher_preference->updated_at = date('Y-m-d H:i:s', time());
            $teacher_preference->save();
        }

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

    public function saveProfile($teacherProfile)
    {
        $userid = $teacherProfile['user_id'];

        $tid_update = $teacherProfile['tid_update'];
        $insertedId = $userid;
        $cnic_front = Request::file('cnic_front_image');
        $cnic_back = Request::file('cnic_back_image');
        $teacher_photo = Request::file('teacher_photo');
        $electricity_bill = Request::file('electricity_bill');

        //save teacher profile
        $this->teacherProfile->save_teacher($userid, $teacherProfile, $cnic_front, $cnic_back, $tid_update, $teacher_photo, $electricity_bill);


        //save directory for teacher's document
        if (!empty($teacherProfile['cnic_front_image']) && !empty($teacherProfile['cnic_back_image'])) {


            $cnicfront = $cnic_front->getClientOriginalName();
            $cnicback = $cnic_back->getClientOriginalName();
            $path = base_path() . "/teachers/$tid_update/cnic/";

            if (file_exists($path . $cnicfront) && file_exists($path . $cnicback)) {
                $cnicfront = time() . '-' . $cnicfront;
                $cnicback = time() . '-' . $cnicback;

                //update database again for cnic image
                $teacher = Teacher::find($tid_update);
                $teacher->cnic_front_image = $cnicfront;
                $teacher->cnic_back_image = $cnicback;
                $teacher->save();

            }

            $this->teacherProfile->upload_teacher_cnic_image($cnic_front, $cnic_back, $cnicfront, $cnicback, $tid_update);


        }
        //save teacher profile photo
        if (!empty($teacherProfile['teacher_photo'])) {

            $teacherphoto = $teacher_photo->getClientOriginalName();
            $path = base_path() . "/teachers/$tid_update/photo/";

            if (file_exists($path . $teacherphoto)) {
                $teacherphoto = time() . '-' . $teacherphoto;
                //update database again for cnic image
                $teacher = Teacher::find($tid_update);
                $teacher->teacher_photo = $teacherphoto;
                $teacher->save();
            }

            $this->teacherProfile->upload_teacher_photo_image($teacher_photo, $teacherphoto, $tid_update);

        }

        if (!empty($teacherProfile['electricity_bill'])) {

            $electricitybill = $electricity_bill->getClientOriginalName();
            $path = base_path() . "/teachers/$tid_update/bill/";

            if (file_exists($path . $electricitybill)) {
                $electricitybill = time() . '-' . $electricitybill;
                //update database again for cnic image
                $teacher = Teacher::find($tid_update);
                $teacher->electricity_bill = $electricitybill;
                $teacher->save();
            }

            $this->teacherProfile->upload_teacher_electricity_bill($electricity_bill, $electricitybill, $tid_update);

        }

        //save teacher tuition categories
        if (isset($teacherProfile['tuition_category_id']) && $teacherProfile['category_change'] == 'change') {

            $this->teacherProfile->tuitionCategories($teacherProfile, $tid_update);
        }

        //save teacher preferred institute
        if (isset($teacherProfile['institute_id']) && $teacherProfile['institute_change'] == 'change') {

            $this->teacherProfile->preferredInstitute($teacherProfile, $tid_update);

        }

        return $tid_update;


    }

    public function AdvanceSearchTuitions($filters)
    {

        $category_list = '';
        $location_list = '';
        $institute_list = '';


        if (isset($filters['tuition_code'])) {
            $tuition_code = $filters['tuition_code'];


        } else {
            $tuition_code = "";

        }

        if (isset($filters['no_of_students'])) {

            $no_of_students = $filters['no_of_students'];


        } else {
            $no_of_students = "";

        }

        if (isset($filters['categories'])) {

            //$location_list = 'SELECT loc.id = ';
            $categories = $filters['categories'];
            $csv = count($categories);
            $count = 1;
            foreach ($categories as $c) {
                $category_list .= $c;
                if ($count <= $csv) {
                    $category_list .= ',';
                }
                $count++;
            }

        }
        //select all zone locations if zone selected and locations not selected.
        if (isset($filters['zone']) && !isset($filters['locations'])) {

            $location_list = $filters['locations_list'];

        } elseif (isset($filters['zone']) && isset($filters['locations'])) {

            //$location_list = 'SELECT loc.id = ';
            $locations = $filters['locations'];
            $csv = count($locations);
            $count = 1;
            foreach ($locations as $l) {
                $location_list .= $l;
                if ($count <= $csv) {
                    $location_list .= ',';
                }
                $count++;
            }

        }

        if (isset($filters['class'])) {
            $class = $filters['class'];

        } else {
            $class = 0;

        }
        if (isset($filters['subject'])) {
            $subject = $filters['subject'];

        } else {
            $subject = 0;

        }

        if (isset($filters['fee_range']) && !empty($filters['fee_range'])) {
            $fee_range = explode("-", $filters['fee_range']);
            $min_fee = $fee_range[0] * 1000;
            $max_fee = $fee_range[1] * 1000;
        } else {
            $min_fee = 0;
            $max_fee = 0;
        }

        if (isset($filters['institutes'])) {

            //$location_list = 'SELECT loc.id = ';
            $institutions = $filters['institutes'];
            $csv = count($institutions);
            $count = 1;
            foreach ($institutions as $inst) {
                $institute_list .= $inst;
                if ($count <= $csv) {
                    $institute_list .= ',';
                }
                $count++;
            }
        } else {

            $institute_list = '';
        }

        if (isset($filters['gender'])) {

            $gender = $filters['gender'];

        } else {

            $gender = '';
        }

        if (isset($filters['suitable_timings'])) {

            $timing = $filters['suitable_timings'];
            if ($filters['suitable_timings'] == 'anytime') {

                $timing = '';

            }

        } else {
            $timing = '';
        }

        if (isset($filters['teaching_duration'])) {

            $duration = $filters['teaching_duration'];


        } else {
            $duration = '';
        }

        if (isset($filters['age'])) {
            $age = $filters['age'];
        } else {
            $age = '';
        }

        $last_filters = session(['last_filters' => $filters]);
        $tuitions = DB::select("call  advanced_search_tuition('$tuition_code','$class','$subject','$category_list', '$location_list',
       '$min_fee','$max_fee','$institute_list','$gender','$no_of_students','$timing','$duration','$age')");

        return array('tutiions' => $tuitions, 'filters' => $filters);
    }

    public function preferedInstitutes($institutes, $teacherid)
    {

        if (isset($institutes)) {

            if ($teacherid != '') {

                //find and delete previous tuition categories.
                $obj = DB::table('teacher_institute_preferences')->where('teacher_id', '=', $teacherid)->get();


                if (!empty($obj)) {

                    DB::table('teacher_institute_preferences')->where('teacher_id', '=', $teacherid)->delete();

                }

            }

            //add new selected tuition categories.
            $len = count($institutes);

            for ($j = 0; $j < $len; $j++) {

                $obj = new teacher_institute_preference();
                $obj->institute_id = $institutes[$j];
                $obj->teacher_id = $teacherid;
                $obj->save();
            }

        }

    }

    public function tuitionCategories($TuitionCategories, $teacherid)
    {

        if (isset($TuitionCategories)) {

            if ($teacherid != '') {

                //find and delete previous tuition categories.
                $obj = DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacherid)->get();


                if (!empty($obj)) {

                    DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacherid)->delete();

                }

            }

            //add new selected tuition categories.
            $len = count($TuitionCategories);

            for ($j = 0; $j < $len; $j++) {

                $obj = new teacher_tuition_category();
                $obj->tuition_category_id = $TuitionCategories[$j];
                $obj->teacher_id = $teacherid;
                $obj->save();
            }

        }

    }


    public function load($records, $links, $filters)
    {
        $last_filters = session(['last_filters' => $filters]);
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($records);
        //Define how many items we want to be visible in each page
        if (isset($filters['pagesize']) && $filters['pagesize'] > 0) {

            $perPage = $filters['pagesize'];
        } else {
            $perPage = 50;
        }

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $records = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $records->setPath($links);
        //Total records
        $count_records = count($collection);
        //count of records on current page.
        if ($currentPage == 1) {

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        } else {

            $offset = ($perPage * $currentPage) - ($perPage - 1);
            $perpage_record = $offset + (count($currentPageSearchResults) - 1);
        }

        return $data = array(
            'count' => $count_records,
            'perpage_record' => $perpage_record,
            'offset' => $offset,
            'records' => $records,
            'pagesize' => $perPage
        );


    }

    public function save_band($formdata, $modaname)
    {

        $name = $formdata['name'];
        $display_order = $formdata['display_order'];

        $id = $formdata['id'];
        $status = $formdata['status'];

        $obj = new $modaname();
        $obj->name = $name;
        $obj->display_order = $display_order;

        $obj->created_at = date('Y-m-d H:i:s', time());
        $obj->updated_at = date('Y-m-d H:i:s', time());

        if ($status == 'add') {

            $obj->save();

        } else {

            $obj = $modaname::find($id);
            $obj->name = $name;
            $obj->display_order = $display_order;

            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if (!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }

    public function saveApplicationStatus($formdata, $modaname)
    {

        $name = $formdata['name'];
        $description = $formdata['description'];
        $sms_description = $formdata['sms_description'];
        $id = $formdata['id'];
        $status = $formdata['status'];

        if ($status == 'add') {

            $obj = new $modaname();
            $obj->name = $name;
            $obj->description = $description;
            $obj->sms_description = $sms_description;
            $obj->created_at = date('Y-m-d H:i:s', time());
            $obj->updated_at = date('Y-m-d H:i:s', time());

            $obj->save();

        } else {

            $obj = $modaname::find($id);
            $obj->name = $name;
            $obj->description = $description;
            $obj->sms_description = $sms_description;
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if (!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }

    }

    public function save($formdata, $modaname)
    {

        $name = $formdata['name'];
        if (isset($formdata['color'])) {
            $color = $formdata['color'];
        }


        $id = $formdata['id'];
        $status = $formdata['status'];


        if ($status == 'add') {

            $obj = new $modaname();
            $obj->name = $name;
            if (isset($formdata['color'])) {
                $obj->color = $color;
            }
            $obj->created_at = date('Y-m-d H:i:s', time());
            $obj->updated_at = date('Y-m-d H:i:s', time());

            $obj->save();

        } else {

            $obj = $modaname::find($id);
            $obj->name = $name;
            if (isset($formdata['color'])) {
                $obj->color = $color;
            }
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if (!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }


    public function getZoneLocations($zone_id)
    {

        $options = "";
        $location_list = '';

        if ($zone_id == 0) {

            $locations = DB::table('locations')->select('locations', 'locations.id as lid')->orderBy('locations', 'ASC')->get();
            foreach ($locations as $location) {

                $options .= "<option value='$location->lid' >$location->locations</option> ";
                $location_list .= $location->lid;
                $location_list .= ',';
            }
        } else {

            $locations = DB::table('locations')
                ->select('locations', 'locations.id as lid')
                ->where('zone_id', '=', $zone_id)
                ->orderBy('locations', 'ASC')
                ->get();


            foreach ($locations as $location) {

                $options .= "<option value='$location->lid' >$location->locations</option> ";
                $location_list .= $location->lid;
                $location_list .= ',';

            }

        }

        return array(
            'locations' => $locations,
            'options' => $options,
            'locations_list' => $location_list
        );
    }

    public function getGradeSubjects($class_id)
    {

        $options = "<option value=''>ALL</option>";
        $subjects_list = '';
        if ($class_id == 0) {

            $subjects = DB::table('subjects')->select('subjects.name', 'subjects.id')->orderBy('subjects.name', 'ASC')->get();
            foreach ($subjects as $subject) {

                $options .= "<option value='$subject->id' >$subject->name</option> ";
                $subjects_list .= $subject->id;
                $subjects_list .= ',';

            }
        } else {

            $subjects = DB::table('class_subject_mappings')
                ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
                ->select('class_subject_mappings.*', 'subjects.name', 'subjects.id')
                ->where('class_id', '=', $class_id)
                ->orderBy('subjects.name', 'ASC')
                ->get();


            foreach ($subjects as $subject) {

                $options .= "<option value='$subject->id' >$subject->name</option> ";
                $subjects_list .= $subject->id;
                $subjects_list .= ',';

            }

        }
        return array(
            'subjects' => $subjects,
            'options' => $options,
            'subjects_list' => $subjects_list
        );
    }

    public function loadGradeSubjectsMappings($class)
    {

        $class_id = $class['classid'];
        $classes = DB::table('classes')->where('id', $class_id)->get();
        $class_name = $classes[0]->name;

        $mappings = DB::table('class_subject_mappings')
            ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
            ->select('class_subject_mappings.*', 'subjects.id as sid', 'subjects.name as name')
            ->where('class_id', $class_id)->get();

        return array('classname' => $class_name, 'subjectname' => $mappings);

    }

    public function getGradeSubjectsMappings($class_id, $teacherid)
    {

        $mappings = DB::table('class_subject_mappings')
            ->leftJoin('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
            ->leftJoin('teacher_subject_preferences',
                function ($join) use ($teacherid) {
                    $join->on('teacher_subject_preferences.class_subject_mapping_id', '=', 'class_subject_mappings.id');
                    $join->on('teacher_subject_preferences.teacher_id', '=', DB::raw("$teacherid"));
                })
            ->select('class_subject_mappings.*', 'subjects.id as sid', 'subjects.name as name', 'teacher_subject_preferences.class_subject_mapping_id')
            ->where('class_subject_mappings.class_id', $class_id)
            ->groupBy('class_subject_mappings.id')
            ->get();

        $gradeSubjectCount = count($mappings);
        $mappingSubjectCount = 0;
        //check if already mapping subjects equal to grade subjects
        foreach ($mappings as $mapping) {

            if (isset($mapping->class_subject_mapping_id)) {
                $mappingSubjectCount++;
            }
        }

        if ($gradeSubjectCount == $mappingSubjectCount) {
            $flag = false;
        } else {
            $flag = true;
        }


        return array(
            'flag' => $flag,
            'mappings' => $mappings
        );

    }

    public function gradeSubjectsMappings($Preference)
    {

        $teacherid = $Preference['teacherid'];
        $pid = $Preference['id'];
        $status = $Preference['status'];
        $classid = $Preference['class_id'];
        $subject_length = count($Preference['subjects']);

        //delete last preferrences.
        $this->deleteSubjectPreferrences($classid, $teacherid);

        if ($status == 'add') {

            for ($j = 0; $j < $subject_length; $j++) {

                //save selected subjects
                $teacher_preference = new teacher_preference();
                $teacher_preference->teacher_id = $teacherid;
                $teacher_preference->class_subject_mapping_id = $Preference['subjects'][$j];

                $teacher_preference->created_at = date('Y-m-d H:i:s', time());
                $teacher_preference->updated_at = date('Y-m-d H:i:s', time());
                $teacher_preference->save();
            }


        }
        return array('teacherid' => $teacherid, 'classid' => $classid);

    }

    public function deleteSubjectPreferrences($classid, $teacherid)
    {

        //find subject preferrences of selected teacher
        $preferrences = DB::table('teacher_subject_preferences')
            ->join('class_subject_mappings',
                function ($join) use ($classid) {
                    $join->on('class_subject_mappings.id', '=', 'teacher_subject_preferences.class_subject_mapping_id');
                    $join->on('class_subject_mappings.class_id', '=', DB::raw("$classid"));
                })
            ->select('teacher_subject_preferences.*')
            ->where('teacher_subject_preferences.teacher_id', '=', $teacherid)
            ->groupBy('teacher_subject_preferences.id')
            ->get();

        if (isset($preferrences) && !empty($preferrences)) {

            foreach ($preferrences as $preferrence) {

                DB::table('teacher_subject_preferences')->where('id', '=', $preferrence->id)->delete();

            }
        }

    }

    public function SaveTuitionHistoryReason($historyid, $reason)
    {

        $obj = tuition_history::find($historyid);
        $obj->feedback_comment = $reason;
        $obj->save();
    }


    public function delete($modalobj, $id)
    {

        $obj = $modalobj::find($id);

        try {
            $obj->delete();
        } catch (\Illuminate\Database\QueryException $e) {

            //return redirect($link)->with('warning', $e->errorInfo[2]);
        }
    }

    public function DeletePreferredInstitute($id)
    {

        $obj = teacher_institute_preference::find($id);
        $obj->delete();
    }

    public function DeleteGradeSubjectsCategory($id)
    {

        $obj = teacher_tuition_category::find($id);
        $obj->delete();
    }

    public function DeleteLabel($id)
    {

        $obj = teacher_label::find($id);
        $obj->delete();
    }

    public function loads($filters)
    {

        $last_filters = session(['last_filters' => $filters]);

        $degree_levels = '';
        $subject_list = '';
        $location_list = '';
        $label_list = '';
        $category_list = '';
        $qualification_list = '';
        $institute_list = '';

        if (isset($filters['reset'])) {

            $firstname = "";
            $zipcode = "";
            $teacher_band_id = "";
            $marital_status_id = "";
            $gender_id = "";
            $father_name = "";
            $expected_minimum_fee = "";
            $cnic_number = "";
            $mobile1 = "";
            $city = "";
            $province = "";
            $email = "";
            $is_active = "";
            $is_approved = "";
            $pagesize = 50;
            $age = "";
            $experience = "";
            $reg_number = "";
            $teacher_id = "";
            $time = "";

        } else {

            if (isset($filters['firstname'])) {
                $firstname = $filters['firstname'];
            } else {
                $firstname = "";
            }
            if (isset($filters['zip_code'])) {
                $zipcode = $filters['zip_code'];
            } else {
                $zipcode = "";
            }
            if (isset($filters['teacher_band_id'])) {
                $teacher_band_id = $filters['teacher_band_id'];
            } else {
                $teacher_band_id = "";
            }
            if (isset($filters['marital_status_id'])) {
                $marital_status_id = $filters['marital_status_id'];
            } else {
                $marital_status_id = "";
            }
            if (isset($filters['gender_id'])) {
                $gender_id = $filters['gender_id'];
            } else {
                $gender_id = "";
            }
            if (isset($filters['father_name'])) {
                $father_name = $filters['father_name'];
            } else {
                $father_name = "";
            }
            if (isset($filters['expected_minimum_fee'])) {
                $expected_minimum_fee = $filters['expected_minimum_fee'];
            } else {
                $expected_minimum_fee = 0;
            }
            if (isset($filters['cnic_number'])) {
                $cnic_number = $filters['cnic_number'];
            } else {
                $cnic_number = "";
            }
            if (isset($filters['mobile1'])) {
                $mobile1 = $filters['mobile1'];
            } else {
                $mobile1 = "";
            }
            if (isset($filters['city'])) {
                $city = $filters['city'];
            } else {
                $city = "";
            }
            if (isset($filters['province'])) {
                $province = $filters['province'];
            } else {
                $province = "";
            }
            if (isset($filters['email'])) {
                $email = $filters['email'];
            } else {
                $email = "";
            }

            if (isset($filters['is_active'])) {
                $is_active = $filters['is_active'];
            } else {
                $is_active = "0";
            }

            if (isset($filters['is_approved'])) {
                $is_approved = $filters['is_approved'];
            } else {
                $is_approved = "0";
            }


            if (isset($filters['age'])) {
                $age = $filters['age'];
            } else {
                $age = 0;
            }

            if (isset($filters['experience'])) {
                $experience = $filters['experience'];
            } else {
                $experience = '';
            }

            if (isset($filters['reg_number'])) {
                $reg_number = $filters['reg_number'];
            } else {
                $reg_number = '';
            }

            if (isset($filters['suitable_timings'])) {
                $time = $filters['suitable_timings'];
            } else {
                $time = '';
            }


            if (isset($filters['labels'])) {

                $labels = $filters['labels'];
                $label_list = $this->FilterCSV($labels);

            }

            if (isset($filters['categories'])) {

                $categories = $filters['categories'];
                $category_list = $this->FilterCSV($categories);

            }

            if (isset($filters['insts'])) {

                $institutes = $filters['insts'];
                $institute_list = $this->FilterCSV($institutes);

            }

            //if zone category selected and location not selected then load all zone locations.
            if (!empty($filters['zone']) && empty($filters['locations'])) {

                $zoneid = $filters['zone'];
                $locationid = '';
                //load mapping against selected class
                $zoneLocationMappings = $this->ZoneLocationsMappings($zoneid, $locationid);
                $location_list = $this->CSMCSV($zoneLocationMappings);


            } //if both filters are set.
            elseif (!empty($filters['zone']) && !empty($filters['locations'])) {

                $locations = $filters['locations'];
                $zoneid = $filters['zone'];
                $locationids = $this->FilterCSV($locations);
                //load mapping against selected class and subjects
                $zoneLocationMappings = $this->ZoneLocationsMappings($zoneid, $locationids);
                //if not recod found
                if (empty($zoneLocationMappings)) {
                    $location_list = 'null,'; //send null id
                } else {

                    $location_list = $this->CSMCSV($zoneLocationMappings);
                }


            } elseif (empty($filters['zone']) && !empty($filters['locations'])) {

                $locations = $filters['locations'];
                $locationids = $this->FilterCSV($locations);
                //load mapping against selected class and subjects
                $locationMappings = $this->LocationMappings($locationids);
                $location_list = $this->CSMCSV($locationMappings);

            }

            //if grade category selected and subjects not selected then load all grade subjects.
            if (!empty($filters['class']) && empty($filters['subjects'])) {

                $classid = $filters['class'];
                $subjectid = '';
                //load mapping against selected class
                $clsssSubjectMappings = $this->ClassSubjectMapplings($classid, $subjectid);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);

            } //if both filters are set.
            elseif (!empty($filters['class']) && !empty($filters['subjects'])) {

                $subjects = $filters['subjects'];
                $classid = $filters['class'];
                $subjectids = $this->FilterCSV($subjects);
                //load mapping against selected class and subjects
                $clsssSubjectMappings = $this->ClassSubjectMapplings($classid, $subjectids);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);


            } elseif (empty($filters['class']) && !empty($filters['subjects'])) {

                $subjects = $filters['subjects'];
                $subjectids = $this->FilterCSV($subjects);
                //load mapping against selected class and subjects
                $clsssSubjectMappings = $this->SubjectsMapping($subjectids);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);


            }

            //fetch teacher_id against qualification matched
            if (isset($filters['qual_name']) && !empty($filters['qual_name'])) {

                $q_name = $filters['qual_name'];
                $qualifications = DB::table('teacher_qualifications')
                    ->select('teacher_id')
                    ->where('qualification_name', 'like', '%' . $q_name . '%')
                    ->groupBy('teacher_id')
                    ->get();

                $csv = count($qualifications);
                $count = 1;
                foreach ($qualifications as $qualification) {
                    $qualification_list .= $qualification->teacher_id;
                    if ($count <= $csv) {
                        $qualification_list .= ',';
                    }
                    $count++;
                }

                //if no qualification find against criteria.
                if (empty($qualification_list)) {

                    $qualification_list = 'null'; //send null value.

                }

            }

            if (isset($filters['pagesize']) && $filters['pagesize'] > 0) {

                $pagesize = $filters['pagesize'];

            } else {
                $pagesize = 50;
            }

            if (isset($filters['teacher_id'])) {

                $teacher_id = $filters['teacher_id'];
            } else {

                $teacher_id = '';
            }


        }

        //teacher search filters
        $teachers = DB::select("call  load_teachers('$firstname','$zipcode','$teacher_band_id','$marital_status_id',
                        '$gender_id','$father_name','$expected_minimum_fee','$cnic_number','$mobile1','$city','$province',
                        '$email','$degree_levels','$subject_list','$location_list','$is_active','$is_approved','$label_list'
                        ,'$age','$experience','$reg_number','$teacher_id','$category_list','$qualification_list'
                        ,'$institute_list','$time')");

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($teachers);
        //Define how many items we want to be visible in each page
        $perPage = $pagesize;
        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $teachers = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $teachers->setPath('teachers');
        //Total records
        $count_teachers = count($collection);
        //count of records on current page.
        if ($currentPage == 1) {

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        } else {

            $offset = ($perPage * $currentPage) - ($perPage - 1);
            $perpage_record = $offset + (count($currentPageSearchResults) - 1);
        }


        return $data = array(
            'count' => $count_teachers,
            'perpage_record' => $perpage_record,
            'offset' => $offset,
            'records' => $teachers,
            'pagesize' => $pagesize,
        );

    }

    public function ZoneLocationsMappings($zoneid, $locationids)
    {

        if ($locationids == '') {

            $zoneLoacationMappings = $qualifications = DB::table('teacher_location_preferences')
                ->select('location_id', 'teacher_id as id'
                    , 'teacher_location_preferences.id as lpid')
                ->where('zoneid', '=', $zoneid)
                ->groupBy('teacher_id')
                ->get();

        } else {

            $zoneLoacationMappings = $qualifications = DB::table('teacher_location_preferences')
                ->select('location_id', 'teacher_id as id'
                    , 'teacher_location_preferences.id as lpid')
                ->where('zoneid', '=', $zoneid)
                ->whereRaw("FIND_IN_SET(location_id,'$locationids')")
                ->groupBy('teacher_id')
                ->get();

        }

        return $zoneLoacationMappings;

    }

    public function LocationMappings($locationids)
    {

        $loacationMappings = $qualifications = DB::table('teacher_location_preferences')
            ->select('location_id', 'teacher_id as id'
                , 'teacher_location_preferences.id as lpid')
            ->whereRaw("FIND_IN_SET(location_id,'$locationids')")
            ->groupBy('teacher_id')
            ->get();

        return $loacationMappings;

    }

    public function ClassSubjectMapplings($classid, $subjectids)
    {

        if ($subjectids == '') {

            $clsssSubjectMappings = $qualifications = DB::table('class_subject_mappings')
                ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
                ->select('class_subject_mappings.id', 'subject_id', 'subjects.name', 'subjects.id as sid')
                ->where('class_id', '=', $classid)
                ->groupBy('class_subject_mappings.id')
                ->get();
        } else {

            //load mapping against selected class and subjects
            $clsssSubjectMappings = $qualifications = DB::table('class_subject_mappings')
                ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
                ->select('class_subject_mappings.id', 'subject_id', 'subjects.name', 'subjects.id as sid')
                ->where('class_id', $classid)
                ->whereRaw("FIND_IN_SET(class_subject_mappings.subject_id,'$subjectids')")
                ->groupBy('class_subject_mappings.id')
                ->get();

        }

        return $clsssSubjectMappings;

    }

    public function SubjectsMapping($subjectids)
    {

        $clsssSubjectMappings = $qualifications = DB::table('class_subject_mappings')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', 'subject_id', 'subjects.name', 'subjects.id as sid')
            ->whereRaw("FIND_IN_SET(class_subject_mappings.subject_id,'$subjectids')")
            ->groupBy('class_subject_mappings.id')
            ->get();

        return $clsssSubjectMappings;

    }

    public function FilterCSV($filters)
    {

        $list = '';

        $csv = count($filters);
        $count = 1;
        foreach ($filters as $filter) {
            $list .= $filter;
            if ($count <= $csv) {
                $list .= ',';
            }
            $count++;
        }

        return $list;

    }

    public function CSMCSV($mappings)
    {

        $csv_list = '';
        $csv = count($mappings);
        $count = 1;
        foreach ($mappings as $m) {
            $csv_list .= $m->id;
            if ($count <= $csv) {
                $csv_list .= ',';
            }
            $count++;
        }

        return $csv_list;
    }

    public function CreateList($table, $field)
    {

        $list = '';

        if (!empty($table)) {

            $csv = count($table);
            $count = 1;

            foreach ($table as $lp) {

                if ($field == 'location_id') {

                    $list .= $lp->location_id;

                } elseif ($field == 'subject_id') {

                    $list .= $lp->subject_id;
                } elseif ($field == 'institute_id') {

                    $list .= $lp->institute_id;
                } elseif ($field == 'label_id') {

                    $list .= $lp->label_id;

                } elseif ($field == 'tuition_category_id') {

                    $list .= $lp->tuition_category_id;
                }


                if ($count <= $csv) {
                    $list .= ',';
                }
                $count++;
            }

        }
        return $list;
    }

    public function TeacherPreferences($teacherid)
    {


        $tuitions = DB::table('tuition_history')
            ->join('tuition_details', 'tuition_details.id', '=', 'tuition_history.tuition_detail_id')
            ->join('tuitions', 'tuitions.id', '=', 'tuition_details.tuition_id')
            ->where('tuition_history.teacher_id', '=', $teacherid)
            ->get();

        $location_preferences = DB::table('teacher_location_preferences')
            ->select('teacher_location_preferences.location_id')
            ->where('teacher_id', '=', $teacherid)
            ->get();

        $subject_preferences = DB::table('teacher_subject_preferences')
            ->join('class_subject_mappings', 'class_subject_mappings.id', '=', 'teacher_subject_preferences.class_subject_mapping_id')
            ->select('class_subject_mappings.subject_id')
            ->where('teacher_id', '=', $teacherid)
            ->get();

        $institute_preferences = DB::table('teacher_institute_preferences')
            ->select('teacher_institute_preferences.id', 'teacher_institute_preferences.teacher_id',
                'teacher_institute_preferences.institute_id')
            ->where('teacher_institute_preferences.teacher_id', '=', $teacherid)
            ->get();

        $teacher_lables = DB::table('teacher_labels')
            ->select('teacher_labels.label_id', 'teacher_labels.id', 'teacher_labels.teacher_id')
            ->where('teacher_labels.teacher_id', '=', $teacherid)
            ->get();

        $tuition_categories = DB::table('teacher_tuition_categories')
            ->select('teacher_tuition_categories.tuition_category_id')
            ->where('teacher_tuition_categories.teacher_id', '=', $teacherid)
            ->get();

        $location_list = $this->CreateList($location_preferences, 'location_id');
        $subject_list = $this->CreateList($subject_preferences, 'subject_id');
        $institute_list = $this->CreateList($institute_preferences, 'institute_id');
        $label_list = $this->CreateList($teacher_lables, 'label_id');
        $categories_list = $this->CreateList($tuition_categories, 'tuition_category_id');

        //dd($categories_list);

        return $data = array(

            'tuitions' => $tuitions,
            'location_list' => $location_list,
            'subject_list' => $subject_list,
            'institute_list' => $institute_list,
            'label_list' => $label_list,
            'category_list' => $categories_list,
        );


    }

    public function LoadTuitionForMe($filters, $teacherDetail, $teacherPreferences)
    {

        $locations = $teacherPreferences['location_list'];
        $subjects = $teacherPreferences['subject_list'];
        $minfee = $teacherDetail->expected_minimum_fee * 1000;
        $maxfee = $teacherDetail->expected_max_fee * 1000;
        $age = $teacherDetail->age;
        $experience = $teacherDetail->experience;
        $gender = $teacherDetail->gender_id;
        $categories = $teacherPreferences['category_list'];
        $institutes = $teacherPreferences['institute_list'];

        if ($teacherDetail->suitable_timings == 'anytime') {
            $timing = '';
        } else {

            $timing = $teacherDetail->suitable_timings;
        }


        //echo $locations."loca".$subjects."subjec".$institutes."institute". $categories."catego".$age."age".$timing."timing".$minfee."minfee".$maxfee."maxfee";
        if (isset($filters['page'])) {

            $tuitions = DB::select("call  auto_matched_tuitions('$locations','$subjects','$institutes',
                        '$age','$categories','$timing','$minfee','$maxfee','$experience','$gender')");
            $response = $this->LoadMatchedTuitions($tuitions, 9);

        } else {

            $tuitions = DB::select("call  auto_matched_tuitions('$locations','$subjects','$institutes',
                        '$age','$categories','$timing','$minfee','$maxfee','$experience','$gender')");
            $response = $this->LoadMatchedTuitions($tuitions, 9);

        }

        return $response;

    }

    public function LoadMatchedTuitions($tuitions, $pagesize)
    {

        //Get current page form url e.g. &page=9
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($tuitions);
        //Define how many items we want to be visible in each page
        $perPage = $pagesize;
        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $tuitions = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $tuitions->setPath("");

        //Total records
        $count = count($collection);
        //count of records on current page.
        if ($currentPage == 1) {

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        } else {

            $offset = ($perPage * $currentPage) - ($perPage - 1);
            $perpage_record = $offset + (count($currentPageSearchResults) - 1);
        }


        return $data = array(
            'count' => $count,
            'perpage_record' => $perpage_record,
            'offset' => $offset,
            'records' => $tuitions,
            'pagesize' => $pagesize,
        );

    }

    public function LoadTeacchersForStudents($filters)
    {

        $last_filters = session(['last_filters' => $filters]);

        $degree_levels = '';
        $subject_list = '';
        $location_list = '';
        $label_list = '';
        $category_list = '';
        $qualification_list = '';
        $institute_list = '';

        if (isset($filters['reset'])) {

            $firstname = "";
            $zipcode = "";
            $teacher_band_id = "";
            $marital_status_id = "";
            $gender_id = "";
            $father_name = "";
            $expected_minimum_fee = "";
            $cnic_number = "";
            $mobile1 = "";
            $city = "";
            $province = "";
            $email = "";
            $is_active = "";
            $is_approved = "";
            $pagesize = 5;
            $age = "";
            $experience = "";
            $reg_number = "";
            $teacher_id = "";
            $time = "";
            $qualification_list = '';

        } else {

            if (isset($filters['firstname'])) {
                $firstname = $filters['firstname'];
            } else {
                $firstname = "";
            }
            if (isset($filters['zip_code'])) {
                $zipcode = $filters['zip_code'];
            } else {
                $zipcode = "";
            }
            if (isset($filters['teacher_band_id'])) {
                $teacher_band_id = $filters['teacher_band_id'];
            } else {
                $teacher_band_id = "";
            }
            if (isset($filters['marital_status_id'])) {
                $marital_status_id = $filters['marital_status_id'];
            } else {
                $marital_status_id = "";
            }
            if (isset($filters['gender_id'])) {
                $gender_id = $filters['gender_id'];
            } else {
                $gender_id = "";
            }
            if (isset($filters['father_name'])) {
                $father_name = $filters['father_name'];
            } else {
                $father_name = "";
            }
            if (isset($filters['expected_minimum_fee'])) {
                $expected_minimum_fee = $filters['expected_minimum_fee'];
            } else {
                $expected_minimum_fee = 0;
            }
            if (isset($filters['cnic_number'])) {
                $cnic_number = $filters['cnic_number'];
            } else {
                $cnic_number = "";
            }
            if (isset($filters['mobile1'])) {
                $mobile1 = $filters['mobile1'];
            } else {
                $mobile1 = "";
            }
            if (isset($filters['city'])) {
                $city = $filters['city'];
            } else {
                $city = "";
            }
            if (isset($filters['province'])) {
                $province = $filters['province'];
            } else {
                $province = "";
            }
            if (isset($filters['email'])) {
                $email = $filters['email'];
            } else {
                $email = "";
            }

            if (isset($filters['is_active'])) {
                $is_active = $filters['is_active'];
            } else {
                $is_active = "0";
            }

            if (isset($filters['is_approved'])) {
                $is_approved = $filters['is_approved'];
            } else {
                $is_approved = "0";
            }


            if (isset($filters['age'])) {
                $age = $filters['age'];
            } else {
                $age = 0;
            }

            if (isset($filters['experience'])) {
                $experience = $filters['experience'];
            } else {
                $experience = '';
            }

            if (isset($filters['reg_number'])) {
                $reg_number = $filters['reg_number'];
            } else {
                $reg_number = '';
            }

            if (isset($filters['suitable_timings'])) {
                $time = $filters['suitable_timings'];
            } else {
                $time = '';
            }


            if (isset($filters['labels'])) {

                $labels = $filters['labels'];
                $label_list = $this->FilterCSV($labels);

            }

            if (isset($filters['categories'])) {

                $categories = $filters['categories'];
                $category_list = $this->FilterCSV($categories);

            }

            if (isset($filters['insts'])) {

                $institutes = $filters['insts'];
                $institute_list = $this->FilterCSV($institutes);

            }

            //if zone category selected and location not selected then load all zone locations.
            if (!empty($filters['zone']) && empty($filters['locations'])) {

                $zoneid = $filters['zone'];
                $locationid = '';
                //load mapping against selected class
                $zoneLocationMappings = $this->ZoneLocationsMappings($zoneid, $locationid);
                $location_list = $this->CSMCSV($zoneLocationMappings);


            } //if both filters are set.
            elseif (!empty($filters['zone']) && !empty($filters['locations'])) {

                $locations = $filters['locations'];
                $zoneid = $filters['zone'];
                $locationids = $this->FilterCSV($locations);
                //load mapping against selected class and subjects
                $zoneLocationMappings = $this->ZoneLocationsMappings($zoneid, $locationids);
                //if not recod found
                if (empty($zoneLocationMappings)) {
                    $location_list = 'null,'; //send null id
                } else {

                    $location_list = $this->CSMCSV($zoneLocationMappings);
                }


            } elseif (empty($filters['zone']) && !empty($filters['locations'])) {

                $locations = $filters['locations'];
                $locationids = $this->FilterCSV($locations);
                //load mapping against selected class and subjects
                $locationMappings = $this->LocationMappings($locationids);
                $location_list = $this->CSMCSV($locationMappings);

            }

            //if grade category selected and subjects not selected then load all grade subjects.
            if (!empty($filters['class']) && empty($filters['subjects'])) {

                $classid = $filters['class'];
                $subjectid = '';
                //load mapping against selected class
                $clsssSubjectMappings = $this->ClassSubjectMapplings($classid, $subjectid);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);

            } //if both filters are set.
            elseif (!empty($filters['class']) && !empty($filters['subjects'])) {

                $subjects = $filters['subjects'];
                $classid = $filters['class'];
                $subjectids = $this->FilterCSV($subjects);
                //load mapping against selected class and subjects
                $clsssSubjectMappings = $this->ClassSubjectMapplings($classid, $subjectids);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);


            } elseif (empty($filters['class']) && !empty($filters['subjects'])) {

                $subjects = $filters['subjects'];
                $subjectids = $this->FilterCSV($subjects);
                //load mapping against selected class and subjects
                $clsssSubjectMappings = $this->SubjectsMapping($subjectids);
                $subject_list = $this->CSMCSV($clsssSubjectMappings);


            }

            //fetch teacher_id against qualification matched
            if (isset($filters['qual_name']) && !empty($filters['qual_name'])) {

                $q_name = $filters['qual_name'];
                $qualifications = DB::table('teacher_qualifications')
                    ->select('teacher_id')
                    ->where('qualification_name', 'like', '%' . $q_name . '%')
                    ->groupBy('teacher_id')
                    ->get();

                $csv = count($qualifications);
                $count = 1;
                foreach ($qualifications as $qualification) {
                    $qualification_list .= $qualification->teacher_id;
                    if ($count <= $csv) {
                        $qualification_list .= ',';
                    }
                    $count++;
                }

                //if no qualification find against criteria.
                if (empty($qualification_list)) {

                    $qualification_list = 'null'; //send null value.

                }

            }

            if (isset($filters['pagesize']) && $filters['pagesize'] > 0) {

                $pagesize = $filters['pagesize'];

            } else {
                $pagesize = 5;
            }

            if (isset($filters['teacher_id'])) {

                $teacher_id = $filters['teacher_id'];
            } else {

                $teacher_id = '';
            }


        }

        //teacher search filters
        $teachers = DB::select("call  load_student_teachers('$firstname','$zipcode','$teacher_band_id','$marital_status_id',
                        '$gender_id','$father_name','$expected_minimum_fee','$cnic_number','$mobile1','$city','$province',
                        '$email','$degree_levels','$subject_list','$location_list','$is_active','$is_approved','$label_list'
                        ,'$age','$experience','$reg_number','$teacher_id','$category_list','$qualification_list'
                        ,'$institute_list','$time')");

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($teachers);
        //Define how many items we want to be visible in each page
        $perPage = $pagesize;
        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $teachers = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $teachers->setPath('search-teacher');
        //Total records
        $count_teachers = count($collection);
        //count of records on current page.
        if ($currentPage == 1) {

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        } else {

            $offset = ($perPage * $currentPage) - ($perPage - 1);
            $perpage_record = $offset + (count($currentPageSearchResults) - 1);
        }


        return $data = array(
            'count' => $count_teachers,
            'perpage_record' => $perpage_record,
            'offset' => $offset,
            'records' => $teachers,
            'pagesize' => $pagesize,
        );

    }

    public function SetDateFilters($filters)
    {

        // get the current time
        $current = Carbon::now();
        if (empty($filters)) {
            //$tuition_start_date = date('Y-m-d',strtotime($current->subDays(7)));
            $tuition_start_date = date('Y-m-d', strtotime(Carbon::now()));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } elseif (isset($filters['start_date']) && isset($filters['end_date'])) {

            $tuition_start_date = $filters['start_date'];
            $tuition_end_date = $filters['start_date'];

        } elseif ($filters == 7) {

            $tuition_start_date = date('Y-m-d', strtotime($current->subDays(7)));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } elseif ($filters == 14) {

            $tuition_start_date = date('Y-m-d', strtotime($current->subDays(14)));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } elseif ($filters == 30) {

            $tuition_start_date = date('Y-m-d', strtotime($current->subDays(30)));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } elseif ($filters == 90) {

            $tuition_start_date = date('Y-m-d', strtotime($current->subDays(90)));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));
        } elseif ($filters == 0) {

            $tuition_start_date = date('Y-m-d', strtotime(Carbon::now()));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } else {
            $tuition_start_date = "";
            $tuition_end_date = "";
        }

        return array(
            'tuition_start_date' => $tuition_start_date,
            'tuition_end_date' => $tuition_end_date,
            'tuition_filter' => $filters
        );

    }

    public function CreateGlobalList($teacherid)
    {

        $obj = new teacher_global();
        $obj->teacher_id = $teacherid;
        $obj->created_at = date('Y-m-d H:i:s', time());
        $obj->updated_at = date('Y-m-d H:i:s', time());
        $obj->save();


    }


    public function DeleteGT($request)
    {

        $globalTeachers = explode(',', $request['ids']);

        for ($j = 0; $j < count($globalTeachers); $j++) {

            $globalTeacherId = $globalTeachers[$j];
            $globalTeacher = teacher_global::where('teacher_id', $globalTeacherId)->first();
            $globalTeacher->delete();

        }

    }

    public function getBroadCastTeacher()
    {

        $teachers = DB::table('teacher_globals')
            ->orderBy('teachers.teacher_band_id', 'asc')
            ->leftjoin('teachers', 'teachers.id', '=', 'teacher_globals.teacher_id')
            ->leftjoin('teacher_bands', 'teachers.teacher_band_id', '=', 'teacher_bands.id')
            ->leftjoin('teacher_qualifications', 'teacher_qualifications.teacher_id', '=', 'teachers.id')
            ->select('teachers.*', 'teacher_globals.id as global_id', 'teacher_bands.name as band_name',
                DB::raw("GROUP_CONCAT(DISTINCT(qualification_name) separator ',') as qualifications"))
            ->groupby('teachers.id')
            ->get();

        return $teachers;
    }


    public function TeacherLocations($teacher_id)
    {

        $query1 = "SELECT locations.id ,locations as location_name,zone_id,preference.location_id FROM `locations` ";
        $query1 .= "LEFT OUTER JOIN ( SELECT * FROM teacher_location_preferences tlp  WHERE tlp.teacher_id = $teacher_id ) ";
        $query1 .= "preference ON preference.location_id = locations.id WHERE locations.zone_id = 1 ";

        $query2 = "SELECT locations.id ,locations as location_name,zone_id,preference.location_id FROM `locations` ";
        $query2 .= "LEFT OUTER JOIN ( SELECT * FROM teacher_location_preferences tlp  WHERE tlp.teacher_id = $teacher_id ) ";
        $query2 .= "preference ON preference.location_id = locations.id WHERE locations.zone_id = 2 ";

        $query3 = "SELECT locations.id ,locations as location_name,zone_id,preference.location_id FROM `locations` ";
        $query3 .= "LEFT OUTER JOIN ( SELECT * FROM teacher_location_preferences tlp  WHERE tlp.teacher_id = $teacher_id ) ";
        $query3 .= "preference ON preference.location_id = locations.id WHERE locations.zone_id = 3 ";

        $query4 = "SELECT locations.id ,locations as location_name,zone_id,preference.location_id FROM `locations` ";
        $query4 .= "LEFT OUTER JOIN ( SELECT * FROM teacher_location_preferences tlp  WHERE tlp.teacher_id = $teacher_id ) ";
        $query4 .= "preference ON preference.location_id = locations.id WHERE locations.zone_id = 4 ";

        $query5 = "SELECT locations.id ,locations as location_name,zone_id,preference.location_id FROM `locations` ";
        $query5 .= "LEFT OUTER JOIN ( SELECT * FROM teacher_location_preferences tlp  WHERE tlp.teacher_id = $teacher_id ) ";
        $query5 .= "preference ON preference.location_id = locations.id WHERE locations.zone_id = 5 ";

        $z1L = DB::select(DB::raw($query1));
        $z2L = DB::select(DB::raw($query2));
        $z3L = DB::select(DB::raw($query3));
        $z4L = DB::select(DB::raw($query4));
        $z5L = DB::select(DB::raw($query5));

        return $zoneLocations = array(
            'z1L' => $z1L,
            'z2L' => $z2L,
            'z3L' => $z3L,
            'z4L' => $z4L,
            'z5L' => $z5L,
        );
    }

    public function SaveLocationPreferrence($preference)
    {

        $teacher_id = $preference['teacher_id'];
        //delete last selected locations
        $preferedLocation = location_preference::where('teacher_id', $teacher_id);
        if (isset($preferedLocation)) {

            $preferedLocation->delete();
        }

        if (isset($preference['zilocations'])) {

            $locations = $preference['zilocations'];
            //add selected locations
            for ($j = 0; $j < count($locations); $j++) {

                $obj = new location_preference();
                $obj->teacher_id = $teacher_id;
                $obj->location_id = $locations[$j];
                $obj->zoneid = 1;
                $obj->created_at = date('Y-m-d H:i:s', time());
                $obj->updated_at = date('Y-m-d H:i:s', time());
                $obj->save();
            }

        }

        if (isset($preference['z2locations'])) {

            $locations = $preference['z2locations'];
            //add selected locations
            for ($j = 0; $j < count($locations); $j++) {

                $obj = new location_preference();
                $obj->teacher_id = $teacher_id;
                $obj->location_id = $locations[$j];
                $obj->zoneid = 2;
                $obj->created_at = date('Y-m-d H:i:s', time());
                $obj->updated_at = date('Y-m-d H:i:s', time());
                $obj->save();
            }

        }


        if (isset($preference['z3locations'])) {

            $locations = $preference['z3locations'];
            //add selected locations
            for ($j = 0; $j < count($locations); $j++) {

                $obj = new location_preference();
                $obj->teacher_id = $teacher_id;
                $obj->location_id = $locations[$j];
                $obj->zoneid = 3;
                $obj->created_at = date('Y-m-d H:i:s', time());
                $obj->updated_at = date('Y-m-d H:i:s', time());
                $obj->save();
            }

        }

        if (isset($preference['z4locations'])) {

            $locations = $preference['z4locations'];
            //add selected locations
            for ($j = 0; $j < count($locations); $j++) {

                $obj = new location_preference();
                $obj->teacher_id = $teacher_id;
                $obj->location_id = $locations[$j];
                $obj->zoneid = 4;
                $obj->created_at = date('Y-m-d H:i:s', time());
                $obj->updated_at = date('Y-m-d H:i:s', time());
                $obj->save();
            }

        }

        if (isset($preference['z5locations'])) {

            $locations = $preference['z5locations'];
            //add selected locations
            for ($j = 0; $j < count($locations); $j++) {

                $obj = new location_preference();
                $obj->teacher_id = $teacher_id;
                $obj->location_id = $locations[$j];
                $obj->zoneid = 5;
                $obj->created_at = date('Y-m-d H:i:s', time());
                $obj->updated_at = date('Y-m-d H:i:s', time());
                $obj->save();
            }

        }


    }

    public function TeacherDetails($id)
    {

        $details = DB::table('teachers')
            ->join('cities', 'cities.id', '=', 'teachers.city')
            ->select('teachers.*', 'cities.name as city_name')
            ->where('teachers.id', '=', $id)
            ->first();

        $qualification = DB::table('teacher_qualifications')
            ->select(DB::raw("GROUP_CONCAT(DISTINCT(qualification_name) separator ',') as name"))
            ->where('teacher_id', '=', $id)
            ->groupby('teacher_id')
            ->first();

        $qDetails = DB::table('teacher_qualifications')->where('teacher_id', '=', $id)->get();

        $subjects = DB::select("call  teacher_subject_preferences('$id')");

        $institutes = DB::table('teacher_institute_preferences')
            ->select('institutes.name','institutes.logo')
            ->join('institutes','institutes.id','=','teacher_institute_preferences.institute_id')
            ->where('teacher_id', '=', $id)
            ->get();

        $tuitionCategories = DB::table('teacher_tuition_categories')
            ->select('tuition_categories.name')
            ->join('tuition_categories', 'tuition_categories.id', '=', 'teacher_tuition_categories.tuition_category_id')
            ->where('teacher_id', '=', $id)
            ->get();

        $locations = DB::select("call  teacher_locations('$id')");

        return array('details' => $details, 'qualifications' => $qualification, 'subjects' => $subjects,
            'institutes' => $institutes, 'locations' => $locations,'qdetails'=>$qDetails,'tuitionCategories'=>$tuitionCategories);

    }

}