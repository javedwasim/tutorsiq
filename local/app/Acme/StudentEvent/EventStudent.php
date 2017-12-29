<?php

namespace Acme\StudentEvent;

use App\student_teacher;
use App\Http\Controllers\AdminController;
use Auth;
use Mail;
use Request;
use Validator;
use File;
use Storage;
use Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Acme\Mailers\Mailers;
use Acme\Config\Constants;
use App\teacher_tuition_categorie;
use App\Tuition;
use App\tuition_detail;
use App\tuition_institute_preference;
use App\teacher_bookmark;


class EventStudent
{
    protected $teacherProfile;


    public function __construct(Mailers $mailers, Constants $constants)
    {
        $this->teacherProfile = new AdminController($mailers, $constants);

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
        //Changed age from SP.
        //AND (age_p=0 OR (DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365)>=age_p)
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

    public function SaveStudentTeachers($teacherid, $studentid)
    {

        $obj = new student_teacher;
        $obj->teacherid = $teacherid;
        $obj->studentid = $studentid;
        $obj->save();
    }

    public function SaveTuition($Tuition, $teacherid, $studentid)
    {

        $timestamp = Carbon::now();
        $timestamp->toDateTimeString();
        //get tuition code of last inserted tuition
        $tuition_code = Tuition::orderby('id', 'desc')->first()->toArray();
        $tuition_code = $tuition_code['tuition_code'];
        //new tuition code by inrementing one.
        $code = "T" . date('ym'); //first pasrt of tuition code i.e. current year and month
        $newStr = ++$tuition_code;
        $last_part = substr($newStr, 5); //last part of string i.e. incremented part of string
        $new_tuition_code = substr_replace($last_part, $code, 0, 0);

        $tuition_code = $new_tuition_code;
        $contact_person = $Tuition['contact_person'];
        $no_of_students = $Tuition['no_of_students'];
        $location_id = $Tuition['location_id'];
        $address = $Tuition['address'];
        $contact_no = $Tuition['contact_no'];
        $contact_no2 = $Tuition['contact_no2'];

        if (isset($Tuition['tuition_date']) && $Tuition['tuition_date'] != '') {

            $tuition_date = $this->ConvertDateFormat($Tuition['tuition_date']);

        } else {

            $tuition_date = '0000-00-00';
        }

        if (isset($Tuition['is_active'])) {
            $is_active = $Tuition['is_active'];
        } else {
            $is_active = 0;
        }
        if (isset($Tuition['is_approved'])) {

            $is_approved = $Tuition['is_approved'];

        } else {

            $is_approved = 0;

        }

        $tuition_obj = new Tuition();

        $tuition_obj->tuition_code = $tuition_code;
        $tuition_obj->student_id = $studentid;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->tuition_status_id = 13; //set tuition status as teacher require
        $tuition_obj->location_id = $location_id;
        $tuition_obj->is_active = $is_active;
        $tuition_obj->is_approved = $is_approved;
        $tuition_obj->contact_person = $contact_person;
        $tuition_obj->tuition_date = $tuition_date;
        $tuition_obj->contact_no = $contact_no;
        $tuition_obj->contact_no2 = $contact_no2;
        $tuition_obj->address = $address;
        $tuition_obj->created_at = $timestamp;
        $tuition_obj->updated_at = $timestamp;
        $tuition_obj->created_by = 'student';

        $tuition_obj->save();

        $tuition_id = $tuition_obj->id;

        //save tuition subjects if subjects selected
        $this->SaveTuitionDetails($Tuition['csm'], $tuition_id);
        //save tuition institutes
        $this->SaveTuitionInstitutePreference($Tuition['institutes'], $tuition_id);
        //bookmark selected teacher
        $this->BookmarkTeacher($tuition_id, $teacherid);

        return "save";


    }

    public function SaveTuitionDetails($csm, $tuition_id)
    {

        for ($j = 0; $j < count($csm); $j++) {

            $tdetail_obj = new tuition_detail();
            $tdetail_obj->tuition_id = $tuition_id;
            $tdetail_obj->class_subject_mapping_id = $csm[$j];
            $tdetail_obj->save();

        }

    }

    public function SaveTuitionInstitutePreference($institutes, $tuition_id)
    {

        if (isset($Tuition['institutes'])) {

            $len = count($Tuition['institutes']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new tuition_institute_preference();
                $obj->institute_id = $institutes[$j];
                $obj->tuition_id = $tuition_id;
                $obj->save();

            }

        }

    }

    public function BookmarkTeacher($tuitionid, $teacher_id)
    {

        $obj = teacher_bookmark::where('tuition_id', $tuitionid)->where('teacher_id', $teacher_id)->first();

        // if teahcer is not already bookmarked.
        if (!isset($obj->id)) {

            $obj = new teacher_bookmark();
            $obj->tuition_id = $tuitionid;
            $obj->teacher_id = $teacher_id;
            $save = $obj->save();
        }
    }

    public function ConvertDateFormat($date)
    {

        $var = $date;
        $date = str_replace('/', '-', $var);
        $tuition_start_date = date('Y-m-d', strtotime($date));
        return $tuition_start_date;
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

    public function ZoneLocationsMappings($zoneid, $locationids)
    {

        if ($locationids == '') {

            $zoneLoacationMappings = DB::table('teacher_location_preferences')
                ->select('location_id', 'teacher_id as id'
                    , 'teacher_location_preferences.id as lpid')
                ->where('zoneid', '=', $zoneid)
                ->groupBy('teacher_id')
                ->get();

        } else {

            $zoneLoacationMappings = DB::table('teacher_location_preferences')
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

    public function TeacherDetails($id)
    {

        $details = DB::table('teachers')
            ->leftjoin('cities', 'cities.id', '=', 'teachers.city')
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
            ->select('institutes.name', 'institutes.logo')
            ->join('institutes', 'institutes.id', '=', 'teacher_institute_preferences.institute_id')
            ->where('teacher_id', '=', $id)
            ->get();

        $tuitionCategories = DB::table('teacher_tuition_categories')
            ->select('tuition_categories.name')
            ->join('tuition_categories', 'tuition_categories.id', '=', 'teacher_tuition_categories.tuition_category_id')
            ->where('teacher_id', '=', $id)
            ->get();

        $locations = DB::select("call  teacher_locations('$id')");

        return array('details' => $details, 'qualifications' => $qualification, 'subjects' => $subjects,
            'institutes' => $institutes, 'locations' => $locations, 'qdetails' => $qDetails, 'tuitionCategories' => $tuitionCategories);

    }

    public function GetTuitionDetail($studentid)
    {
        $query = "select tuitions.id,GROUP_CONCAT(DISTINCT(ci.c_subjects) separator ', ') as subjects,teachers.registeration_no as TutorReg,";
        $query .= " tuitions.tuition_date";
        $query .= " from tuitions ";
        $query .= " LEFT JOIN(";
        $query .= " SELECT concat(classes.name,': ',GROUP_CONCAT(subjects.name separator ',')) as c_subjects,tuition_details.tuition_id";
        $query .= " FROM tuition_details";
        $query .= " LEFT JOIN class_subject_mappings ON class_subject_mappings.id = tuition_details.class_subject_mapping_id";
        $query .= " LEFT JOIN classes on classes.id = class_subject_mappings.class_id";
        $query .= " LEFT JOIN subjects on subjects.id = class_subject_mappings.subject_id";
        $query .= " GROUP BY tuition_details.id";
        $query .= " )ci ON ci.tuition_id = tuitions.id";
        $query .= " Left JOIN teacher_bookmarks tbm on tbm.tuition_id=tuitions.id";
        $query .= " Left JOIN teachers on teachers.id = tbm.teacher_id";
        $query .= " WHERE tuitions.student_id=$studentid";
        $query .= " GROUP BY tuitions.id";

        $tuitionDetails = DB::select(DB::raw($query));
        return json_encode($tuitionDetails);

    }


    public function SaveCallAcademyTuition($Tuition)
    {

        $timestamp = Carbon::now();
        $timestamp->toDateTimeString();

        $tuition_code = $Tuition['tuition_code'];
        $student_id = $Tuition['student_id'];
        $no_of_students = $Tuition['no_of_students'];
        $location_id = $Tuition['location_id'];
        $tuition_status_id = $Tuition['tuition_status_id'];
        $tuition_catefory_id = $Tuition['tuition_catefory_id'];
        $note = $Tuition['note'];
        $address = $Tuition['address'];
        $contact_no = $Tuition['contact_no'];
        $teacher_gender = $Tuition['teacher_gender'];
        $contact_person = $Tuition['contact_person'];
        $teacher_age = $Tuition['teacher_age'];
        $contact_no2 = $Tuition['contact_no2'];
        $suitable_timings = $Tuition['suitable_timings'];
        $teaching_duration = $Tuition['teaching_duration'];
        $tuition_fee = $Tuition['tuition_fee'];
        $experience = $Tuition['experience'];
        $tuition_max_fee = $Tuition['tuition_max_fee'];

        if (isset($Tuition['tuition_date']) && $Tuition['tuition_date'] != '') {

            $tuition_date = $this->ConvertDateFormat($Tuition['tuition_date']);

        } else {

            $tuition_date = date("Y-m-d", strtotime($timestamp->toDateTimeString()));

        }

        if (isset($Tuition['tuition_start_date']) && $Tuition['tuition_start_date'] != '') {

            $tuition_start_date = $this->ConvertDateFormat($Tuition['tuition_start_date']);

        } else {

            $tuition_start_date = '0000-00-00';
        }

        if (isset($Tuition['is_active'])) {
            $is_active = $Tuition['is_active'];
        } else {
            $is_active = 0;
        }
        if (isset($Tuition['is_approved'])) {

            $is_approved = $Tuition['is_approved'];

        } else {

            $is_approved = 0;

        }

        $tuition_obj = new Tuition();
        $tuition_obj->tuition_code = $tuition_code;
        $tuition_obj->student_id = $student_id;
        $tuition_obj->is_created_admin = 1;
        $tuition_obj->tuition_date = $tuition_date;
        $tuition_obj->tuition_catefory_id = $tuition_catefory_id;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->location_id = $location_id;
        $tuition_obj->is_active = $is_active;
        $tuition_obj->is_approved = $is_approved;
        $tuition_obj->tuition_status_id = 13;
        $tuition_obj->special_notes = $note;
        $tuition_obj->address = $address;
        $tuition_obj->contact_no = $contact_no;
        $tuition_obj->teacher_gender = $teacher_gender;
        $tuition_obj->contact_person = $contact_person;
        $tuition_obj->teacher_age = $teacher_age;
        $tuition_obj->tuition_start_date = $tuition_start_date;
        $tuition_obj->contact_no2 = $contact_no2;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->suitable_timings = $suitable_timings;
        $tuition_obj->teaching_duration = $teaching_duration;
        $tuition_obj->tuition_fee = $tuition_fee;
        $tuition_obj->tuition_max_fee = $tuition_max_fee;
        $tuition_obj->experience = $experience;
        $tuition_obj->created_at = $timestamp;
        $tuition_obj->updated_at = $timestamp;

        $tuition_obj->save();

        return $tuition_id = $tuition_obj->id;
    }

    public function SaveCallAcademyTuitionSubjects($Tuition,$tuition_id){

        //save tuition subjects if subjects selected
        if(isset($Tuition['csm']) && !empty($Tuition['csm'])){

            $csm = $Tuition['csm'];
            for ($j = 0; $j < count($csm); $j++) {

                $tdetail_obj = new tuition_detail();
                $tdetail_obj->tuition_id = $tuition_id;
                $tdetail_obj->class_subject_mapping_id = $csm[$j];
                $tdetail_obj->save();

            }

        }
    }

    public function SaveCallAcademyTuitionInstitute($Tuition,$tuition_id){

        if(isset($Tuition['institutes']) ){

            $institutes = $Tuition['institutes'];

            $len = count($Tuition['institutes']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new tuition_institute_preference();
                $obj->institute_id = $institutes[$j];
                $obj->tuition_id = $tuition_id;
                $obj->save();

            }

        }
    }


}