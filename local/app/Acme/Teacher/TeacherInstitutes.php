<?php

namespace Acme\Teacher;


use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Validator;
use File;
use Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeacherInstitutes
{

    public function load($records,$links,$filters)
    {
        $last_filters = session(['last_filters' => $filters]);

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($records);
        //Define how many items we want to be visible in each page
        if(isset($filters['pagesize']) && $filters['pagesize']>0){

            $perPage = $filters['pagesize'];
        }else{
            $perPage = 50;
        }
        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $records= new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $records->setPath($links);
        //Total records
        $count_records = count($collection);
        //count of records on current page.
        if($currentPage==1){

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        }else{

            $offset =  ($perPage*$currentPage)-($perPage-1);
            $perpage_record = $offset + (count($currentPageSearchResults)-1);
        }

       return $data = array(
            'count'  =>$count_records,
            'perpage_record'   => $perpage_record,
            'offset' => $offset,
            'records'=>$records,
           'pagesize'=>$perPage
        );


    }

    public function save($formdata,$modelObj){

        $name = $formdata['name'];
        $id = $formdata['id'];
        $status = $formdata['status'];

        $obj= new $modelObj();
        $obj->name = $name;
        $obj->created_at = date('Y-m-d H:i:s', time());
        $obj->updated_at = date('Y-m-d H:i:s', time());

        if($status=='add'){

            $obj->save();

        }else{

            $obj =  $modelObj::find($id);
            $obj->name = $name;
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if(!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd'){

            return 'saveandadd';

        }else{

            return 'save';

        }


    }

    public function delete($modalobj, $id){

        $obj = $modalobj::find($id);

        try{
            $obj->delete();
        }catch ( \Illuminate\Database\QueryException $e) {

            //return redirect($link)->with('warning', $e->errorInfo[2]);
        }
    }

    public function delete_teacher_label($modalobj, $id){

        $obj = $modalobj::find($id);

        try{
            $obj->delete();
        }catch ( \Illuminate\Database\QueryException $e) {

            //return redirect($link)->with('warning', $e->errorInfo[2]);
        }
    }

    public function delete_tuition_label($modalobj, $id){

        $obj = $modalobj::find($id);

        try{
            $obj->delete();
        }catch ( \Illuminate\Database\QueryException $e) {

            //return redirect($link)->with('warning', $e->errorInfo[2]);
        }
    }

    public function teachers($filters){

        $degree_levels= '';
        $subject_list = '';
        $location_list = '';

        if (isset($filters['reset'])) {

            $firstname = ""; $zipcode=""; $teacher_band_id=""; $marital_status_id=""; $gender_id="";
            $father_name=""; $expected_minimum_fee=""; $cnic_number=""; $mobile1=""; $city=""; $province="";
            $email="";

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
                $expected_minimum_fee = "";
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

            if(isset($filters['degree_level'])){

                $degree_level  = $filters['degree_level'];
                $csv = count($degree_level);
                $count = 1;
                foreach($degree_level as $level){
                    $degree_levels .= $level;
                    if($count<=$csv){
                        $degree_levels .=',';
                    }
                    $count++;
                }

            }

            if(isset($filters['subjects'])){

                $subjects  = $filters['subjects'];
                $csv = count($subjects);
                $count = 1;
                foreach($subjects as $subject){
                    $subject_list .= $subject;
                    if($count<=$csv){
                        $subject_list .=',';
                    }
                    $count++;
                }

            }

            if(isset($filters['locations'])){
                //$location_list = 'SELECT loc.id = ';
                $locations  = $filters['locations'];
                $csv = count($locations);
                $count = 1;
                foreach($locations as $location){
                    $location_list .= $location;
                    if($count<=$csv){
                        $location_list .=',';
                    }
                    $count++;
                }

            }

        }

        //print_r($locations); die();

        $teachers = DB::select("call  load_teachers('$firstname','$zipcode','$teacher_band_id','$marital_status_id',
                        '$gender_id','$father_name','$expected_minimum_fee','$cnic_number','$mobile1','$city','$province',
                        '$email','$degree_levels','$subject_list','$location_list')");
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($teachers);
        //Define how many items we want to be visible in each page
        $perPage = 10;
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
            'count'  =>$count_teachers,
            'perpage_record'=> $perpage_record,
            'offset' => $offset,
            '$records'=>$teachers
        );

    }



}