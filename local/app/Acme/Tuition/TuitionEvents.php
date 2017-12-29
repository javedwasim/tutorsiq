<?php
namespace Acme\Tuition;

use App\Tuition;
use App\tuition_detail;
use App\teacher_bookmark;
use App\tuition_history;
use App\tuition_label;
use Auth;
use Mail;
use Request;
use App\tuition_global;
use Validator;
use File;
use Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\tuition_institute_preference;
use App\teacher_application;


class TuitionEvents
{

    public function LoadSubjects($id)
    {
        return DB::table('class_subject_mappings')
            ->join('classes', 'class_subject_mappings.class_id', '=', 'classes.id')
            ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
            ->select('class_subject_mappings.id as mid', 'classes.name as cname', 'classes.id as cid', 'subjects.name as sname', 'subjects.id as sid')
            ->where('class_id', $id)
            ->get();

    }

    public function quickUpdate($Tuition){

        $tuitionid          = $Tuition['qetid'];
        $tuitionStatus      = $Tuition['qeTuitionStatus'];
        $startDate          = $Tuition['qestartDate'];
        $tuitionFee         = $Tuition['qetuitionFee'];
        $partnerShare       = $Tuition['qepartnerShare'];
        $partnerOneShare    = $Tuition['qeagentOneShare'];
        $partnerTwoShare    = $Tuition['qeagentTwoShare'];

        $tuition_obj = Tuition::find($tuitionid);
        $tuition_obj->tuition_status_id  = $tuitionStatus;
        $tuition_obj->tuition_start_date = $startDate;
        $tuition_obj->tuition_final_fee  = $tuitionFee;
        $tuition_obj->partner_share      = $partnerShare;
        $tuition_obj->agent_one_share    = $partnerOneShare;
        $tuition_obj->agent_two_share    = $partnerTwoShare;

        $tuition_obj->save();

    }

    public function updateTuitionStartDate($request){

        $id = $request['tids'];
        $tuition_start_date = $request['tuition_start_date'];

        $tuition_obj = Tuition::find($id);
        $tuition_obj->tuition_start_date = $tuition_start_date;
        $tuition_obj->tuition_status_id = 6;
        $tuition_obj->updated_at = Carbon::now();
        $tuition_obj->save();

    }

