<?php

namespace App\Http\Controllers;

use App\teacher_institute_preference;
use App\teacher_tuition_category;
use App\User;
use App\teacher_label;
use App\Teacher;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Hash;
use File;
use Session;
use Acme\Mailers\Mailers;
use Acme\Config\Constants;
use Illuminate\Support\Facades\Route;
use App\global_note;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminController extends Controller
{

    protected $mailers;
    protected $CurrentUri;

    public function __construct(Mailers $mailers, Constants $constants)
    {

        $this->mailers = $mailers;
        $this->BulkEmailTemplate = $constants->BulkEmailTemplate();
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();

    }

    public function teacherPost(Request $request)
    {

        //get form data
        $OrderForm = Request::all();

        $cnic_front = Request::file('cnic_front_image');
        $cnic_back = Request::file('cnic_back_image');
        $teacher_photo = Request::file('teacher_photo');
        $electricity_bill = Request::file('electricity_bill');

        $userid = $OrderForm['user_id'];

        if(isset($OrderForm['email'])){
            $email = $OrderForm['email'];
        }else{
            $email='';
        }

        $password = $OrderForm['password'];
        $fullname = $OrderForm['fullname'];
        if(isset($OrderForm['tid_update'])&&!empty($OrderForm['tid_update'])){
            $tid_update = $OrderForm['tid_update'];
        }else{

            $tid_update="";
        }


        //if user already created for teacher
        if (isset($userid) && $userid > 0) {

            $tid_update = $tid_update;
            $insertedId = $userid;
        }
        //create user for registered teacher
        elseif(!empty($email)&&!empty($password)) {

            $result = DB::table('teachers')->where('email', '=', $email)->get();

            if (!empty($result)) {
                return response()->json(['success' => 'This email has already been taken.', 'teacherid' => '']);
            }


            //create user with role and permisssion
            $user = new User;
            $user->name = $fullname;
            $user->email = $email;
            $user->password = Hash::make($password);

            if ($OrderForm['is_active'] == 1) {
                $user->confirmed = 1;
            } else {
                $user->confirmed = 0;
            }

            $user->save();

            //adding roles to a user
            $user->assignRole('teacher');
            //adding permissions to a user
            $user->givePermissionTo('teacher postal');
            //user id
            $insertedId = $user->id;
            $tid_update = $tid_update;

        }
        //create teacher without user if email and password not provided.
        else{
            $tid_update = $tid_update;
            $insertedId = "";
        }



        //register teacher
        $teacherid = $this->save_teacher($insertedId, $OrderForm, $cnic_front, $cnic_back, $tid_update, $teacher_photo,$electricity_bill);

        //save teacher labels
        if (isset($OrderForm['labels']) && $OrderForm['label_change'] == 'change') {

            $this->save_teacher_labels($OrderForm, $teacherid);
        }

        //save teacher tuition categories
        if (isset($OrderForm['tuition_category_id']) && $OrderForm['category_change'] == 'change') {

            $this->tuitionCategories($OrderForm, $teacherid);
        }

        //save teacher preferred institute
        if (isset($OrderForm['institute_id']) && $OrderForm['institute_change'] == 'change') {

            $this->preferredInstitute($OrderForm, $teacherid);
        }

        //create directory for teacher's document
        if (!empty($OrderForm['cnic_front_image']) && !empty($OrderForm['cnic_back_image'])) {

            //$this->upload_teacher_cnic_image($cnic_front,$cnic_back, $teacherid);

            $cnicfront = $cnic_front->getClientOriginalName();
            $cnicback = $cnic_back->getClientOriginalName();
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

        if (!empty($OrderForm['teacher_photo'])) {

            $teacherphoto = $teacher_photo->getClientOriginalName();
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

        if (!empty($OrderForm['electricity_bill'])) {

            $electricitybill = $electricity_bill->getClientOriginalName();
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

        if (!empty($OrderForm['submitbtnValue']) && $OrderForm['submitbtnValue'] == 'saveadd') {

            return response()->json(['success' => 'saveandadd', 'teacherid' => '']);

        } else {

            return response()->json(['success' => 'save', 'teacherid' => '']);

        }


    }

    //  creating teacher
    public function save_teacher($user_id, $OrderForm, $cnic_front, $cnic_back, $tid_update, $teacher_photo,$electricity_bill)
    {

        $gender_id = $OrderForm['gender_id'];
        $marital_status_id = $OrderForm['marital_status_id'];


        if (isset($OrderForm['firstname'])) {
            $firstname = $OrderForm['firstname'];
        } else {
            $firstname = '';
        }
        if (isset($OrderForm['lastname'])) {
            $lastname = $OrderForm['lastname'];
        } else {
            $lastname = '';
        }

        if (isset($OrderForm['fullname'])) {
            $fullname = $OrderForm['fullname'];
        } else {
            $fullname = '';
        }

        if (isset($OrderForm['added_by'])) {

            $added_by = $OrderForm['added_by'];

        } else {
            $added_by = '';
        }

        if (isset($OrderForm['email'])) {
            $email = $OrderForm['email'];
        } else {
            $email = '';
        }
        if (isset($OrderForm['password'])) {
            $password = Hash::make($OrderForm['password']);
            $pwd = $OrderForm['password'];
        } else {
            $password = '';
            $pwd='';
        }
        if (isset($OrderForm['no_of_children'])) {
            $no_of_children = $OrderForm['no_of_children'];
        } else {
            $no_of_children = 0;
        }

        if (isset($OrderForm['suitable_timings'])) {
            $suitable_timings = $OrderForm['suitable_timings'];
        } else {
            $suitable_timings = '';
        }

        if(isset($OrderForm['address_line1'])){
            $address_line1 = $OrderForm['address_line1'];
        }else{
            $address_line1='';
        }

        if(isset($OrderForm['address_line1_p'])){
            $address_line1_p = $OrderForm['address_line1_p'];
        }else{
            $address_line1_p='';
        }

        if(isset($OrderForm['address_line2'])){
            $address_line2 = $OrderForm['address_line2'];
        }else{
            $address_line2='';
        }

        if(isset($OrderForm['address_line2_p'])){
            $address_line2_p = $OrderForm['address_line2_p'];
        }else{
            $address_line2_p='';
        }

        if(isset($OrderForm['province'])){
            $province = $OrderForm['province'];
        }else{
            $province=0;
        }

        if(isset($OrderForm['province_p'])){
            $province_p = $OrderForm['province_p'];
        }else{
            $province_p=0;
        }

        if(!empty($OrderForm['age'])){
            $age = $OrderForm['age'];
        }else{
            $age=0;
        }

        if(!empty($OrderForm['livingin'])){
            $livingin = $OrderForm['livingin'];
        }else{
            $livingin=0;
        }


        if(isset($OrderForm['city'])){
            $city = $OrderForm['city'];
        }else{
            $city=0;
        }
        if(isset($OrderForm['city_p'])){
            $city_p = $OrderForm['city_p'];
        }else{
            $city_p=0;
        }

        if(isset($OrderForm['zip_code'])){
            $zip_code = $OrderForm['zip_code'];
        }else{
            $zip_code='';
        }

        if(isset($OrderForm['zip_code_p'])){
            $zip_code_p = $OrderForm['zip_code_p'];
        }else{
            $zip_code_p='';
        }

        if(isset($OrderForm['country'])){
            $country = $OrderForm['country'];
        }else{
            $country='';
        }

        if(isset($OrderForm['country_p'])){
            $country_p = $OrderForm['country_p'];
        }else{
            $country_p='';
        }

        if(isset($OrderForm['strength'])){
            $strength = $OrderForm['strength'];
        }else{
            $strength='';
        }

        if(isset($OrderForm['reference_for_rent'])){
            $reference_for_rent = $OrderForm['reference_for_rent'];
        }else{
            $reference_for_rent='';
        }

        if(isset($OrderForm['landline'])){
            $landline = $OrderForm['landline'];
        }else{
            $landline='';
        }

        if(isset($OrderForm['mobile1'])){
            $mobile1 = $OrderForm['mobile1'];
        }else{
            $mobile1='';
        }

        if(isset($OrderForm['personal_contactno2'])){
            $personal_contactno2 = $OrderForm['personal_contactno2'];
        }else{
            $personal_contactno2='';
        }

        if(isset($OrderForm['mobile2'])){
            $mobile2 = $OrderForm['mobile2'];
        }else{
            $mobile2='';
        }

        if(isset($OrderForm['emergency_contact_no'])){
            $emergency_contact_no = $OrderForm['emergency_contact_no'];
        }else{
            $emergency_contact_no='';
        }

        if(isset($OrderForm['guardian_contact_no'])){
            $guardian_contact_no = $OrderForm['guardian_contact_no'];
        }else{
            $guardian_contact_no='';
        }

        if(isset($OrderForm['reference_gurantor'])){
            $reference_gurantor = $OrderForm['reference_gurantor'];
        }else{
            $reference_gurantor='';
        }

        if(isset($OrderForm['terms'])){
            $terms = $OrderForm['terms'];
        }else{
            $terms='';
        }

        if(isset($OrderForm['expected_minimum_fee'])){
            $expected_minimum_fee = $OrderForm['expected_minimum_fee'];
            $expected_max_fee = 0;
            switch ($expected_minimum_fee) {
                case 4:
                    $expected_max_fee=8;
                    break;

                case 8:
                    $expected_max_fee=12;
                    break;

                case 12:
                    $expected_max_fee=15;
                    break;

                case 15:
                    $expected_max_fee=20;
                    break;

                case 20:
                    $expected_max_fee=30;
                    break;

                case 30:
                    $expected_max_fee=40;
                    break;

            }


            }else{

            $expected_minimum_fee=0;
            $expected_max_fee=0;
        }


        //$no_of_children = $OrderForm['no_of_children'];
        $registeration_no = $OrderForm['registeration_no'];
        $father_name = $OrderForm['father_name'];

        $religion = $OrderForm['religion'];

        $cnic_number = $OrderForm['cnic_number'];

        //format date to mysql
        $var = $OrderForm['dob'];
        $date = str_replace('/', '-', $var);
        $dob = date('Y-m-d', strtotime($date));

        $created_by = 'admin';
        $created_at = date('Y-m-d H:i:s', time());
        $updated_by = 'admin';
        $updated_at = date('Y-m-d H:i:s', time());

        if(isset($OrderForm['other_detail'])){

            $other_detail = $OrderForm['other_detail'];

        }else{

            $other_detail = '';

        }

        if(isset($OrderForm['admin_remarks'])){

            $admin_remarks = $OrderForm['admin_remarks'];

        }else{

            $admin_remarks = '';

        }

        if (isset($OrderForm['cnic_front_image'])) {
            $cnic_front_image = $cnic_front->getClientOriginalName();

        } else {
            $cnic_front_image = $OrderForm['front_image'];
        }
        if (isset($OrderForm['cnic_back_image'])) {
            $cnic_back_image = $cnic_back->getClientOriginalName();
        } else {
            $cnic_back_image = $OrderForm['back_image'];
        }

        if (isset($OrderForm['teacher_photo'])) {
            $teacher_photo = $teacher_photo->getClientOriginalName();
        } else {
            $teacher_photo = $OrderForm['photo'];
        }

        if (isset($OrderForm['electricity_bill'])) {

            $electricity_bill = $electricity_bill->getClientOriginalName();
        } else {
            $electricity_bill = $OrderForm['bill'];
        }

        if (isset($OrderForm['is_active'])) {
            $isactive = $OrderForm['is_active'];
        } else {
            $isactive = '2';
        }

        if (isset($OrderForm['is_approved'])) {

            $isapproved = $OrderForm['is_approved'];


        } else {
            $isapproved = 0;
        }

        if(!empty($OrderForm['teacher_band_id'])){
            $teacher_band_id = $OrderForm['teacher_band_id'];
        }else{
            $teacher_band_id=0;
        }

        if(!empty($OrderForm['experience'])){
            $experience = $OrderForm['experience'];
        }else{
            $experience=0;
        }

        if(!empty($OrderForm['about_us'])){
            $about_us = $OrderForm['about_us'];
        }else{
            $about_us=0;
        }

        if(!empty($OrderForm['past_experience'])){
            $past_experience = $OrderForm['past_experience'];
        }else{
            $past_experience=0;
        }

        if(isset($OrderForm['accept'])){
            $accept = $OrderForm['accept'];
        }else{
            $accept=0;
        }

        if(!empty($OrderForm['visited'])){
            $visited = $OrderForm['visited'];
        }else{
            $visited=0;
        }


        if ($tid_update == "") {// add new teacher

            $obj = new Teacher();
            $obj->firstname = $firstname;
            $obj->lastname = $lastname;
            $obj->fullname = $fullname;
            $obj->added_by = $added_by;
            $obj->user_id = $user_id;
            $obj->teacher_band_id = $teacher_band_id;
            $obj->marital_status_id = $marital_status_id;
            $obj->gender_id = $gender_id;
            $obj->registeration_no = $registeration_no;
            $obj->father_name = $father_name;
            $obj->expected_minimum_fee = $expected_minimum_fee;
            $obj->expected_max_fee = $expected_max_fee;
            $obj->religion = $religion;
            $obj->strength = $strength;
            $obj->no_of_children = $no_of_children;
            $obj->cnic_number = $cnic_number;
            $obj->cnic_front_image = $cnic_front_image;
            $obj->cnic_back_image = $cnic_back_image;
            $obj->email = $email;
            $obj->password = $pwd;
            $obj->dob = $dob;
            $obj->landline = $landline;
            $obj->mobile1 = $mobile1;
            $obj->personal_contactno2 = $personal_contactno2;
            $obj->mobile2 = $mobile2;
            $obj->address_line1 = $address_line1;
            $obj->address_line1_p = $address_line1_p;
            $obj->address_line2 = $address_line2;
            $obj->address_line2_p = $address_line2_p;
            $obj->city = $city;
            $obj->city_p = $city_p;
            $obj->province = $province;
            $obj->province_p = $province_p;
            $obj->zip_code = $zip_code;
            $obj->zip_code_p = $zip_code_p;
            $obj->country = $country;
            $obj->country_p = $country_p;
            $obj->other_detail = $other_detail;
            $obj->admin_remarks = $admin_remarks;
            $obj->is_active = $isactive;
            $obj->is_approved = $isapproved;
            $obj->experience = $experience;
            $obj->suitable_timings = $suitable_timings;
            $obj->age = $age;
            $obj->livingin = $livingin;
            $obj->reference_for_rent = $reference_for_rent;
            $obj->emergency_contact_no = $emergency_contact_no;
            $obj->guardian_contact_no = $guardian_contact_no;
            $obj->reference_gurantor = $reference_gurantor;
            $obj->about_us = $about_us;
            $obj->past_experience = $past_experience;
            $obj->visited = $visited;
            $obj->accept = $accept;

            $obj->created_by = $created_by;
            $obj->created_at = $created_at;
            $obj->updated_by = $updated_by;
            $obj->updated_at = $updated_at;
            $obj->teacher_photo = $teacher_photo;
            $obj->electricity_bill = $electricity_bill;
            $obj->terms = $terms;


            $obj->save();
            return $teacher_id = $obj->id;

        }

        else {

           //echo $tid_update; die(); //update teacher

            $obj = Teacher::find($tid_update);


            $obj->firstname = $firstname;
            $obj->lastname = $lastname;
            if(!empty($email)){$obj->email = $email;}
            $obj->fullname = $fullname;
            $obj->added_by = $added_by;
            $obj->password = $pwd;
            $obj->user_id = $user_id;
            $obj->teacher_band_id = $teacher_band_id;
            $obj->marital_status_id = $marital_status_id;
            $obj->gender_id = $gender_id;
            $obj->registeration_no = $registeration_no;
            $obj->father_name = $father_name;
            $obj->expected_minimum_fee = $expected_minimum_fee;
            $obj->expected_max_fee = $expected_max_fee;
            $obj->religion = $religion;
            $obj->strength = $strength;
            $obj->no_of_children = $no_of_children;
            $obj->cnic_number = $cnic_number;
            $obj->cnic_front_image = $cnic_front_image;
            $obj->cnic_back_image = $cnic_back_image;
            $obj->dob = $dob;
            $obj->landline = $landline;
            $obj->mobile1 = $mobile1;
            $obj->personal_contactno2 = $personal_contactno2;
            $obj->mobile2 = $mobile2;
            $obj->address_line1 = $address_line1;
            $obj->address_line1_p = $address_line1_p;
            $obj->address_line2 = $address_line2;
            $obj->address_line2_p = $address_line2_p;
            $obj->city = $city;
            $obj->city_p = $city_p;
            $obj->province = $province;
            $obj->province_p = $province_p;
            $obj->zip_code = $zip_code;
            $obj->zip_code_p = $zip_code_p;
            $obj->country = $country;
            $obj->country_p = $country_p;
            $obj->other_detail = $other_detail;
            $obj->admin_remarks = $admin_remarks;
            $obj->is_active = $isactive;
            $obj->is_approved = $isapproved;
            $obj->updated_at = $updated_at;
            $obj->teacher_photo = $teacher_photo;
            $obj->electricity_bill = $electricity_bill;
            $obj->terms = $terms;
            $obj->experience = $experience;
            $obj->suitable_timings = $suitable_timings;
            $obj->age = $age;
            $obj->livingin = $livingin;
            $obj->reference_for_rent = $reference_for_rent;
            $obj->emergency_contact_no = $emergency_contact_no;
            $obj->guardian_contact_no = $guardian_contact_no;
            $obj->reference_gurantor = $reference_gurantor;
            $obj->about_us = $about_us;
            $obj->past_experience = $past_experience;
            $obj->visited = $visited;
            $obj->accept = $accept;

            $obj->save();

            //send email to approved teacher if noe marked to approved
            if( ($isapproved == 1)&& (!empty($email)) ){

                $this->mailers->MarkApproved($tid_update);
            }

            //if password changed
            if($obj->password != $pwd){

                $this->mailers->ResetPassword($tid_update,$pwd);

            }

            //update user confirmed status
            $user = User::find($obj->user_id);
            if(isset($user)){

                if ($obj->is_active == 1) {
                    $user->confirmed = 1;
                } else {

                    $user->confirmed = 0;
                }

                $user->name = $fullname;
                $user->password = $password;
                $user->save();

            }

            return $teacher_id = $tid_update;

        }


    }

    public function preferredInstitute($OrderForm, $teacherid){

        if (isset($OrderForm['institute_id'])) {

            if ($teacherid != '') {

                //find and delete previous tuition categories.
                $obj = DB::table('teacher_institute_preferences')->where('teacher_id', '=', $teacherid)->get();


                if (!empty($obj)) {

                    DB::table('teacher_institute_preferences')->where('teacher_id', '=', $teacherid)->delete();

                }

            }

            //add new selected preferred institutes.
            $len = count($OrderForm['institute_id']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new teacher_institute_preference();
                $obj->institute_id = $OrderForm['institute_id'][$j];
                $obj->teacher_id = $teacherid;
                $obj->save();
            }

        }

    }

    public function tuitionCategories($OrderForm, $teacherid){

        if (isset($OrderForm['tuition_category_id'])) {

            if ($teacherid != '') {

                //find and delete previous tuition categories.
                $obj = DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacherid)->get();


                if (!empty($obj)) {

                    DB::table('teacher_tuition_categories')->where('teacher_id', '=', $teacherid)->delete();

                }

            }

            //add new selected tuition categories.
            $len = count($OrderForm['tuition_category_id']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new teacher_tuition_category();
                $obj->tuition_category_id = $OrderForm['tuition_category_id'][$j];
                $obj->teacher_id = $teacherid;
                $obj->save();
            }

        }

    }

    public function save_teacher_labels($OrderForm, $teacherid)
    {

        if (isset($OrderForm['labels'])) {

            if ($teacherid != '') {

                //find and delete previous tuition labels.
                $obj = DB::table('teacher_labels')->where('teacher_id', '=', $teacherid)->get();


                if (!empty($obj)) {

                    DB::table('teacher_labels')->where('teacher_id', '=', $teacherid)->delete();

                }

            }

            $len = count($OrderForm['labels']);
            for ($j = 0; $j < $len; $j++) {
                $obj = new teacher_label();
                $obj->label_id = $OrderForm['labels'][$j];
                $obj->teacher_id = $teacherid;
                $obj->save();
            }

        }

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

    Public function userrole()
    {

        $user = Auth::user();

        if($user->confirmed==0){

            session(['account_message' => 'Account still not activated.']);
            Auth::logout();
        }

        //Route to respetive user role assign permission.
        if ($user->can('teacher postal')) {

            return redirect()->to('teacher');

        } elseif ($user->can('student postal')) {

            return redirect()->to('search-teacher');

        } elseif ($user->can('administrator')) {

            return redirect()->to('admin');
        }

        /**
         * //creating role
         * $role = Role::create(['name' => 'student']);
         * //creating permission
         * $permission = Permission::create(['name' => 'student postal']);
         *
         * //adding permission to a role
         * $role->givePermissionTo('student postal');
         *
         * //adding roles to a user
         * $user->assignRole('student');
         *
         * //adding permissions to a user
         * $user->givePermissionTo('student postal');
         * **/


    }

    // Global Note Pad

    public function GlobalNotePad(){

        $current_route = $this->CurrentUri;
        try {
            $notes = global_note::findOrFail(1);
        }catch (ModelNotFoundException $ex) {
            $notes = '';
        }
        return view('global_note_pad',compact('current_route','notes'));

    }

    public function SaveGlobalNotePad(Request $request){

        $request = Request::all();
        if(isset($request['save'])){

            $obj =  global_note::findOrFail(1);
            $obj->new_arrivals = $request['new_arrivals'];
            $obj->pending_retry = $request['pending_retry'];
            $obj->created_at = date('Y-m-d H:i:s', time());
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

            Session::flash('notes', 'Notes save successfully!');

        }else{

            $obj =  global_note::findOrFail(1);
            $obj->new_arrivals = '';
            $obj->pending_retry = '';
            $obj->save();

            Session::flash('notes', 'Notes deleted successfully!');

        }

        $notes =  global_note::find(1);


        $current_route = $this->CurrentUri;
        return view('global_note_pad',compact('current_route','notes'));

    }


    public function showStudentPortal()
    {

        return view('student');

    }

    public function showAdminPortal()
    {

        return view('home');

    }


}
