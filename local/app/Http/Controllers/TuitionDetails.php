<?php

namespace App\Http\Controllers;

use App\Tuition;
use App\application_status;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\Tuition\TuitionEvents;
use Acme\Mailers\Mailers;
use Acme\Config\Constants;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\tuition_global;
use App\teacher_application;

class TuitionDetails extends Controller
{

    protected $tuition;
    protected $mailers;
    protected $BulkEmailTemplate;
    protected $CurrentUri;
    protected $Followup;

    public function __construct(TuitionEvents $tuition, Mailers $mailers, Constants $constants)
    {
        $this->tuition = $tuition;
        $this->mailers = $mailers;
        $this->BulkEmailTemplate = $constants->BulkEmailTemplate();
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();

        //initialise tuition follow up status.
        $this->Followup = $constants->TuitionFollowUp();
        //dd($this->BulkEmailTemplate['BULK_EMAIL_TEMPLATE']);
    }

    public function AdminTuitions()
    {

        return redirect("admin/tuitions")->with('status', 'Tuition Save Successfully!');
    }

    public function CopyTuition($id)
    {

        //get tuition code of last inserted tuition
        $tuition_code = Tuition::orderby('id', 'desc')->first()->toArray();
        $tuition_code = $tuition_code['tuition_code'];

        //new tuition code by inrementing one.
        $code = "T" . date('ym'); //first pasrt of tuition code i.e. current year and month
        $newStr = ++$tuition_code;
        $last_part = substr($newStr, 5); //last part of string i.e. incremented part of string
        $new_tuition_code = substr_replace($last_part, $code, 0, 0);

        //copy to new tuition
        $new_tuition = $this->tuition->copy($id, $new_tuition_code);

        return redirect('admin/tuitions')->with('status', 'Tuition Copied Successfully!');

    }

    public function GetCustomerPhone()
    {

        $customer_phone = DB::table('tuitions')->select('contact_no')->get();
        $phone_numers = '';

        foreach ($customer_phone as $phone) {

            if (!empty($phone->contact_no)) {

                $phone_numers .= $phone->contact_no . ";";

            }


        }
        $current_route = $this->CurrentUri;

        return view('customer_phone_numers', compact('phone_numers', 'current_route'));

    }

    public function LoadTuitionsWithPageSize(Request $request)
    {
        $filters = Request::all();
        $response = $this->tuition->load($filters);
        $tuitions = $response['$records'];
        return response()->json(['success' => true, 'tuitions' => $tuitions]);
    }

    public function LoadTuitions(Request $request)
    {
        //dd(session('filters'));
        $filters = Request::all();

        if (empty($filters)) {

            $tuition_start_date = date('d/m/Y', strtotime(Carbon::now()->subDays(90)));
            $tuition_end_date = date('d/m/Y', strtotime(Carbon::now()));

            $filters = array('start_date' => $tuition_start_date, 'end_date' => $tuition_end_date, 'tuition_date' => '90');

        }

        if (isset($filters['reset'])) {

            $filters = "";
            $date_filter = $this->tuition->SetDateFilters($filters);
            $last_filters = session('last_filters');
            $tuition_start_date = $date_filter['tuition_start_date'];
            $tuition_end_date = $date_filter['tuition_end_date'];
            $tuition_filter = $date_filter['tuition_filter'];

            $filters = array('start_date' => $tuition_start_date, 'end_date' => $tuition_end_date, 'tuition_date' => '90');
            if (isset($last_filters['student_tutiions'])) {

                $filters['student_tutiions'] = $last_filters['student_tutiions'];
            }

            if (!empty($tuition_filter)) {
                $tuition_filter = $tuition_filter['tuition_date'];
            } else {
                $tuition_filter = 90;
            }


            $response = $this->tuition->load($filters);

        } elseif (isset($filters['page'])) {

            $last_filters = session('last_filters');

            $tuition_start_date = $last_filters['start_date'];
            $tuition_end_date = $last_filters['end_date'];
            $tuition_filter = $last_filters['tuition_date'];

            $response = $this->tuition->load($last_filters);

            $filters = $last_filters;

        } elseif (!empty(session('filters'))) {

            $filters = session('filters');
            $response = $this->tuition->load($filters);
            $tuition_filter = $filters['tuition_date'];
            session()->forget('filters');

        } //set default filters i.e. laod tuitions for last 90 days.
        else {

            //set tuition date filter
            $date_filter = $this->tuition->SetDateFilters($filters);
            $tuition_start_date = $date_filter['tuition_start_date'];
            $tuition_end_date = $date_filter['tuition_end_date'];
            $tuition_filter = $date_filter['tuition_filter'];

            if (!empty($tuition_filter)) {
                $tuition_filter = $tuition_filter['tuition_date'];
            }
            $response = $this->tuition->load($filters);

        }

        $tuitions = $response['records'];
        $offset = $response['offset'];
        $count_tuitions = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];

        $classes = DB::table('classes')->get();

        //if class filter selected then load related subjects
        if (isset($filters['class']) && $filters['class'] != 0) {

            $classesid = $filters['class'];
        } else {
            $classesid = 0;
        }
        $subjects = $this->tuition->getClassSubjects($classesid);

        $genders = DB::table('gender')->get();
        $labels = DB::table('tlabels')->get();
        $categories = DB::table('tuition_categories')->get();
        $locations = DB::table('locations')->get();
        $assign_status = DB::table('tution_status')->get();
        $current_route = $this->CurrentUri;