    public function update($Tuition)
    {

        $id = $Tuition['id'];
        $tuition_code = $Tuition['tuition_code'];
        $tuition_date = $this->ConvertDateFormat($Tuition['tuition_date']);
        if(!empty($Tuition['tuition_start_date'])){
            $tuition_start_date = $this->ConvertDateFormat($Tuition['tuition_start_date']);
        }else{

            $tuition_start_date = '0000-00-00';
        }

        $no_of_students = $Tuition['no_of_students'];
        $location_id = $Tuition['location_id'];
        $tuition_status_id = $Tuition['tuition_status_id'];
        $tuition_catefory_id = $Tuition['tuition_catefory_id'];
        $note = $Tuition['note'];

        $address = $Tuition['address'];
        $contact_no = $Tuition['contact_no'];
        $teacher_gender = $Tuition['teacher_gender'];
        $details = $Tuition['details'];
        $contact_person = $Tuition['contact_person'];
        $teacher_age = $Tuition['teacher_age'];
        $take_note = $Tuition['take_note'];
        $contact_no2 = $Tuition['contact_no2'];
        $suitable_timings = $Tuition['suitable_timings'];
        $teaching_duration = $Tuition['teaching_duration'];
        $tuition_fee = $Tuition['tuition_fee'];
        $experience = $Tuition['experience'];
        $band_id = $Tuition['band_id'];
        $referrer_id = $Tuition['referrer_id'];
        $tuition_max_fee = $Tuition['tuition_max_fee'];
        $tuition_final_fee = $Tuition['tuition_final_fee'];
        $partner_share = $Tuition['partner_share'];
        $agent_one_share = $Tuition['agent_one_share'];
        $agent_two_share = $Tuition['agent_two_share'];

        $timestamp = Carbon::now();
        $timestamp->toDateTimeString();

        if (isset($Tuition['is_active'])) {
            $is_active = $Tuition['is_active'];
        } else {
            $is_active = 2;
        }
        if (isset($Tuition['is_approved'])) {
            $is_approved = $Tuition['is_approved'];
        } else {
            $is_approved = 2;
        }

        $tuition_obj = Tuition::find($id);
        $tuition_obj->tuition_code = $tuition_code;
        $tuition_obj->student_id = '';
        $tuition_obj->tuition_date = $tuition_date;
        $tuition_obj->tuition_catefory_id = $tuition_catefory_id;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->location_id = $location_id;
        $tuition_obj->is_active = $is_active;
        $tuition_obj->is_approved = $is_approved;
        $tuition_obj->tuition_status_id = $tuition_status_id;
        $tuition_obj->special_notes = $note;
        $tuition_obj->address = $address;
        $tuition_obj->contact_no = $contact_no;
        $tuition_obj->teacher_gender = $teacher_gender;
        $tuition_obj->details = $details;
        $tuition_obj->contact_person = $contact_person;
        $tuition_obj->teacher_age = $teacher_age;
        $tuition_obj->take_note = $take_note;
        $tuition_obj->tuition_start_date = $tuition_start_date;
        $tuition_obj->contact_no2 = $contact_no2;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->suitable_timings = $suitable_timings;
        $tuition_obj->teaching_duration = $teaching_duration;
        $tuition_obj->tuition_fee = $tuition_fee;
        $tuition_obj->tuition_max_fee = $tuition_max_fee;
        $tuition_obj->experience = $experience;
        $tuition_obj->band_id = $band_id;
        $tuition_obj->tuition_final_fee = $tuition_final_fee;
        $tuition_obj->partner_share = $partner_share;
        $tuition_obj->agent_one_share = $agent_one_share;
        $tuition_obj->agent_two_share = $agent_two_share;
        $tuition_obj->referrer_id = $referrer_id;
        $tuition_obj->updated_at = $timestamp;
        $tuition_obj->created_by = 'admin';

        $tuition_obj->save();

        //save tuition subjects if subjects selected
        if(isset($Tuition['csm']) && $Tuition['class_change'] == 'change' ){
            $csm = $Tuition['csm'];
            for ($j = 0; $j < count($csm); $j++) {

                $tdetail_obj = new tuition_detail();
                $tdetail_obj->tuition_id = $id;
                $tdetail_obj->class_subject_mapping_id = $csm[$j];
                $tdetail_obj->save();

            }
        }

        //save tuition labesls
        if(isset($Tuition['labels']) && $Tuition['label_change']=='change'){

            $labels = $Tuition['labels'];

            $obj = DB::table('tuition_labels')->where('tuition_id', '=', $id)->get();

            if (!empty($obj)) {

                DB::table('tuition_labels')->where('tuition_id', '=', $id)->delete();

            }

            $len = count($Tuition['labels']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new tuition_label();
                $obj->label_id = $labels[$j];
                $obj->tuition_id = $id;
                $obj->save();

            }

        }

        //save tuition institutes
        if(isset($Tuition['institutes']) && $Tuition['institute_change']=='change'){

            $institutes = $Tuition['institutes'];

            $obj = DB::table('tuition_institute_preferences')->where('tuition_id', '=', $id)->get();

            if (!empty($obj)) {

                DB::table('tuition_institute_preferences')->where('tuition_id', '=', $id)->delete();

            }

            $len = count($Tuition['institutes']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new tuition_institute_preference();
                $obj->institute_id = $institutes[$j];
                $obj->tuition_id = $id;
                $obj->save();

            }

        }


        if (!empty($Tuition['submitbtnValue']) && $Tuition['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }

    public function UpdateTuitionStatus($tuition_id,$tuition_code){

        $tuition = DB::statement("call  copy_tuition('$tuition_id','$tuition_code')");

        return "save";

    }

    public function setFollowUpFilters($filter){

        //set filter for tuition location
        if(isset($filter['locations_p']) && $filter['locations_p']!=0){

            $locIds = $filter['locations_p'];
            $whereClause = "";
            for($j=0;$j<count($locIds);$j++){

                if($j==0){
                    $whereClause .= " locations.id = $locIds[$j]";
                }
                elseif($j<count($locIds)){
                    $whereClause .= " OR locations.id = $locIds[$j]";
                }
            }

            $temLocTable = "CREATE TEMPORARY TABLE IF NOT EXISTS LOC AS (SELECT * FROM locations WHERE $whereClause);";


        }else{

            $temLocTable = "CREATE TEMPORARY TABLE IF NOT EXISTS LOC AS (SELECT * FROM locations WHERE 1);";
        }

        //set filter for tuition labels
        if(isset($filter['label_p']) && $filter['label_p']!=0){

            $labelIds = $filter['label_p'];
            $whereClause = "";
            for($j=0;$j<count($labelIds);$j++){

                if($j==0){
                    $whereClause .= " tlabels.id = $labelIds[$j]";
                }
                elseif($j<count($labelIds)){
                    $whereClause .= " OR tlabels.id = $labelIds[$j]";
                }
            }

            $tempTable = "CREATE TEMPORARY TABLE IF NOT EXISTS LABELS AS (SELECT * FROM tlabels WHERE $whereClause);";


        }else{

            $tempTable = "CREATE TEMPORARY TABLE IF NOT EXISTS LABELS AS (SELECT * FROM tlabels WHERE 1);";
        }



        //tuition contact no filter
        if(isset($filter['contactNo'])){

            $contactNo = $filter['contactNo'];
        }else{

            $contactNo= '';
        }

        //tuition contact person filter
        if(isset($filter['contactPerson'])){

            $contactPerson = $filter['contactPerson'];
        }else{

            $contactPerson= '';
        }

        //tuition status filter
        if (isset($filter['assign_status'])) {
            $assign_status = $filter['assign_status'];

        } else {
            $assign_status = "0";

        }

        //tuition class filter
        if (isset($filter['class'])) {
            $class = $filter['class'];

        } else {
            $class = 0;

        }

        //tuition subject filter
        if (isset($filter['subject'])) {
            $subject = $filter['subject'];

        } else {
            $subject = 0;

        }

        return array(

            "temLocTable"=>$temLocTable,
            "tempTable"=>$tempTable,
            "contactNo"=>$contactNo,
            "contactPerson"=>$contactPerson,
            "assign_status"=>$assign_status,
            "class"=>$class,
            "subject"=>$subject,

        );
    }

    public function FollowUpTuitions($TuitionStatus,$filter)
    {

        //initialize filters
        $filters        = $this->setFollowUpFilters($filter);
        $contactNo      = $filters['contactNo'];
        $contactPerson  = $filters['contactPerson'];
        $assign_status  = $filters['assign_status'];
        $class          = $filters['class'];
        $subject        = $filters['subject'];
        $tempTable      = $filters['tempTable'];
        $temLocTable    = $filters['temLocTable'];

        $query = " SELECT tuitions.`id` as tid,  tuitions.`tuition_status_id`,tuitions.`tuition_code`, tuitions.`no_of_students`,";
        $query .= " tuitions.`tuition_date` ,tuitions.contact_person, tuitions.tuition_status_id, tuitions.contact_no, tuitions.contact_no2,tuitions.address,";
        $query .= " tuitions.tuition_fee,tuition_final_fee,is_started,tuitions.tuition_max_fee,tuitions.tuition_start_date,GROUP_CONCAT(DISTINCT(ci.class_subjects) separator ', ') as subjects,";
        $query .= " ci.teacherid,L.locations,GROUP_CONCAT(DISTINCT(tutiion_labels.name) separator '-') as label_name,ci.teacherinfo,tuitions.no_of_students,tuitions.created_by,referrers.name";


        $query .= " FROM tuitions";
        $query .= " LEFT JOIN (";

        $query .= " SELECT concat(c.name,': ',GROUP_CONCAT(s.name separator ',')) as class_subjects,";
        $query .= " c.id as cid, s.id as sid, td.tuition_id as tid, td.teacher_id as teacherid,teachers.mobile1,";
        $query .= " GROUP_CONCAT(DISTINCT CONCAT(teachers.fullname,'-',teachers.mobile1,'-',
                    teachers.teacher_photo,'-',teachers.id,'-',teachers.email) separator ',') as teacherinfo,teachers.fullname ";
        $query .= " ,c.id as class_id,s.id as subject_id ";
        $query .= " FROM tuition_details td ";
        $query .= " LEFT JOIN class_subject_mappings csm on csm.id = td.class_subject_mapping_id ";
        $query .= " LEFT JOIN classes c on c.id = csm.class_id ";
        $query .= " LEFT JOIN subjects s on s.id = csm.subject_id ";
        $query .= " LEFT JOIN teachers  on teachers.id = td.teacher_id  ";
        $query .= " GROUP BY c.id, td.tuition_id ";

        $query .= " )ci ON tuitions.id = ci.tid ";

        $query .= " LEFT JOIN( ";
        $query .= " SELECT tlabels.name,tlb.label_id,tlb.tuition_id FROM tlabels ";
        $query .= " LEFT JOIN tuition_labels tlb on tlb.label_id = tlabels.id    ";
        $query .= " GROUP BY tlb.id,tlabels.id   ";
        $query .= " )tutiion_labels ON tutiion_labels.tuition_id = tuitions.id   ";

        $query .= " LEFT JOIN LABELS F ON F.ID = tutiion_labels.label_id   ";
        $query .= " LEFT JOIN LOC L ON L.ID = tuitions.location_id   ";
        $query .= " LEFT JOIN  referrers  ON referrers.id = tuitions.referrer_id   ";

        $query .= " WHERE   ( ";

        if(isset($TuitionStatus)){

            $arrLength = count($TuitionStatus);
            $count  = 0;
            foreach ($TuitionStatus as $status){

                $query .= " tuition_status_id = $status   ";
                if($arrLength-1>$count)
                {

                    $query .= " OR  ";
                }
                $count++;
            }

        }

        $query .= " )   ";
        $query .= "  AND ( tutiion_labels.label_id = F.ID )  ";
        $query .= "  AND ( tuitions.location_id = L.ID )  ";

        if($contactNo!=''){
            $query .= "  AND ( CONCAT(tuitions.contact_no,' ',tuitions.contact_no2) LIKE CONCAT('%',$contactNo,'%') 
            OR ci.mobile1 LIKE CONCAT('%',$contactNo,'%') )  ";
        }

        if($contactPerson!=''){
            $query .= "  AND ( tuitions.contact_person LIKE '%$contactPerson%' OR ci.fullname LIKE '%$contactPerson%' )  ";
        }
        if($assign_status!=0){
            $query .= "  AND ( tuitions.tuition_status_id = $assign_status )  ";
        }
        if($class!=0){
            $query .= "  AND ( ci.class_id = $class )  ";
        }
        if($subject!=0){
            $query .= "  AND ( ci.subject_id = $subject )  ";
        }
        $query .= "  GROUP by tuitions.id  ";
        $query .= "  ORDER by tuitions.id;  ";

        $dropTempTable = "  DROP TEMPORARY TABLE LABELS;  ";


        DB::statement(DB::raw($tempTable));
        DB::statement(DB::raw($temLocTable));
        $tuitions = DB::select(DB::raw($query));
        DB::statement(DB::raw($dropTempTable));

        return $tuitions;

    }


    public function copy($tuition_id,$tuition_code){

        $tuition = DB::statement("call  copy_tuition('$tuition_id','$tuition_code')");

        return "save";

    }

    public function loads($records, $links, $filters)
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

    public function save_referrer($formdata, $modaname)
    {

        $name = $formdata['name'];

        $id = $formdata['id'];
        $status = $formdata['status'];

        $obj = new $modaname();
        $obj->name = $name;

        $obj->created_at = date('Y-m-d H:i:s', time());
        $obj->updated_at = date('Y-m-d H:i:s', time());

        if ($status == 'add') {

            $obj->save();

        } else {

            $obj = $modaname::find($id);
            $obj->name = $name;

            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if (!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }

    public function SaveSelectedTuitionLabels($request){

        $tuitionid = $request['tuitionID'];
        $bulkLabels = $request['bulkLabels'];


        for ($k = 0; $k < count($bulkLabels); $k++) {

            //find attached labels
            $obj = tuition_label::where('label_id',$bulkLabels[$k])->where('tuition_id',$tuitionid)->get();

            if($obj->isEmpty()){

                $obj = new tuition_label();
                $obj->tuition_id = $tuitionid;
                $obj->label_id = $bulkLabels[$k];
                $obj->save();

            }


        }

    }

    public function SaveNewTuitionLabels($tuitionids,$bulkLabels,$appendLabels){

        //delete previous tuition labels
        if(!$appendLabels){

            for($j=0;$j<count($tuitionids);$j++){

                $obj = tuition_label::where('tuition_id',$tuitionids[$j]);

                //delete previous tuition labels
                if(isset($obj)){
                    $obj->delete();
                }

            }

            //save new labels
            for ($j = 0; $j < count($tuitionids); $j++) {

                for ($k = 0; $k < count($bulkLabels); $k++) {

                    $obj = new tuition_label();
                    $obj->tuition_id = $tuitionids[$j];
                    $obj->label_id = $bulkLabels[$k];
                    $obj->save();

                }

            }

        }
        else {
            //append new labels
            for ($j = 0; $j < count($tuitionids); $j++) {

                for ($k = 0; $k < count($bulkLabels); $k++) {
                    //find attached labels
                    $obj = tuition_label::where('label_id',$bulkLabels[$k])->where('tuition_id',$tuitionids[$j])->get();
                    //echo $bulkLabels[$k]; dd($tuitionids[$j]);

                    //if(empty($obj)){

                        $obj = new tuition_label();
                        $obj->tuition_id = $tuitionids[$j];
                        $obj->label_id = $bulkLabels[$k];
                        $obj->save();
                    //}



                }

            }
        }
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

    public function loadTuitions($tuitions,$pagesize){

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

    public function save($Tuition){

        $timestamp = Carbon::now();
        $timestamp->toDateTimeString();

        $tuition_code = $Tuition['tuition_code'];
        $no_of_students = $Tuition['no_of_students'];
        $location_id = $Tuition['location_id'];
        $tuition_status_id = $Tuition['tuition_status_id'];
        $tuition_catefory_id = $Tuition['tuition_catefory_id'];
        $note = $Tuition['note'];
        $address = $Tuition['address'];
        $contact_no = $Tuition['contact_no'];
        $teacher_gender = $Tuition['teacher_gender'];
        $details = $Tuition['details'];
        $contact_person = $Tuition['contact_person'];
        $teacher_age = $Tuition['teacher_age'];
        $take_note = $Tuition['take_note'];
        $referrer_id = $Tuition['referrer_id'];
        $contact_no2 = $Tuition['contact_no2'];
        $suitable_timings = $Tuition['suitable_timings'];
        $teaching_duration = $Tuition['teaching_duration'];
        $tuition_fee = $Tuition['tuition_fee'];
        $experience = $Tuition['experience'];
        $band_id = $Tuition['band_id'];
        $tuition_max_fee = $Tuition['tuition_max_fee'];
        $tuition_final_fee = $Tuition['tuition_final_fee'];
        $partner_share = $Tuition['partner_share'];
        $agent_one_share = $Tuition['agent_one_share'];
        $agent_two_share = $Tuition['agent_two_share'];

        if(isset($Tuition['tuition_date']) && $Tuition['tuition_date']!=''){

            $tuition_date = $this->ConvertDateFormat($Tuition['tuition_date']);

        }else{

           //$tuition_date = date("Y-m-d",strtotime($timestamp->toDateTimeString()) );
           $tuition_date = '0000-00-00';

        }

        if(isset($Tuition['tuition_start_date']) && $Tuition['tuition_start_date']!=''){

            $tuition_start_date = $this->ConvertDateFormat($Tuition['tuition_start_date']);

        }else{

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
        $tuition_obj->is_created_admin = 1;
        $tuition_obj->student_id = '';
        $tuition_obj->tuition_date = $tuition_date;
        $tuition_obj->tuition_catefory_id = $tuition_catefory_id;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->location_id = $location_id;
        $tuition_obj->is_active = $is_active;
        $tuition_obj->is_approved = $is_approved;
        $tuition_obj->tuition_status_id = $tuition_status_id;
        $tuition_obj->special_notes = $note;
        $tuition_obj->address = $address;
        $tuition_obj->contact_no = $contact_no;
        $tuition_obj->teacher_gender = $teacher_gender;
        $tuition_obj->details = $details;
        $tuition_obj->contact_person = $contact_person;
        $tuition_obj->teacher_age = $teacher_age;
        $tuition_obj->take_note = $take_note;
        $tuition_obj->tuition_start_date = $tuition_start_date;
        $tuition_obj->contact_no2 = $contact_no2;
        $tuition_obj->no_of_students = $no_of_students;
        $tuition_obj->suitable_timings = $suitable_timings;
        $tuition_obj->teaching_duration = $teaching_duration;
        $tuition_obj->tuition_fee = $tuition_fee;
        $tuition_obj->tuition_max_fee = $tuition_max_fee;
        $tuition_obj->experience = $experience;
        $tuition_obj->band_id = $band_id;
        $tuition_obj->referrer_id = $referrer_id;
        $tuition_obj->tuition_final_fee = $tuition_final_fee;
        $tuition_obj->partner_share = $partner_share;
        $tuition_obj->agent_one_share = $agent_one_share;
        $tuition_obj->agent_two_share = $agent_two_share;
        $tuition_obj->created_at = $timestamp;
        $tuition_obj->updated_at = $timestamp;
        $tuition_obj->created_by = 'admin';

        $tuition_obj->save();

        $tuition_id = $tuition_obj->id;

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

        //save tuition institutes
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

        //save tuition labesls
        if(isset($Tuition['labels']) && !empty($Tuition['labels']) ){

            $labels = $Tuition['labels'];
            $len = count($Tuition['labels']);
            for ($j = 0; $j < $len; $j++) {

                $obj = new tuition_label();
                $obj->label_id = $labels[$j];
                $obj->tuition_id = $tuition_id;
                $obj->save();

            }

        }

        if (!empty($Tuition['submitbtnValue']) && $Tuition['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }

    public function LoadGlobalMatchedTeacher($filters){

        session(['last_filters' => $filters]);
        // Set  Filters
        if(isset($filters['gender_p'])){ $gender_filter = $filters['gender_p'];  } else{ $gender_filter = 1; }
        if (isset($filters['experience_p'])) { $experience_filter = $filters['experience_p'];}else{$experience_filter = 1;}
        if (isset($filters['fee_p'])) { $fee_p = $filters['fee_p'];}else{$fee_p = 1;}
        if(isset($filters['suitable_timings_p'])){$suitable_timings_p = $filters['suitable_timings_p'];}else{ $suitable_timings_p = 1; }
        if (isset($filters['age_p'])) {$age_p = $filters['age_p'];} else { $age_p = 1; }
        if(isset($filters['location_p'])){$location_p = $filters['location_p'];} else{$location_p = 1;}
        if(isset($filters['tuition_category_p'])){$tuition_category_p = $filters['tuition_category_p']; }else{$tuition_category_p = 1;}
        if(isset($filters['teacher_band_id_p'])){ $teacher_band_id_p = $filters['teacher_band_id_p']; }else{  $teacher_band_id_p = 1; }
        if(isset($filters['institution_p'])){ $institution_p = $filters['institution_p'];}else{  $institution_p = 1; }
        if(isset($filters['tuition_label_p'])){$tuition_label_p = $filters['tuition_label_p'];}else{ $tuition_label_p = 1; }
        if(isset($filters['subjectPreference_p'])){$subjectPreference_p = $filters['subjectPreference_p'];}else{ $subjectPreference_p = 1; }
        if(!empty($filters['pagesize'])){$pagesize = $filters['pagesize'];}else{ $pagesize = 50; }

        $teachers  = "SELECT T.id as teacher_id FROM teachers T";

        if($location_p == 1) {

            $teachers .= " INNER JOIN teacher_location_preferences lp on lp.teacher_id = T.id  ";
        }

        if($tuition_category_p == 1){
            $teachers .= " INNER JOIN  teacher_tuition_categories tc on T.id = tc.teacher_id  ";
        }

        if($subjectPreference_p == 1){

            $teachers .= " INNER JOIN teacher_subject_preferences tp on tp.teacher_id = T.id ";
            $teachers .= " INNER JOIN(
                               SELECT DISTINCT td.class_subject_mapping_id
                               FROM tuition_globals gt
                               INNER JOIN tuition_details td ON gt.tuition_id = td.tuition_id  
                           )subs 
                           ON tp.class_subject_mapping_id = subs.class_subject_mapping_id ";

        }

        if($institution_p == 1){

            $teachers .= " INNER JOIN teacher_institute_preferences teacher_ip on teacher_ip.teacher_id = T.id ";
            $teachers .= " INNER JOIN(
                               SELECT DISTINCT tip.institute_id
                               FROM tuition_globals gt
                               INNER JOIN tuition_institute_preferences tip ON gt.tuition_id = tip.tuition_id
                           )inst 
                           ON teacher_ip.institute_id = inst.institute_id ";

        }

        if($tuition_label_p == 1){

            $teachers .= " INNER JOIN teacher_labels tl on tl.teacher_id = T.id";
            $teachers .= " INNER JOIN(
                               SELECT DISTINCT label_id
                               FROM tuition_globals gt
                               INNER JOIN tuition_labels  ON gt.tuition_id = tuition_labels.tuition_id
                           )labels 
                           ON tl.label_id = labels.label_id ";

        }

        if($gender_filter == 1){

            $teachers .= " INNER JOIN(
                                SELECT DISTINCT teacher_gender
                                FROM tuition_globals gt
                                INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )gender 
                           ON T.gender_id = gender.teacher_gender ";

        }

        if($experience_filter == 1){

            $teachers .= " INNER JOIN(
                               SELECT DISTINCT experience
                               FROM tuition_globals gt
                               INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )exp 
                           ON T.experience = exp.experience ";

        }

        if($fee_p == 1){

            $teachers .= " INNER JOIN(
                               SELECT DISTINCT tuition_fee,tuition_max_fee
                               FROM tuition_globals gt
                               INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )fee 
                           ON T.expected_minimum_fee BETWEEN fee.tuition_fee AND fee.tuition_max_fee ";

        }

        if($suitable_timings_p == 1){

            $teachers .= " INNER JOIN(
                               SELECT DISTINCT suitable_timings
                               FROM tuition_globals gt
                               INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )timing 
                           ON T.suitable_timings = timing.suitable_timings ";
        }

        if($age_p == 1){

            $teachers .= " INNER JOIN(
                               SELECT DISTINCT teacher_age
                               FROM tuition_globals gt
                               INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )age 
                           ON T.age = age.teacher_age ";
        }

        if($teacher_band_id_p == 1){

            $teachers .= " INNER JOIN(
                               SELECT DISTINCT band_id
                               FROM tuition_globals gt
                               INNER JOIN tuitions  ON gt.tuition_id = tuitions.id
                           )band 
                           ON T.teacher_band_id = band.band_id ";
        }