        //dd($filters);

        return view('tuition', compact('tuitions', 'tutor', 'classes', 'subjects', 'genders', 'filters', 'pagesize', 'categories', 'current_route',
            'count_tuitions', 'perpage_record', 'offset', 'tuition_start_date', 'tuition_end_date', 'tuition_filter', 'labels', 'locations', 'assign_status'));

    }

    public function ShowFollowUpSummary(){

        $request = Request::all();
        $ids = $request['summaryids'];
        $share = ($request['partnerShare'])/100;
        $shareSummary = $this->tuition->getFollowSummary($ids,$share);

        //print_r($shareSummary); dd();

        return view('followup_summary', compact('shareSummary'));

    }

    public function ShowShortView(){

        $request = Request::all();
        $ids = $request['tuitionid'];
        $tuitions = DB::select("call tuition_short_view11('$ids')");
        $result = $this->tuition->getTuitionShortView11($tuitions);
        $tuitionDetail = $result['tuitionDetail'];
        $smsText = $result['smsText'];
        return response()->json(['success' => true, 'tuitionDetail' => $tuitionDetail, 'smsText' => $smsText]);
    }
    public function ShowSummary()
    {

        $request = Request::all();
        $ids = $request['summaryids'];
        $result = $this->tuition->getTuitionsSummary($ids);
        $partnersShare = $result['partners'];
        //print_r($partnersShare); dd();

        //set variables for agents count and amount.
        $agentOneTuitions = 0;
        $agentTwoTuitions = 0;
        $agentOneShare = 0;
        $agentTwoShare = 0;
        $academyTuitions = 0;
        $academyShare = 0;
        $partnerAmount = 0;
        foreach ($partnersShare as $partner) {

            $agentOneTuitions += $partner->agentOneCount;
            $agentTwoTuitions += $partner->agentTwoCount;
            $agentOneShare += $partner->agentOneShare;
            $agentTwoShare += $partner->agentTwoShare;
            $academyTuitions += $partner->academyTuitions;
            $academyShare += $partner->academyShare;
            $partnerAmount += $partner->partnerShare;
        }

        if ($academyShare > 0) {

            $academyShare = ($academyShare - ($agentOneShare + $agentTwoShare + $partnerAmount));
        }


        return view('tuitionsummary', compact('partnersShare', 'agentOneShare',
            'agentOneTuitions', 'agentTwoShare', 'agentTwoTuitions', 'academyShare', 'academyTuitions'));
    }

    public function CreateGlobalList(Request $request)
    {

        $request = Request::all();
        //single selected tuition to be added in broadcast list
        if (isset($request['tuitionid'])) {

            $tuitionid = $request['tuitionid'];
            $tuition = tuition_global::where('tuition_id', '=', $tuitionid)->get()->toArray();
            //if tuition not exist in list.
            if (!isset($tuition[0]['id'])) {

                $this->tuition->CreateGlobalList($tuitionid);

            }
        } //bulk tuition to be added in broadcast list
        else {

            $ids = $request['ids'];
            $tuitionids = explode(',', $ids);
            for ($j = 0; $j < count($tuitionids); $j++) {

                $tuition = tuition_global::where('tuition_id', '=', $tuitionids[$j])->get()->toArray();
                if (!isset($tuition[0]['id'])) {

                    $this->tuition->CreateGlobalList($tuitionids[$j]);

                }
            }


        }

        return response()->json(['success' => true, 'tuition' => $tuition]);

    }

    public function SaveSingleTuitionLabels()
    {

        $request = Request::all();
        $last_filters = session('last_filters');
        $this->tuition->SaveSelectedTuitionLabels($request);
        return redirect('admin/tuitions')->with('filters', $last_filters)->with('status', 'New labels attached to selected tutiions successfully!');
    }

    public function AddTuitionLabels(Request $request)
    {

        $request = Request::all();
        $last_filters = session('last_filters');
        $ids = $request['lids'];
        $bulkLabels = $request['bulkLabels'];
        if (isset($request['appendLabels'])) {
            $appendLabels = true;
        } else {

            $appendLabels = false;
        }

        $tuitionids = explode(',', $ids);
        $this->tuition->SaveNewTuitionLabels($tuitionids, $bulkLabels, $appendLabels);

        return redirect('admin/tuitions')->with('filters', $last_filters)->with('status', 'New labels attached to selected tutiions successfully!');

    }

    public function AddTuitionFollowupLabels(Request $request)
    {

        $request = Request::all();
        $last_filters = session('last_filters');
        $ids = $request['lids'];
        $bulkLabels = $request['bulkLabels'];
        if (isset($request['appendLabels'])) {
            $appendLabels = true;
        } else {

            $appendLabels = false;
        }

        $tuitionids = explode(',', $ids);
        $this->tuition->SaveNewTuitionLabels($tuitionids, $bulkLabels, $appendLabels);

        return redirect('admin/tuitions/followup')->with('filters', $last_filters)->with('status', 'Labels Set Successfully!');

    }

    public function StarSave(){

        $request = Request::all();
        $ids = $request['starid'];
        $status = $request['starunstar'];
        $tuitionids = explode(',', $ids);

        for($j=0;$j<count($tuitionids);$j++){

            Tuition::where('id', $tuitionids[$j])->update(['is_started' => $status]);
        }

        return redirect('admin/tuitions/followup')->with('status', 'Star Status Updated Successfully!');

    }

    public function TuitionBraodcastIsApprove(){

        $request = Request::all();
        $ids = $request['is_approved_popup_id'];
        $approved = $request['is_approved_popup'];
        $tuitionids = explode(',', $ids);

        for($j=0;$j<count($tuitionids);$j++){

            Tuition::where('id', $tuitionids[$j])->update(['is_approved' => $approved]);
        }

        return redirect('admin/global/tuitions')->with('status', 'Approval Status Updated Successfully!');

    }

    public function UpdateTutiionStatus(Request $request)
    {

        $request = Request::all();
        $ids = $request['tids'];
        $tuitioStatus = $request['tuitioStatus'];

        $tuitionids = explode(',', $ids);
        for ($j = 0; $j < count($tuitionids); $j++) {

            Tuition::where('id', $tuitionids[$j])->update(['tuition_status_id' => $tuitioStatus]);
        }

        return response()->json(['success' => true]);

    }

    public function UpdateFollowUpTuitionStatus(Request $request)
    {

        $request = Request::all();
        $ids = $request['tids'];
        $tuitioStatus = $request['tuitioStatus'];

        $tuitionids = explode(',', $ids);
        for ($j = 0; $j < count($tuitionids); $j++) {

            Tuition::where('id', $tuitionids[$j])->update(['tuition_status_id' => $tuitioStatus]);
        }

        return redirect("admin/tuitions/followup")->with('status', 'Status Updated Successfully');

    }

    public function GlobalListBroadCast(Request $request)
    {

        $request = Request::all();

        if (isset($request['pagesize'])) {

            $pageSize = $request['pagesize'];
        } else {

            $pageSize = 1000;
        }

        $globalTuitions = DB::select("call  global_tuitions()");

        $data = $this->tuition->loadTuitions($globalTuitions, $pageSize);
        $tuitions = $data['records'];
        $totalRecords = $data['count'];
        $offset = $data['offset'];
        $pagesize = $data['pagesize'];
        $perpage_record = $data['perpage_record'];

        $current_route = $this->CurrentUri;

        return response()->json(['success' => true, 'tuitions' => (array)$tuitions]);

    }

    public function GloablSmsText()
    {

        $filters = Request::all();

        //get global tuitions sms text
        $broadcastSMS = DB::select("call  get_sms_text");

        // Set  Filters
        /*if (isset($filters['gender_p'])) {
            $gender_P = $filters['gender_p'];
        } else {
            $gender_P = 1;
        }*/
        if (isset($filters['fee_p'])) {
            $fee_p = $filters['fee_p'];
        } else {
            $fee_p = 1;
        }
        if (isset($filters['suitable_timings_p'])) {
            $suitable_timings_p = $filters['suitable_timings_p'];
        } else {
            $suitable_timings_p = 1;
        }
        if (isset($filters['location_p'])) {
            $location_p = $filters['location_p'];
        } else {
            $location_p = 1;
        }
        if (isset($filters['special_requirement_p'])) {
            $special_requirement_p = $filters['special_requirement_p'];
        } else {
            $special_requirement_p = 1;
        }
        if (isset($filters['teaching_duration_p'])) {
            $teaching_duration_p = $filters['teaching_duration_p'];
        } else {
            $teaching_duration_p = 1;
        }
        if (isset($filters['institution_p'])) {
            $institution_p = $filters['institution_p'];
        } else {
            $institution_p = 1;
        }
        if (isset($filters['subjectPreference_p'])) {
            $subjectPreference_p = $filters['subjectPreference_p'];
        } else {
            $subjectPreference_p = 1;
        }

        $smsText = '';
        foreach ($broadcastSMS as $sms) {

            if ($subjectPreference_p == 1) {
                $smsText .= trim($sms->subject);
                $smsText .= "\r\n";
            }

            if ($institution_p == 1) {
                $smsText .= trim($sms->institute_name);
            }


            if ($location_p == 1) {
                $smsText .= " " . trim($sms->location_name);
            }


           /* if ($gender_P == 1) {
                $smsText .= " " . trim($sms->gender);
            }*/


            if ($suitable_timings_p == 1) {
                $smsText .= " " . trim($sms->suitable_timings);
                $smsText .= "\r\n";
            }


            if ($special_requirement_p == 1) {
                $smsText .= trim($sms->special_notes);
                $smsText .= "\r\n";
            }

            if ($teaching_duration_p == 1) {
                $smsText .= trim($sms->teaching_duration . "mins");
                $smsText .= "\r\n";
            }


            if ($fee_p == 1) {
                $smsText .= trim($sms->tuition_fee) . "K - " . trim($sms->tuition_max_fee) . "K";
                $smsText .= "\r\n";
            }


            $smsText .= "\r\n";
        }


        return view('broadcast_sms', compact('smsText', 'filters'));

    }

    public function TuitionShortView($tuitionID)
    {
        $tuitionDetails = DB::select("call  tuition_short_view('$tuitionID')");
        $result = $this->tuition->getTuitionShortView($tuitionDetails);
        $smsText = $result['smsText'];
        $tuitionDetail = $result['tuitionDetail'];

        return response()->json(['success' => true, 'smsText' => $smsText, 'tuitionText' => $tuitionDetail]);
    }

    public function GlobalList(Request $request)
    {

        $request = Request::all();

        if (isset($request['pagesize'])) {

            $pageSize = $request['pagesize'];
        } else {

            $pageSize = 50;
        }

        //get global tuitions
        $globalTuitions = DB::select("call  global_tuitions()");


        $data = $this->tuition->loadTuitions($globalTuitions, $pageSize);
        $tuitions = $data['records'];
        $totalRecords = $data['count'];
        $offset = $data['offset'];
        $pagesize = $data['pagesize'];
        $perpage_record = $data['perpage_record'];

        //get tuition id's for broadcast list.
        $tuition_ids = '';
        foreach ($tuitions as $tuition) {

            $tuition_ids .= $tuition->contact_no . ';';
        }
        //remove last , from string
        $tuition_ids = rtrim($tuition_ids, ";");

        $current_route = $this->CurrentUri;
//dd($tuitions);
        return view('tuition_global', compact('tuitions', 'totalRecords', 'offset', 'pagesize', 'perpage_record', 'current_route', 'tuition_ids', 'smsText'));

    }


    public function SetTuitionDateFiler()
    {

        $filter = Request::all();
        if ($filter['date_filter'] == 'custom') {

            return response()->json(['success' => 'custom']);

        } else {

            $date_filter = $this->tuition->SetDateFilters($filter['date_filter']);
            return response()->json(['success' => true, 'result' => $date_filter]);
        }


    }

    public function TuitionDetailView()
    {
        $status = 'add';
        $tuition_code = DB::table('tuitions')
            ->select('tuition_code')
            ->orderBy('id', 'desc')
            ->first();
        $code = "T" . date('ym');
        //dd($tuition_code);
        $newStr = ++$tuition_code->tuition_code;
        $last_part = substr($newStr, 5);
        $latest_code = substr_replace($last_part, $code, 0, 0);

        $locations = DB::table('locations')->get();
        $classes = DB::table('class_subject_mappings')
            ->join('classes', 'classes.id', '=', 'class_subject_mappings.class_id')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', DB::raw('CONCAT(classes.name, " - ", subjects.name) as name '))
            ->orderBy(DB::raw('CONCAT(classes.name, " - ", subjects.name)'))
            ->get();

        $assign_status = DB::table('tution_status')->get();
        $tuition_category = DB::table('tuition_categories')->get();
        $notes = DB::table('special_notes')->get();
        $labels = DB::table('tlabels')->get();
        $gender = DB::table('gender')->get();
        $bands = DB::table('teacher_bands')->get();
        $instututes = DB::table('institutes')->get();
        $referrers = DB::table('referrers')->get();

        $preferredinstitute = array();
        $selected_classes = array();
        $tuition_details = '';

        return view('tuitiondetail', compact('locations', 'tuition', 'latest_code', 'classes', 'assign_status', 'bands', 'selected_classes'
            , 'tuition_category', 'notes', 'status', 'tuition_details', 'labels', 'gender', 'instututes', 'preferredinstitute', 'referrers'));

    }

    public function TuitionEditView($id)
    {
        $status = 'update';
        $locations = DB::table('locations')->get();

        $classes = DB::table('class_subject_mappings')
            ->join('classes', 'classes.id', '=', 'class_subject_mappings.class_id')
            ->join('subjects', 'subjects.id', '=', 'class_subject_mappings.subject_id')
            ->select('class_subject_mappings.id', DB::raw('CONCAT(classes.name, " - ", subjects.name) as name '))
            ->orderBy(DB::raw('CONCAT(classes.name, " - ", subjects.name)'))
            ->get();

        $assign_status = DB::table('tution_status')->get();
        $bands = DB::table('teacher_bands')->get();
        $tuition_category = DB::table('tuition_categories')->get();
        $notes = DB::table('special_notes')->get();

        $tuition_details = DB::table('tuition_details')
            ->join('class_subject_mappings', 'class_subject_mappings.id', '=', 'tuition_details.class_subject_mapping_id')
            ->join('classes', 'class_subject_mappings.class_id', '=', 'classes.id')
            ->join('subjects', 'class_subject_mappings.subject_id', '=', 'subjects.id')
            ->where('tuition_id', $id)
            ->select('tuition_details.*', 'class_subject_mappings.id as csmid', 'class_id',
                'subject_id', 'classes.name as class_name', 'subjects.name as subject_name')
            ->get();
        //dd($tuition_details);

        $tuition = DB::table('tuitions')->where('id', $id)->get();
        $tuition = $tuition[0];
        //dd($tuition_details);
        $labels = DB::table('tlabels')->get();
        $gender = DB::table('gender')->get();
        $instututes = DB::table('institutes')->get();
        $referrers = DB::table('referrers')->get();

        $result = DB::table('tuition_labels')->where('tuition_id', '=', $id)->get();
        $result2 = DB::table('tuition_institute_preferences')->where('tuition_id', '=', $id)->get();


        //build array for edit case to select selected labels
        $tuition_labels = array();
        foreach ($result as $r) {
            $tuition_labels[] = $r->label_id;
        }

        //build array for edit case to select  preferred institute.
        $preferredinstitute = array();
        foreach ($result2 as $r) {
            $preferredinstitute[] = $r->institute_id;
        }

        //build array for edit case to select  preferred institute.
        $selected_classes = array();
        foreach ($tuition_details as $detail) {
            $selected_classes[] = $detail->class_subject_mapping_id;
        }

        //dd($tuition);
        return view('tuitiondetail', compact('locations', 'tuition', 'classes', 'assign_status', 'tuition_category', 'bands', 'selected_classes',
            'notes', 'status', 'tuition_details', 'id', 'labels', 'tuition_labels', 'gender', 'instututes', 'preferredinstitute', 'referrers'));

    }

    public function LoadClassSubjects()
    {

        $class = Request::all();
        $id = $class['classid'];
        $result = $this->tuition->LoadSubjects($id);
        return response()->json(['success' => true, 'result' => $result]);

    }

    public function FeeCollected(){

        $request = Request::all();
        $tuitionID = $request['tuitionid'];
        $status = true;

        if(!empty($tuitionID)){

            $tutiionFinalFee = DB::table('tuitions')->select('tuition_final_fee')
                ->where('id', $tuitionID)->first();
            $tutiionFinalFee = $tutiionFinalFee->tuition_final_fee;
            if($tutiionFinalFee == 0){$status = false;}else{$status=true;}
        }

        return response()->json(['success' => $status]);

    }



    public function AssignedPendingTuition(){

        $request = Request::all();
        $tuitionID = $request['tuitionid'];
        $status = true;

        if(!empty($tuitionID)){

            $tutiion = DB::table('tuitions')->select('tuition_start_date')
                ->where('id', $tuitionID)->first();
            $tutiionStartDate = $tutiion->tuition_start_date;
            if($tutiionStartDate == '0000-00-00'){$status = false;}else{$status=true;}
        }

        return response()->json(['success' => $status]);

    }

    public function UpdateTuitionFinalFee(){

        $request = Request::all();
        $this->tuition->updateTuitionFinalFee($request);
        return redirect("admin/tuitions")->with('status', 'Tuition  Updated Successfully!');

    }

    public function UpdateTuitionStartDate(){

        $request = Request::all();
        $this->tuition->updateTuitionStartDate($request);

        return redirect("admin/tuitions")->with('status', 'Tuition Updated Successfully!');

    }

    public function DeleteCSM()
    {

        $tutiionDetail = Request::all();
        $id = $tutiionDetail['id'];
        $this->tuition->deleteGradeSubject($id);
        return response()->json(['success' => true]);

    }

    public function DeleteGlobalTuitions()
    {

        $request = Request::all();
        $this->tuition->DeleteGT($request);
        return redirect("admin/global/tuitions")->with('deleteTuitions', 'Record Deleted Successfully!');
    }

    public function EmptyGlobalList()
    {

        tuition_global::truncate();

        return redirect('admin/global/tuitions')->with('deleteTuitions', 'List Deleted Successfully!');

    }

    public function UnAssignTuition()
    {

        $request = Request::all();
        $tuition_status_id = $request['tuition_status_regular_id'];
        $tuiion_history_id = $request['tuiion_history_id'];
        $tuition_id = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $td_id = $request['td_id'];

        //Unassigne teacher from tuition details.
        $this->tuition->RemoveTeacher($td_id);
        //Remove teacher tutiion history
        $this->tuition->RemoveTeacherTuitionHistory($teacher_id, $td_id);
        //send email to unassigned teacher
        $this->mailers->UnAssignedTuition($teacher_id);

        return response()->json(['success' => true]);
    }


    public function SaveTuition()
    {

        //validate form values
        $Tuition = Request::all();


        if ($Tuition['status'] == 'add') {
            $tuition = $this->tuition->save($Tuition);
        } else {

            $tuition = $this->tuition->update($Tuition);
        }


        if ($tuition == 'saveandadd') {

            return response()->json(['success' => 'saveandadd']);

        } else {

            return response()->json(['success' => 'save']);

        }


    }

    public function GlobalMatchedTeacher()
    {

        $filters = Request::all();

        //reset loading filters
        if (isset($filters['reset'])) {

            $filters = "";
            $response = $this->tuition->LoadGlobalMatchedTeacher($filters);

        } //set filters for pagination
        elseif (isset($filters['page'])) {
            $last_filters = session('last_filters');
            $response = $this->tuition->LoadGlobalMatchedTeacher($last_filters);
            $filters = $last_filters;

        } //default loading
        else {
            $response = $this->tuition->LoadGlobalMatchedTeacher($filters);

        }
        //teachers listing
        $teachers = $response['records'];

        //get teachers id's for broadcast list.
        $contactNumbers = '';
        foreach ($teachers as $teacher) {

            $contactNumbers .= $teacher->mobile1 . ';';
        }
        //pagination
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $tuition_category_p = $response['tuition_category_p'];

        $teacher_band = DB::table('teacher_bands')->get();
        $labels = DB::table('tlabels')->select('id', 'name')->get();
        $tuitionid = '';
        $screen = $this->CurrentUri;
//dd($pagesize);

        return view('matched_global_teachers', compact('teachers', 'count', 'offset', 'perpage_record', 'screen',
            'pagesize', 'teacher_band', 'labels', 'tuitionid', 'filters', 'tuition_category_p', 'contactNumbers'));

    }

    public function MatchedTeacher($tuitionid)
    {

        $filters = Request::all();
        //set loading filters
        if (isset($filters['reset'])) {

            $filters = "";
            $response = $this->tuition->LoadMatchedTeacher($tuitionid, $filters);

        } elseif (isset($filters['page'])) {

            $last_filters = session('last_filters');
            $response = $this->tuition->LoadMatchedTeacher($tuitionid, $last_filters);
            $filters = $last_filters;

        } else {
            $response = $this->tuition->LoadMatchedTeacher($tuitionid, $filters);

        }

        $teachers = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $tuition_category_p = $response['tuition_category_p'];

        $contactNos = $this->tuition->getBroadCastTeachers();
        //get teachers id's for broadcast list.
        $contactNumbers = '';
        foreach ($teachers as $teacher) {

            $contactNumbers .= $teacher->mobile1 . ';';
        }


        $teacher_band = DB::table('teacher_bands')->get();
        $labels = DB::table('tlabels')->select('id', 'name')->get();

        return view('matched_teachers', compact('teachers', 'count', 'offset', 'perpage_record',
            'contactNumbers', 'pagesize', 'teacher_band', 'labels', 'tuitionid', 'filters', 'tuition_category_p'));

    }

    public function ViewMatched()
    {

        $tuitionid = Request::all();
        $tuitionid = $tuitionid['id'];

        $gender = DB::table('tuitions')->where('id', '=', $tuitionid)->select('teacher_gender')->get();
        $gender_id = $gender[0]->teacher_gender;

        $teachers = DB::select("call  matched_teachers('$tuitionid','$gender_id')");
        //print_r($gender_id[0]->teacher_gender); die();

        return response()->json(['success' => true, 'teachers' => $teachers]);

    }

    public function Unbookmark()
    {

        $request = Request::all();

        $tuitionid = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $tbm_id = $request['tbm_id'];

        $bookmark = $this->tuition->RemoveBookmark($tuitionid, $teacher_id);
        $tuition_details = DB::select("call  tuition_details('$tuitionid')");
        $teacher_bookmark = DB::select("call  teacher_bookmark('$tuitionid')");
        $tuition_labels = DB::select("call  tuition_labels('$tuitionid')");

        return response()->json(['success' => 'save', 'tuition_details' => $tuition_details, 'teacher_bookmark' => $teacher_bookmark,
            'tuition_labels' => $tuition_labels]);
    }

    public function BookmarkTeacher()
    {

        $request = Request::all();

        $tuitionid = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $tbm_id = $request['tbm_id'];

        $bookmark = $this->tuition->TeacherBookmark($tuitionid, $teacher_id);

        if (isset($request['global']) && $request['global'] == 'global') {

            return redirect("admin/global/teachers/matched")->with('status', 'Teacher Bookmark Successfully!');
        } else {

            return redirect("admin/teachers/matched/$tuitionid")->with('status', 'Teacher Bookmark Successfully!');
        }


    }

    public function AddToBookmarkList()
    {
        $request = Request::all();
        $this->tuition->AddToBookmarkList($request);
        return response()->json(['success' => 'save']);

    }

    public function Unbookmak()
    {
        $request = Request::all();
        $this->tuition->RemoveToBookmarkList($request);
        return response()->json(['success' => 'save']);
    }


    public function UnBookmarkTeacher()
    {

        $request = Request::all();

        $tuitionid = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $tbm_id = $request['tbm_id'];

        $bookmark = $this->tuition->RemoveBookmark($tuitionid, $teacher_id);

        if (isset($request['global']) && $request['global'] == 'global') {

            return redirect("admin/global/teachers/matched")->with('status', 'Teacher UnBookmark Successfully!');
        } else {

            return redirect("admin/teachers/matched/$tuitionid")->with('status', 'Teacher UnBookmark Successfully!');
        }


    }


    public function Bookmark()
    {

        $request = Request::all();
        //print_r($request); die();
        $tuitionid = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $tbm_id = $request['tbm_id'];

        if ($tbm_id == 1) {
            $bookmark = $this->tuition->RemoveBookmark($tuitionid, $teacher_id);
            return redirect("admin/teachers/matched/$tuitionid")->with('status', 'Teacher Unbookmark Successfully!');
            //return response()->json(['success' => $bookmark,'status'=>'0','teacher_id'=>$teacher_id]);
        } else {
            $bookmark = $this->tuition->TeacherBookmark($tuitionid, $teacher_id);
            return redirect("admin/teachers/matched/$tuitionid")->with('status', 'Teacher Bookmark Successfully!');
            //return response()->json(['success' => $bookmark,'status'=>'1','teacher_id'=>$teacher_id]);
        }


    }

    public function AssignTuitionView()
    {

        $request = Request::all();
        $tuitionid = $request['id'];
        $teacher_id = $request['teacher_id'];
        $teachers = DB::select("call  assign_tuition('$teacher_id','$tuitionid')");
        $tuition_details = DB::select("call  tuition_details('$tuitionid')");
        $teacher_bookmark = DB::select("call  teacher_bookmark('$tuitionid')");
        $tuition_labels = DB::select("call  tuition_labels('$tuitionid')");

        return response()->json(['success' => true, 'tuitions' => $teachers, 'tuition_details' => $tuition_details,
            'teacher_bookmark' => $teacher_bookmark, 'tuition_labels' => $tuition_labels]);

    }

    public function AssignedTuition(Request $request)
    {
        //get form data
        $tuition_detail_id = Request::all();

        //update tuition detail and history
        $tuition_detial = $this->tuition->UpdateTuitionDetails($tuition_detail_id);
        $tuition_hitory = $this->tuition->UpdateTtuitionHistory($tuition_detial);
        $tuition_id = $tuition_detail_id['tuitionid'];
        $teacher_id = $tuition_detail_id['teacher_id'];

        $tuition_details = DB::select("call  tuition_details('$tuition_id')");
        $teacher_bookmark = DB::select("call  teacher_bookmark('$tuition_id')");
        $tuition_labels = DB::select("call  tuition_labels('$tuition_id')");

        //send email to assigned teacher
        $this->mailers->MarkRegular($teacher_id);

        return response()->json(['success' => 'save', 'tuition_details' => $tuition_details, 'teacher_bookmark' => $teacher_bookmark,
            'tuition_labels' => $tuition_labels]);

    }

    public function TuitionDetails()
    {

        $tuition = Request::all();
        $tuition_id = $tuition['tuition_id'];
        $tuition_details = DB::select("call  tuition_details('$tuition_id')");
        $teacher_bookmark = DB::select("call  teacher_bookmark('$tuition_id')");
        $tuition_labels = DB::select("call  tuition_labels('$tuition_id')");
        $teacher_applications = DB::select("call  teacher_applications('$tuition_id')");


        return response()->json(['success' => true, 'tuition_details' => $tuition_details,
            'teacher_bookmark' => $teacher_bookmark, 'tuition_labels' => $tuition_labels, 'teacher_applications' => $teacher_applications]);

    }

    public function AssignBookmarkTeacher()
    {

        $request = Request::all();
        $this->tuition->AssignedTeacher($request);
        return response()->json(['success' => true]);
    }

    Public function MarkRegular()
    {

        $request = Request::all();
        $tuition_status_id = $request['tuition_status_regular_id'];
        $tuiion_history_id = $request['tuiion_history_id'];
        $tuition_id = $request['tuition_id'];
        $teacher_id = $request['teacher_id'];
        $td_id = $request['td_id'];
        //Mark tuition regualr
        $this->tuition->MarkRegular($tuition_status_id, $tuiion_history_id, $td_id);
        //send email to teacher
        $this->mailers->MarkRegular($teacher_id);

        $tuition_details = DB::select("call  tuition_details('$tuition_id')");
        $teacher_bookmark = DB::select("call  teacher_bookmark('$tuition_id')");
        $tuition_labels = DB::select("call  tuition_labels('$tuition_id')");

        return response()->json(['success' => true, 'tuition_details' => $tuition_details,
            'teacher_bookmark' => $teacher_bookmark, 'tuition_labels' => $tuition_labels]);

    }

    public function UpdateTuitionStatus()
    {
        $request = Request::all();
        $tuitionStatus = $this->tuition->ChangeTuitionStatus($request);
        $tutiionFinalFee = $tuitionStatus['finalFee'];
        if($tutiionFinalFee == 0){$status = false;}else{$status=true;}

        return response()->json(['success' => $status, 'statusColor' => $tuitionStatus]);
    }


    public function SendEmail($teacher_id)
    {

        $this->mailers->SendMail($teacher_id);

    }

    public function DeleteTuition($tuition_id)
    {

        $tuition = Tuition::find($tuition_id);
        try {

            $tuition->delete();

            return redirect('admin/tuitions')->with('status', 'Record Deleted Successfully!');

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/tuitions?page=1')->with('warning', 'Cannot Delete Parent Record: Please delete child records first!');
        }

    }

    public function updateQuicEditTuition()
    {

        $Tuition = Request::all();
        $this->tuition->quickUpdate($Tuition);

        return redirect('admin/tuitions/followup')->with('status', 'Updated Successfully!');

    }

    public function TuitionFollowUpQuickEdit()
    {

        $request = Request::all();
        $tuitionid = $request['tuitionid'];

        $result = $this->tuition->createFollowupQuickEditOptions($tuitionid);
        $tuition = $result['tuition'];
        $options = $result['options'];

        return response()->json(['tuition' => $tuition, 'tuitionStatus' => $options]);
    }

    public function TuitionFollowUp()
    {


        $filter = Request::all();
        //reset filters
        if (isset($filter['reset'])) {

            $filter = "";
        }

        $TuitionStatus = $this->Followup;
        $pageSize = 50;
        //get followup tuitions.
        $tuitions = $this->tuition->FollowUpTuitions($TuitionStatus, $filter);
        //get paginations
        $result = $this->tuition->Pagination($tuitions, $pageSize);
        $tuitions = $result['records'];
        //dd($tuitions);
        $current_route = $this->CurrentUri;
        //load tuition status
        $ids = [6,7,8,9];
        $tuitionStatus = DB::table('tution_status')->whereIn('id',$ids)->get();
        //load tuition status for search results
        $tuitionStatus_result = DB::table('tution_status')->get();
        //Load tutiion labels
        $labels = DB::table('tlabels')->get();
        //Load tutiion locations
        $locations = DB::table('locations')->get();

        $classes = DB::table('classes')->get();

        //if class filter selected then load related subjects
        if (isset($filter['class']) && $filter['class'] != 0) {

            $classesid = $filter['class'];
        } else {
            $classesid = 0;
        }
        $subjects = $this->tuition->getClassSubjects($classesid);
        $assign_status = DB::table('tution_status')->get();

        $tuition_start_date = date('d/m/Y', strtotime(Carbon::now()->subDays(365)));
        $tuition_end_date = date('d/m/Y', strtotime(Carbon::now()));

        return view('followupdetails', compact('tuitions', 'current_route', 'tuitionStatus', 'labels', 'result', 'filter','assign_status',
            'locations','tuition_start_date','tuition_end_date', 'assign_status', 'classes', 'subjects', 'tuitionStatus_result'));

    }

    public function tuitionstarred(){

        $filter = Request::all();
        $tuitionid = $filter['tuitionid'];
        $isStarted = $filter['isStarted'];

        if ($isStarted == 0 ){
            $starred = DB::table('tuitions')
                ->where('id', $tuitionid)
                ->update(['is_started' => 1]);
        }elseif($isStarted == 1 ){
            $starred = DB::table('tuitions')
                ->where('id', $tuitionid)
                ->update(['is_started' => 0]);
        }
        return response()->json(['success' => true,
            'tuitionid' => $tuitionid, 'isStarted' => $isStarted]);
    }



    public static function AssignedTeachers($tid)
    {

        //Load assigned teacher for tuitions
        $teachers = DB::table('teachers')
            ->join('tuition_details', 'tuition_details.teacher_id', '=', 'teachers.id')
            ->select('teachers.id as teacherid', 'teachers.fullname', 'teachers.mobile2',
                'teachers.teacher_photo', 'tuition_details.tuition_id as tid', 'teachers.mobile1', 'teachers.email')
            ->where('tuition_details.tuition_id', '=', $tid)
            ->groupBy('teachers.id')
            ->get();

        return $teachers;

    }

    public function TuitionSubjects($class_id)
    {

        $options = "<option value='0' selected>All</option>";
        if ($class_id == 0) {

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


        echo $options;
    }

    public function ChangeApplicationStatus(Request $request)
    {

        $request = Request::all();
        $applicationids = $request['application_id'][0];
        $currentTuitionId = $request['currentTuitionId'][0];

        $options = "<option value=''></option>";
        $applicationStatus = application_status::orderBy('name')->get();

        foreach ($applicationStatus as $status) {

            $options .= "<option value='$status->id' >$status->name</option> ";
        }

        return response()->json(['success' => true, 'application_list' => $options,
            'applicationids' => $applicationids, 'currentTuitionId' => $currentTuitionId]);

    }

    public function ApplyTuitionStatus()
    {

        $request = Request::all();
        $this->tuition->SaveTuitionStatus($request);
        $currentTuitionId = $request['currentTuitionId2'];
        return response()->json(['success' => true, 'currentTuitionId2' => $currentTuitionId]);
    }


    public function TuitionClassified()
    {

         $q_locations = "(
                            SELECT COUNT(*), loc.locations,loc.id,COUNT(loc.locations) as locations_count FROM tuitions 
                            INNER JOIN locations loc on loc.id = tuitions.location_id 
                            WHERE tuition_status_id=1 or tuition_status_id=4 or tuition_status_id=5 
                            GROUP BY loc.locations
                            ORDER BY locations_count DESC,loc.locations
                            LIMIT 10
                         )
                        UNION ALL
                        (
                           SELECT COUNT(*),others,t1.id,  count(locations_count) as others_count
                            FROM (
                                SELECT COUNT(*), loc.id,'others',COUNT(loc.id) as locations_count
                                FROM tuitions 
                                INNER JOIN locations loc on loc.id = tuitions.location_id
                                WHERE tuition_status_id=1 or tuition_status_id=4 or tuition_status_id=5
                                GROUP BY loc.id
                                ORDER BY locations
                                LIMIT 20 OFFSET 10
                            )AS t1
                         )";

        $q_gender = "SELECT gender.name,COUNT(gender.name) as gender_count
                        FROM tuitions 
                        INNER JOIN gender on gender.id = tuitions.teacher_gender
                        WHERE tuition_status_id=1 or tuition_status_id=4 or tuition_status_id=5
                        GROUP BY tuitions.teacher_gender";

        $q_category = "SELECT tc.name,COUNT(tc.name) as category_count
                        FROM tuitions 
                        INNER JOIN tuition_categories tc on tc.id = tuitions.tuition_catefory_id
                        WHERE tuition_status_id=1 or tuition_status_id=4 or tuition_status_id=5
                        GROUP BY tuitions.tuition_catefory_id";

        $locations = DB::select(DB::raw($q_locations));
        $gender = DB::select(DB::raw($q_gender));
        $category = DB::select(DB::raw($q_category));
        //no of locations
        $locations_length = count($locations);
        $location_values = "";
        //string of locations names
        $location_name = "";
        //tottal tutiions i.e. max value for y-xis in chart js
        $TotalTuitions = 0;
        $count = 1;

        foreach ($locations as $location) {

            $location_values .= "$location->locations_count";
            $location_name .= "'$location->locations'";


            if ($locations_length > $count) {
                $location_values .= ",";
                $location_name .= ",";
            }
            $count++;
        }

        //no of tuitions categories
        $category_length = count($category);
        $category_values = "";
        $category_name = "";
        $count = 1;
        foreach ($category as $cat) {

            $category_values .= "$cat->category_count";
            $category_name .= "'$cat->name'";
            $TotalTuitions += $cat->category_count;

            if ($category_length > $count) {
                $category_values .= ",";
                $category_name.=",";
            }
            $count++;

        }
       //print_r($TotalTuitions); dd();
        return view('tuitions_classified', compact('locations', 'gender', 'category',
            'location_name', 'location_values','category_values','TotalTuitions','category_name'));
    }

}