        $teachers .= " GROUP BY T.id";

        $query = "  SELECT T.id as teacher_id,T.firstname,T.lastname,T.fullname,T.city, T.teacher_band_id,T.experience,T.teacher_photo,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365 as agey,T.mobile1,T.email,tb.name as band_name,
                    tuitions.id as id,CASE WHEN tbm.id IS NULL THEN 0 ELSE 1 END as tbm_id";
        $query .= " FROM teachers T";

        $query .= " INNER JOIN (";
        $query .= " $teachers";
        $query .= " )T2 ON T.id = T2.teacher_id";

        $query .= " LEFT JOIN teacher_bands tb on tb.id = T.teacher_band_id";
        $query .= " LEFT JOIN(";
        $query .= " SELECT DISTINCT tuitions.id,band_id FROM tuition_globals gt ";
        $query .= " LEFT JOIN tuitions  ON gt.tuition_id = tuitions.id ";
        $query .= " )tuitions ON tuitions.band_id = tb.id ";

        $query .= " LEFT JOIN teacher_bookmarks tbm on  tbm.teacher_id = T.id " ;
        $query .= " LEFT JOIN(";
        $query .= " SELECT DISTINCT tuitions.id FROM tuition_globals gt ";
        $query .= " LEFT JOIN tuitions  ON gt.tuition_id = tuitions.id ";
        $query .= " )tuition ON tuition.id = tbm.tuition_id ";

        $query .= " GROUP BY T.id";
        $query .= " ORDER BY T.teacher_band_id,T.id ASC;";

        $teachers = DB::select(DB::raw($query));
        $data = $this->Pagination($teachers,$pagesize);
        $data['tuition_category_p'] = 1;
        return $data;

    }

    public function getBroadCastTeachers(){

        $teachers = DB::table('teacher_globals')
                    ->orderBy('teachers.teacher_band_id', 'asc')
                    ->join('teachers', 'teachers.id', '=', 'teacher_globals.teacher_id')
                    ->join('teacher_bands', 'teachers.teacher_band_id', '=', 'teacher_bands.id')
                    ->select('teachers.*', 'teacher_globals.id as global_id','teacher_bands.name as band_name')
                    ->get();

        return $teachers;
    }

    public function LoadMatchedTeacher($tuitionid,$filters){

        session(['last_filters' => $filters]);

        //get tuition details
        $tuitionDetails = $gender = DB::table('tuitions')->where('id','=',$tuitionid)->select('*')->first();

        //dd($tuitionDetails);

        // set tuition category
        if(isset($tuitionDetails->tuition_catefory_id)){ $tuition_catefory_id = $tuitionDetails->tuition_catefory_id; }else{ $tuition_catefory_id = 0; }

        //  tuition location paratmeter for query
        if(isset($tuitionDetails->location_id)){ $location_id = $tuitionDetails->location_id;}else{ $location_id = 0;}

        // select gender value of selected tutiion
        if(isset($tuitionDetails->teacher_gender)){ $gender_id_p = $tuitionDetails->teacher_gender;}else{ $gender_id_p = 0;}

        // select experience value of selected tutiion
        if(isset($tuitionDetails->experience  ) && !empty($tuitionDetails->experience)){ $experience_p = $tuitionDetails->experience;}else{ $experience_p = 0;}

        // select MIN fee value of selected tutiion
        if(isset($tuitionDetails->tuition_fee)){ $tuition_fee = $tuitionDetails->tuition_fee;}else{ $tuition_fee = 0;}

        // select MAX fee value of selected tutiion
        if(isset($tuitionDetails->tuition_max_fee)){ $tuition_max_fee = $tuitionDetails->tuition_max_fee;}else{ $tuition_max_fee = 0;}

        // select timing value of selected tutiion
        if(isset($tuitionDetails->suitable_timings)){ $suitable_timings = $tuitionDetails->suitable_timings;}else{ $suitable_timings = '';}

        // select teacher_age value of selected tutiion
        if(isset($tuitionDetails->teacher_age)){ $teacher_age = $tuitionDetails->teacher_age;}else{ $teacher_age = 0;}

        // select band  value of selected tutiion
        if(isset($tuitionDetails->band_id)){ $band_id = $tuitionDetails->band_id;}else{ $band_id = 0;}

        // select band  value of selected tutiion
        if(isset($tuitionDetails->location_id)){ $location_id = $tuitionDetails->location_id;}else{ $location_id = 0;}

        //default page size is 50
        if(isset($filters['pagesize']) && $filters['pagesize']>0){ $pagesize=$filters['pagesize'];}else{ $pagesize=50; }

        // Set  Filters
        if(isset($filters['gender_p'])){ $gender_filter = $filters['gender_p'];  } else{ $gender_filter = 1; }
        if (isset($filters['experience_p'])) { $experience_filter = $filters['experience_p'];}else{$experience_filter = 1;}
        if (isset($filters['fee_p'])) { $fee_p = $filters['fee_p'];}else{$fee_p = 1;}
        if(isset($filters['suitable_timings_p'])){$suitable_timings_p = $filters['suitable_timings_p'];}else{ $suitable_timings_p = 1; }
        if (isset($filters['age_p'])) {$age_p = $filters['age_p'];} else { $age_p = 1; }
        if(isset($filters['location_p'])){$location_p = $filters['location_p'];} else{$location_p = 1;}
        if(isset($filters['tuition_category_p'])){$tuition_category_p = $filters['tuition_category_p']; }else{$tuition_category_p = 1;}
        if(isset($filters['teacher_band_id_p'])){ $teacher_band_id_p = $filters['teacher_band_id_p']; }else{  $teacher_band_id_p = 1; }
        if(isset($filters['institution_p'])){ $institution_p = $filters['institution_p'];}else{  $institution_p = 1; }
        if(isset($filters['tuition_label_p'])){$tuition_label_p = $filters['tuition_label_p'];}else{ $tuition_label_p = 1; }
        if(isset($filters['subjectPreference_p'])){$subjectPreference_p = $filters['subjectPreference_p'];}else{ $subjectPreference_p = 1; }


        $variables  = "SET @id_p = $tuitionid, @teacher_band_p = $teacher_band_id_p,@label_p = $tuition_label_p,@age_p = $age_p,@exp_p=$experience_filter,@location_p=$location_p,@LId=$location_id,
                       @t_band_id =$band_id, @gender_p=$gender_filter,@subject_pref_p=0,@fee_p=$fee_p,@suitable_timings_p=$suitable_timings_p ,@institute_p=$institution_p,@category_p=$tuition_category_p;";

        $teachers  = "SELECT T.id as teacher_id FROM teachers T";

        if($location_p == 1) {

            $teachers .= " INNER JOIN teacher_location_preferences lp on lp.teacher_id = T.id and lp.location_id = $location_id ";
        }

        if($tuition_category_p == 1){
            $teachers .= " INNER JOIN  teacher_tuition_categories tc on T.id = tc.teacher_id and tc.tuition_category_id = $tuition_catefory_id ";
        }

        if($subjectPreference_p == 1){
            $teachers .= " INNER JOIN teacher_subject_preferences tp on tp.teacher_id = T.id ";
            $teachers .= " INNER JOIN(
                           SELECT class_subject_mapping_id FROM tuition_details 
                           WHERE tuition_details.tuition_id = @id_p
                           )subs 
                           ON tp.class_subject_mapping_id = subs.class_subject_mapping_id ";
        }

        if($institution_p == 1){

            $teachers .= " INNER JOIN teacher_institute_preferences teacher_ip on teacher_ip.teacher_id = T.id ";
            $teachers .= " INNER JOIN(
                           SELECT institute_id FROM  tuition_institute_preferences 
                           WHERE tuition_institute_preferences.tuition_id = @id_p
                           )inst 
                           ON teacher_ip.institute_id = inst.institute_id ";

        }

        if($tuition_label_p == 1){

            $teachers .= " INNER JOIN teacher_labels tl on tl.teacher_id = T.id";
            $teachers .= " INNER JOIN(
                           SELECT label_id FROM  tuition_labels 
                           WHERE tuition_labels.tuition_id = @id_p
                           )labels 
                           ON tl.label_id = labels.label_id ";

        }

        $teachers .= " WHERE (@gender_p = 0  OR T.gender_id = $gender_id_p ) ";
        $teachers .= " and (@exp_p = 0 OR T.experience = '$experience_p' ) ";
        $teachers .= " and (@fee_p = 0 OR T.expected_minimum_fee  BETWEEN $tuition_fee AND $tuition_max_fee ) ";
        $teachers .= " and (@suitable_timings_p = 0 OR T.suitable_timings = '$suitable_timings') ";
        $teachers .= " and (@age_p = 0 OR T.age = $teacher_age) ";
        $teachers .= " and (@teacher_band_p = 0 OR T.teacher_band_id = $band_id ) ";

        $query = "  SELECT T.id as teacher_id,T.firstname,T.lastname,T.fullname,T.city, T.teacher_band_id,T.experience,T.teacher_photo,
                    DATEDIFF(CURRENT_DATE, STR_TO_DATE(dob, '%Y-%m-%d'))/365 as agey,T.mobile1,T.email, tlp.location_id,@id_p as id,td2.id as td_id,
                    CASE WHEN tbm.id IS NULL THEN 0 ELSE 1 END as tbm_id,tb.name as band_name,lbl.name as labels";
        $query .= " FROM teachers T";
        $query .= " INNER JOIN (";
        $query .= " $teachers";
        $query .= " )T2 ON T.id = T2.teacher_id";

        $query .= " LEFT JOIN teacher_bands tb on tb.id = T.teacher_band_id";
        $query .= " LEFT JOIN teacher_location_preferences tlp ON tlp.teacher_id = T.id ";

        $query .= " LEFT JOIN teacher_bookmarks tbm on  tbm.teacher_id = T.id AND tbm.tuition_id = @id_p";
        $query .= " LEFT JOIN teacher_subject_preferences tp ON tp.teacher_id = T.id";
        $query .= " LEFT JOIN(";
        $query .= " SELECT id, class_subject_mapping_id  FROM  tuition_details where tuition_id  = @id_p";
        $query .= " )td2 ON td2.class_subject_mapping_id = tp.class_subject_mapping_id";

        $query .= " LEFT JOIN teacher_labels tl on tl.teacher_id = T.id";
        $query .= " LEFT JOIN tuition_labels tlabel  on tlabel.label_id =  tl.label_id";
        $query .= " LEFT JOIN tlabels lbl on lbl.id = tlabel.label_id";
        $query .= " LEFT JOIN class_subject_mappings csm on csm.id =  tp.class_subject_mapping_id";
        $query .= " GROUP BY T.id";
        $query .= " ORDER BY T.teacher_band_id,T.id ASC;";

       //echo $query;   dd();

        DB::statement(DB::raw($variables));
        $teachers = DB::select(DB::raw($query));
        $data = $this->Pagination($teachers,$pagesize);
        $data['tuition_category_p'] = $tuition_category_p;
        return $data;

    }

    public function Pagination($teachers,$pagesize){

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
        $teachers->setPath('');
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

    public function load($filters)
    {
        //print_r($filters); die();
        $last_filters = session(['last_filters' => $filters]);
        $label_list='';
        $category_list='';
        $location_list='';


        if (isset($filters['reset'])) {

            $tuition_code = "";
            $tuition_date = "90";
            $assign_status = "0";
            $is_active = "0";
            $is_approved = "0";
            $class = "0";
            $subject = "0";
            $date_filter = $this->SetDateFilters($filters);
            $tuition_start_date = $date_filter['tuition_start_date'];
            $tuition_end_date = $date_filter['tuition_end_date'];
            $label_list ='';
            $category_list='';
            $location_list='';
            $contact_no='';
            $contact_person='';
            $teacher_gender = "0";
            $suitable_timings = "";
            $tuition_fee = "0";
            $pagesize = 50;

        }
        else {

            if (isset($filters['tuition_code'])) {
                $tuition_code = $filters['tuition_code'];


            } else {
                $tuition_code = "";

            }
            if (isset($filters['tuition_date'])) {
                $tuition_date = $filters['tuition_date'];

            } else {
                $tuition_date = "0000-00-00";

            }

            if (isset($filters['assign_status'])) {
                $assign_status = $filters['assign_status'];

            } else {
                $assign_status = "0";

            }
            if (isset($filters['is_approved'])) {

                $is_approved = $filters['is_approved'];


            }  else {
                $is_approved = "0";

            }

            if (isset($filters['is_active'])) {
                $is_active = $filters['is_active'];


            } else {
                $is_active = "0";

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

            if (isset($filters['contact_no'])) {
                $contact_no = $filters['contact_no'];

            } else {
                $contact_no = "";

            }

            if (isset($filters['contact_person'])) {
                $contact_person = $filters['contact_person'];

            } else {
                $contact_person = "";

            }

            if (isset($filters['teacher_gender'])) {
                $teacher_gender = $filters['teacher_gender'];

            } else {
                $teacher_gender = 0;
            }

            if (isset($filters['suitable_timings'])) {
                $suitable_timings = $filters['suitable_timings'];

            } else {
                $suitable_timings = '';
            }

            if (isset($filters['tuition_fee'])) {
                $tuition_fee = $filters['tuition_fee'];

            } else {
                $tuition_fee = 0;
            }


            if (isset($filters['start_date']) && isset($filters['end_date'])) {

                $date_filter = $this->SetDateFilters($filters);
                $tuition_start_date = $date_filter['tuition_start_date'];
                $tuition_end_date = $date_filter['tuition_end_date'];


            } else {

                $date_filter = $this->SetDateFilters($filters);
                $tuition_start_date = $date_filter['tuition_start_date'];
                $tuition_end_date = $date_filter['tuition_end_date'];
            }

            if(isset($filters['labels'])){

                //$location_list = 'SELECT loc.id = ';
                $labels  = $filters['labels'];
                $csv = count($labels);
                $count = 1;
                foreach($labels as $label){
                    $label_list .= $label;
                    if($count<=$csv){
                        $label_list .=',';
                    }
                    $count++;
                }

            }

            if(isset($filters['categories'])){

                //$location_list = 'SELECT loc.id = ';
                $categories  = $filters['categories'];
                $csv = count($categories);
                $count = 1;
                foreach($categories as $c){
                    $category_list .= $c;
                    if($count<=$csv){
                        $category_list .=',';
                    }
                    $count++;
                }

            }

            if(isset($filters['locations'])){

                //$location_list = 'SELECT loc.id = ';
                $locations  = $filters['locations'];
                $csv = count($locations);
                $count = 1;
                foreach($locations as $l){
                    $location_list .= $l;
                    if($count<=$csv){
                        $location_list .=',';
                    }
                    $count++;
                }

            }

            if(isset($filters['pagesize']) && $filters['pagesize']>0){

                $pagesize=$filters['pagesize'];

            }else{
                $pagesize=50;
            }

            if(isset($filters['student_tutiions'])){

                $student_tuition = $filters['student_tutiions'];
            }else{

                $student_tuition = '';
            }


        }
        //$teachers = DB::table('teachers')->get();
        $tuitions = DB::select("call  load_tuitions('$tuition_code','$tuition_date','$assign_status','$is_active','$is_approved','$class','$subject',
                    '$tuition_start_date','$tuition_end_date','$label_list','$category_list','$location_list','$contact_no','$contact_person',
                    '$teacher_gender','$suitable_timings','$tuition_fee','$student_tuition')");

        //Get current page form url e.g. &page=6
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
        $tuitions->setPath('tuitions');
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
            'records' => $tuitions,
            'pagesize' => $pagesize,
        );

    }

    public function AddToBookmarkList($request){

        $ids = $request['ids'];
        $tuitionid = $request['tuitionid'];
        $teachersids = explode(',',$ids);

        for($j=0;$j<count($teachersids);$j++){

            $teacherid = $teachersids[$j];
            $this->TeacherBookmark($tuitionid,$teacherid);
        }

    }

    public function TeacherBookmark($tuitionid, $teacher_id)
    {
        $obj = teacher_bookmark::where('tuition_id',$tuitionid)->where('teacher_id',$teacher_id)->first();

        // if teahcer is not already bookmarked.
       if(!isset($obj->id)){

           $obj = new teacher_bookmark();
           $obj->tuition_id = $tuitionid;
           $obj->teacher_id = $teacher_id;
           $save = $obj->save();
       }
    }

    public function RemoveToBookmarkList($request){

        $ids = $request['ids'];
        $tuitionid = $request['tuitionid'];
        $teachersids = explode(',',$ids);

        for($j=0;$j<count($teachersids);$j++){

            $teacherid = $teachersids[$j];
            $this->RemoveBookmark($tuitionid,$teacherid);
        }

    }

    public function  RemoveBookmark($tuitionid, $teacher_id)
    {
        $obj = teacher_bookmark::where('tuition_id',$tuitionid)->where('teacher_id',$teacher_id)->first();
        if(isset($obj)){

            $obj->delete();

        }

    }

    public function UpdateTuitionDetails($tuition_detail_id)
    {

        $subject_length = count($tuition_detail_id['subjects']);

        if ($subject_length > 0) {
            for ($j = 0; $j < $subject_length; $j++) {

                $tuition_detail = tuition_detail::find($tuition_detail_id['subjects'][$j]);
                $tuition_detail->teacher_id = $tuition_detail_id['teacher_id'];
                $tuition_detail->assign_date = date('Y-m-d');
                $tuition_detail->is_trial = 1;
                $tuition_detail->save();


            }
        }

        return $tuition_detial = array(
            'teacher_id' => $tuition_detail_id['teacher_id'],
            'tuition_detail_id' => $tuition_detail_id['subjects']
        );
    }

    public function CheckTuitionHistory($tuition_detail_id, $teacher_id)
    {

        DB::table('tuition_history')
            ->where('tuition_detail_id', $tuition_detail_id)
            ->where('teacher_id', '!=', $teacher_id)
            ->update(['tuition_status_id' => 4]);


    }

    public function UpdateTtuitionHistory($tuition_detial)
    {

        $subject_length = count($tuition_detial['tuition_detail_id']);

        if ($subject_length > 0) {
            for ($j = 0; $j < $subject_length; $j++) {

                $tuition_detail_id = $tuition_detial['tuition_detail_id'][$j];
                $teacher_id = $tuition_detial['teacher_id'];

                $tuition_history = DB::table('tuition_history')
                                    ->where('tuition_detail_id', $tuition_detail_id)
                                    ->where('teacher_id', $teacher_id)
                                    ->get();



                if (empty($tuition_history)) {

                    $obj = new tuition_history();
                    $obj->teacher_id = $tuition_detial['teacher_id'];
                    $obj->tuition_detail_id = $tuition_detial['tuition_detail_id'][$j];
                    $obj->assign_date = date('Y-m-d');
                    $obj->save();

                } else {
                    $history_id = $tuition_history[0]->id;
                    $obj = tuition_history::find($history_id);
                    $obj->teacher_id = $tuition_detial['teacher_id'];
                    $obj->tuition_detail_id = $tuition_detial['tuition_detail_id'][$j];
                    $obj->assign_date = date('Y-m-d');
                    $obj->save();


                }

            }
        }

        return 'save';

    }

    public function ChangeTuitionStatus($request)
    {
        $tuitionid =   $request['tuitionid'];
        $statusid =   $request['statusid'];
        $color =   $request['color'];

        $obj = Tuition::find($tuitionid);
        $obj->tuition_status_id = $statusid;
        $obj->save();

        $tutiionFinalFee = DB::table('tuitions')->select('tuition_final_fee')
            ->where('id', $tuitionid)->first();

        $tutiionFinalFee = $tutiionFinalFee->tuition_final_fee;
        $tuitionStatus = array(
            'tuitionid' => $tuitionid,
            'color' => $color,
            'statusid' => $statusid,
            'finalFee' => $tutiionFinalFee

        );


        return $tuitionStatus;

    }


    public function RemoveTeacherTuitionHistory($teacher_id,$td_id){

        //update tuition history
        $tuition_history = DB::table('tuition_history')
                            ->where('tuition_detail_id', $td_id)
                            ->where('teacher_id', $teacher_id)
                            ->get();


        if (!empty($tuition_history)) {

            $history_id = $tuition_history[0]->id;
            $obj = tuition_history::find($history_id);
            $obj->tuition_fee = 1;
            $obj->save();


        }

        return 'save';

    }

    public function AssignedTeacher($request)
    {
        //print_r($request); die();
        //update tuition detail table
        $teacher_id = $request['teacher_id'];
        $td_id = $request['td_id'];
        $tuition_id = $request['tuition_id'];

        $obj = tuition_detail::find($td_id);
        $obj->teacher_id = $teacher_id;
        $obj->assign_date = date('Y-m-d');
        $obj->save();

        //update tuition history
        $tuition_history = DB::table('tuition_history')
                            ->where('tuition_detail_id', $td_id)
                            ->where('teacher_id', $teacher_id)
                            ->get();
        //print_r($tuition_history); die();
        $this->CheckTuitionHistory($td_id, $teacher_id);

        if (empty($tuition_history)) {

            $obj = new tuition_history();
            $obj->teacher_id = $teacher_id;
            $obj->tuition_detail_id = $td_id;
            $obj->assign_date = date('Y-m-d');
            $obj->tuition_status_id = 1;
            $obj->save();


        } else {

            $history_id = $tuition_history[0]->id;

            $obj = tuition_history::find($history_id);
            $obj->teacher_id = $teacher_id;
            $obj->tuition_detail_id = $td_id;
            $obj->assign_date = date('Y-m-d');
            $obj->tuition_status_id = 1;
            $obj->save();

        }

        //$this->UpdateTuitionStatus($tuition_id);

        return 'save';
    }

    public function SetDateFilters($filters)
    {


        // get the current time
        $current = Carbon::now();
        if (empty($filters)) {
            //$tuition_start_date = date('Y-m-d',strtotime($current->subDays(7)));
            $tuition_start_date = date('Y-m-d', strtotime(Carbon::now()->subDays(90) ));
            $tuition_end_date = date('Y-m-d', strtotime(Carbon::now()));

        } elseif (isset($filters['start_date']) && isset($filters['end_date'])) {

            $tuition_start_date = $this->ConvertDateFormat($filters['start_date']);
            $tuition_end_date = $this->ConvertDateFormat($filters['end_date']);

        }  else {
            $tuition_start_date = "";
            $tuition_end_date = "";
        }

        return array(
            'tuition_start_date' => $tuition_start_date,
            'tuition_end_date' => $tuition_end_date,
            'tuition_filter' => $filters
        );

    }

    public function ConvertDateFormat($date){

        $var = $date;
        $date = str_replace('/', '-', $var);
        $tuition_start_date = date('Y-m-d', strtotime($date));
        return $tuition_start_date;
    }

    public  function RemoveTeacher($id){

        $tuitionDetail = tuition_detail::find($id);
        $tuitionDetail->teacher_id = 0;
        $tuitionDetail->is_trial = 0;
        $tuitionDetail->assign_date = '0000-00-00';
        $tuitionDetail->save();

    }



    public function MarkRegular($tuition_status_id, $tuiion_history_id,$td_id)
    {


        //set tial to regular in tuition detials
        $obj = tuition_detail::find($td_id);
        $obj->is_trial = 0;
        $obj->save();

    }

    public function CreateGlobalList($tuitionid){

        $obj = new tuition_global();
        $obj->tuition_id = $tuitionid;
        $obj->created_at = date('Y-m-d H:i:s', time());
        $obj->updated_at = date('Y-m-d H:i:s', time());
        $obj->save();


    }


    public function DeleteGT($request){

        $globalTuitions = explode(',', $request['ids']);

        for($j = 0 ; $j<count($globalTuitions); $j++){

            $globalTuitionsId = $globalTuitions[$j];
            $globalTutiion = tuition_global::where('tuition_id', $globalTuitionsId)->first();
            $globalTutiion->delete();

        }

    }

    public function getFollowSummary($ids,$share){

        if(!empty($ids)){
            $partners = DB::table('tuitions')
                        ->leftJoin('locations','locations.id','=','tuitions.location_id')
                        ->select(DB::raw("tuition_final_fee*$share as pShare, contact_person,locations"))
                        ->whereRaw('FIND_IN_SET(tuitions.id,'."'$ids'".')')
                        ->get();

        }else{
            $partners='';
        }

        return $partners;


    }


    public function getTuitionsSummary($ids){

        if(!empty($ids)){
            $partners = DB::table('tuitions')
                            ->leftJoin('referrers','referrers.id','=','tuitions.referrer_id')
                            ->select('referrers.name',
                                DB::raw('(sum(case when tuitions.partner_share>0 then tuitions.partner_share*tuitions.tuition_final_fee end))/100 AS partnerShare'),
                                DB::raw('COUNT(case when tuitions.partner_share>0 then tuitions.partner_share end  ) AS partnerCount'),
                                DB::raw('(sum(case when tuitions.agent_one_share>0 then tuitions.agent_one_share*tuitions.tuition_final_fee end))/100 AS agentOneShare'),
                                DB::raw('(sum(case when tuitions.agent_two_share>0 then tuitions.agent_two_share*tuitions.tuition_final_fee end))/100 AS agentTwoShare'),
                                DB::raw('COUNT(case when tuitions.agent_one_share>0 then tuitions.agent_one_share  end) AS agentOneCount'),
                                DB::raw('COUNT(case when  tuitions.agent_two_share>0 then tuitions.agent_two_share  end) AS agentTwoCount'),
                                DB::raw('(sum(case when tuitions.tuition_final_fee>0 then tuitions.tuition_final_fee end)) AS academyShare'),
                                DB::raw('COUNT(case when tuitions.tuition_final_fee>0 then tuitions.tuition_final_fee end ) AS academyTuitions')
                            )
                            ->whereRaw('FIND_IN_SET(tuitions.id,'."'$ids'".')')
                            ->groupBy('tuitions.referrer_id')
                            ->orderBy('referrers.name', 'ASC')
                            ->get();

        }else{
            $partners='';
        }

        return $tuitionSummary = array(
            'partners'=>$partners
        );


    }

    public function getClassSubjects($classid){

        if(isset($classid) && $classid!=0 ){

            $class_id = $classid;
            $subjects = DB::table('class_subject_mappings')
                        ->join('subjects','subjects.id','=','class_subject_mappings.subject_id')
                        ->select('class_subject_mappings.*', 'subjects.name','subjects.id as sid')
                        ->where('class_id','=',$class_id)
                        ->orderBy('subjects.name', 'ASC')
                        ->get();

        }else{
            $subjects = DB::table('subjects')->select('subjects.name','subjects.id as sid')->orderBy('subjects.name', 'ASC')->get();

        }

        return $subjects;
    }

    public function getTuitionShortView($tuitionDetails){

        //sms broadcast view
        $smsText  = '';
        $tuitionDetail  = '';
        if(isset($tuitionDetails[0])){

            $tuitionDetails = $tuitionDetails[0];
            if($tuitionDetails->subject !=''){
                $smsText .= trim($tuitionDetails->subject);
                $smsText .= "\r\n";
            }
            if($tuitionDetails->institute_name !=''){
                $smsText .= " ".trim($tuitionDetails->institute_name);
            }
            if($tuitionDetails->location_name !=''){
                $smsText .= " ".trim($tuitionDetails->location_name);
            }
            if($tuitionDetails->gender !=''){
                $smsText .= trim($tuitionDetails->gender);
                $smsText .= "\r\n";
            }
            if($tuitionDetails->suitable_timings !=''){
                $smsText .= " ".trim($tuitionDetails->suitable_timings);
                $smsText .= "\r\n";
            }
            if($tuitionDetails->special_notes !=''){
                $smsText .= trim($tuitionDetails->special_notes);
                $smsText .= "\r\n";
            }
            if($tuitionDetails->teaching_duration !=''){
                $smsText .= trim($tuitionDetails->teaching_duration."mins");
                $smsText .= "\r\n";
            }
            if($tuitionDetails->tuition_fee !=''){
                $smsText .= trim($tuitionDetails->tuition_fee)."K - ".trim($tuitionDetails->tuition_max_fee)."K";
                $smsText .= "\r\n";
            }


            if($tuitionDetails->contact_person!=''){
                $tuitionDetail .= trim($tuitionDetails->contact_person);
            }
            if($tuitionDetails->contact_no!=''){
                $tuitionDetail .= " ".trim($tuitionDetails->contact_no);
            }
            if($tuitionDetails->contact_no2!=''){
                $tuitionDetail .= " ".trim($tuitionDetails->contact_no2);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->subject!=''){
                $tuitionDetail .= trim($tuitionDetails->subject);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->institute_name!=''){
                $tuitionDetail .= trim($tuitionDetails->institute_name);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->location_name!=''){
                $tuitionDetail .= trim($tuitionDetails->location_name);
            }
            if($tuitionDetails->address!=''){
                $tuitionDetail .= " ".trim($tuitionDetails->address);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->lebel_names!=''){
                $tuitionDetail .= trim($tuitionDetails->lebel_names);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->special_notes!=''){
                $tuitionDetail .= trim($tuitionDetails->special_notes);
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->tuition_fee!=''){
                $tuitionDetail .= trim($tuitionDetails->tuition_fee)."K - ".trim($tuitionDetails->tuition_max_fee)."K";
                $tuitionDetail .= "\r\n";
            }
            if($tuitionDetails->suitable_timings!=''){
                $tuitionDetail .= " ".trim($tuitionDetails->suitable_timings);
            }
            if($tuitionDetails->teaching_duration!=''){
                $tuitionDetail .= " ".trim($tuitionDetails->teaching_duration);
            }

        }

        return array('tuitionDetail'=>$tuitionDetail,'smsText'=>$smsText);
    }

    public function getTuitionShortView11($tuitionDetails){

        $smsText  = '';
        $tuitionDetail  = '';
        foreach ($tuitionDetails as $detail){
            if($detail->subject !=''){
                $smsText .= trim($detail->subject);
                $smsText .= "\r\n";
            }
            if($detail->institute_name !=''){
                $smsText .= " ".trim($detail->institute_name);
                $smsText .= "\r\n";
            }
            if($detail->location_name !=''){
                $smsText .= " ".trim($detail->location_name);
                $smsText .= "\r\n";
            }
            if($detail->gender !=''){
                $smsText .= trim($detail->gender);
                $smsText .= "\r\n";
            }
            if($detail->suitable_timings !=''){
                $smsText .= " ".trim($detail->suitable_timings);
                $smsText .= "\r\n";
            }
            if($detail->special_notes !=''){
                $smsText .= trim($detail->special_notes);
                $smsText .= "\r\n";
            }
            if($detail->teaching_duration !=''){
                $smsText .= trim($detail->teaching_duration."mins");
                $smsText .= "\r\n";
            }
            if($detail->tuition_fee !=''){
                $smsText .= trim($detail->tuition_fee)."K - ".trim($detail->tuition_max_fee)."K";
                $smsText .= "\r\n\r\n";
            }

            if($detail->contact_person!=''){
                $tuitionDetail .= trim($detail->contact_person);
            }
            if($detail->contact_no!=''){
                $tuitionDetail .= " ".trim($detail->contact_no);
            }
            if($detail->contact_no2!=''){
                $tuitionDetail .= " ".trim($detail->contact_no2);
                $tuitionDetail .= "\r\n";
            }
            if($detail->subject!=''){
                $tuitionDetail .= trim($detail->subject);
                $tuitionDetail .= "\r\n";
            }
            if($detail->institute_name!=''){
                $tuitionDetail .= trim($detail->institute_name);
                $tuitionDetail .= "\r\n";
            }
            if($detail->location_name!=''){
                $tuitionDetail .= trim($detail->location_name);
            }
            if($detail->address!=''){
                $tuitionDetail .= " ".trim($detail->address);
                $tuitionDetail .= "\r\n";
            }
            if($detail->lebel_names!=''){
                $tuitionDetail .= trim($detail->lebel_names);
                $tuitionDetail .= "\r\n";
            }
            if($detail->special_notes!=''){
                $tuitionDetail .= trim($detail->special_notes);
                $tuitionDetail .= "\r\n";
            }
            if($detail->tuition_fee!=''){
                $tuitionDetail .= trim($detail->tuition_fee)."K - ".trim($detail->tuition_max_fee)."K";
                $tuitionDetail .= "\r\n";
            }
            if($detail->suitable_timings!=''){
                $tuitionDetail .= " ".trim($detail->suitable_timings);
                $tuitionDetail .= "\r\n";
            }
            if($detail->teaching_duration!=''){
                $tuitionDetail .= " ".trim($detail->teaching_duration);
                $tuitionDetail .= "\r\n\r\n";
            }

        }
        return array('tuitionDetail'=>$tuitionDetail,'smsText'=>$smsText);
    }


    public function deleteGradeSubject($id){


        $tuitionDetail = tuition_detail::find($id);
        try {

            $tuitionDetail->delete();

        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json(['success' => false]);
        }
    }


    public function createFollowupQuickEditOptions($tuitionid){

        $tuition = Tuition::where('tuitions.id', '=', $tuitionid)
                            ->join('tution_status','tution_status.id','=','tuitions.tuition_status_id')
                            ->select('tuitions.id', 'tuition_status_id','tuition_start_date','tuition_final_fee','partner_share','agent_one_share','agent_two_share','tution_status.name')
                            ->get()
                            ->first();

        $tuitions_status_id = $tuition['tuition_status_id'];

        $tuitionStatus = DB::table('tution_status')->get();
        $options = "" ;
        foreach ($tuitionStatus as $status){

            $options .= "<option value='$status->id'";
            if($status->id == $tuitions_status_id) {
                $options .="selected";
            }
            $options .= ">$status->name</option> ";

        }

        return array('tuition'=>$tuition,'options'=>$options);

    }

    public function SaveTuitionStatus($request){

        $str = $request['applicationids'];
        $status = $request['application_status'];
        $currentTuitionId = $request['currentTuitionId2'];
        $timestamp = Carbon::now();
        $timestamp->toDateTimeString();

        if(!empty($str)){
            $ids = explode(',',$str);
            foreach($ids as $id){
                $obj = teacher_application::find($id);
                $obj->application_status_id = $status;
                $obj->updated_at = $timestamp;
                $obj->save();

            }
        }

        return $currentTuitionId;

    }

}