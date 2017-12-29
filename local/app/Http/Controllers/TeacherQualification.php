<?php

namespace App\Http\Controllers;

use App\teacher_qualification;
use Auth;
use Mail;
use Request;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Illuminate\Support\Facades\Route;

class TeacherQualification extends Controller
{

    protected $CurrentUri;

    public function __construct()
    {

        //initialize current uri.
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();

    }

    public function SetQualificationMessage(){

        return redirect('qualifications')->with('status', 'Record Save Successfully!');
    }

    public function TeacherQualificationAddView($teacherid)
    {
        $status = 'add';
        return view('tutor-qualification', compact('status','teacherid'));

    }

    public function TeacherQualificationEditView($qid)
    {

        $teacher_qualifications = DB::table('teacher_qualifications')->where('id', $qid)->get();
        $qualifications = $teacher_qualifications[0];
        $status = 'update';

        return view('tutor-qualification', compact('qualifications', 'status'));

    }

    public function TeacherQualificationSave(Request $request)
    {

        //validate form values
        $this->validate(request(), [
            'passing_year' => 'required',
            'institution' => 'required',
            'grade' => 'required',
            'qualification_name' => 'required',
            'degree_document' => 'mimes:jpeg,bmp,png,pdf,docx,xlsx'
        ]);

        //get form data
        $Qualification = Request::all();
        $teacherid = $Qualification['teacherid'];
        $qid = $Qualification['qid'];
        $status = $Qualification['status'];
        $continue = $Qualification['continue'];
        $highest_degree = $Qualification['highest_degree'];
        if($continue=='completed'){
            $higher_degree = '';
        }else{
            $higher_degree = $Qualification['higher_degree'];
        }

        $elective_subjects = $Qualification['elective_subjects'];
        $passing_year = $Qualification['passing_year'];
        $qualification = $Qualification['qualification_name'];
        $institution = $Qualification['institution'];
        $grade = $Qualification['grade'];


        $degree_document = Request::file('degree_document');

        if ($status == 'add') {

            $teacher_qualification = new teacher_qualification();
            $teacher_qualification->teacher_id = $teacherid;
            $teacher_qualification->passing_year = $passing_year;
            $teacher_qualification->institution = $institution;
            $teacher_qualification->grade = $grade;
            $teacher_qualification->qualification_name = $qualification;
            $teacher_qualification->highest_degree = $highest_degree;
            $teacher_qualification->elective_subjects = $elective_subjects;
            $teacher_qualification->status = $continue;
            $teacher_qualification->higher_degree = $higher_degree;
            $teacher_qualification->created_at = date('Y-m-d H:i:s', time());
            $teacher_qualification->updated_at = date('Y-m-d H:i:s', time());

            if (isset($degree_document)) {

                $filename = $degree_document->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/qualification/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }

                $teacher_qualification->degree_document = $filename;
                $this->upload_teacher_documents($degree_document, $path, $filename);

            } else {

                $teacher_qualification->degree_document = $Qualification['document'];
            }

            $teacher_qualification->save();


        } else {

            $teacher_qualification = teacher_qualification::find($qid);

            $teacher_qualification->teacher_id = $teacherid;
            $teacher_qualification->passing_year = $passing_year;
            $teacher_qualification->institution = $institution;
            $teacher_qualification->grade = $grade;
            $teacher_qualification->qualification_name = $qualification;
            $teacher_qualification->highest_degree = $highest_degree;
            $teacher_qualification->higher_degree = $higher_degree;
            $teacher_qualification->elective_subjects = $elective_subjects;
            $teacher_qualification->status = $continue;
            $teacher_qualification->updated_at = date('Y-m-d H:i:s', time());

            if (isset($degree_document)) {

                $filename = $degree_document->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/qualification/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }

                $teacher_qualification->degree_document = $filename;
                $this->upload_teacher_documents($degree_document, $path, $filename);

            } else {

                $teacher_qualification->degree_document = $Qualification['document'];
            }



            $teacher_qualification->save();


        }

        if (!empty($Qualification['register']) && $Qualification['register'] == 'front') {

            $register = 'front';

        } else {

            $register = '';
        }


        if (!empty($Qualification['submitbtnValue']) && $Qualification['submitbtnValue'] == 'saveadd') {

            return response()->json(['success' => 'saveandadd', 'teacherid' => $teacherid, 'register' => $register]);

        } else {

            return response()->json(['success' => 'save', 'teacherid' => '', 'register' => $register]);

        }

    }

    public function TeacherQualificationDelete($qid)
    {
        $newStr = explode("-", $qid);

        if (count($newStr)>1) {
            $qid = $newStr[0];
        } else {
            $qid = $qid;
        }

        $qualification = teacher_qualification::find($qid);

        try {


            if (isset($qualification->degree_document)) {

                $filename = $qualification->degree_document;
                $teacherid = $qualification->teacher_id;

                $path = base_path() . "/teachers/$teacherid/qualification/";

                if (file_exists($path . $filename)) {

                    File::delete($path . $filename);

                }

            }

            $qualification->delete();

            if(count($newStr)>1){
                return redirect('qualifications')->with('deleted', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('deleted', 'Record Deleted Successfully!');
            }


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }

    public function DeleteQualification(){

        $request = Request::all();
        $id = $request['id'];
        //find qualification to be deleted
        $qualification = teacher_qualification::find($id);
        //delete associated degreed documented for qualification record.
        if (isset($qualification->degree_document)) {

            $filename = $qualification->degree_document;
            $teacherid = $qualification->teacher_id;

            $path = base_path() . "/teachers/$teacherid/qualification/";

            if (file_exists($path . $filename)) {

                File::delete($path . $filename);

            }

        }
        //delete qualification record
        try{

            $qualification->delete();

            return response()->json(['success' => true]);
        }
        catch (\Illuminate\Database\QueryException $e) {

            return response()->json(['success' => false,'response'=>$e->errorInfo[2]] );
        }

    }

    public function EditTeacherQualification()
    {

        $teachers = DB::table('teachers')->get();
        $degree_level = DB::table('teacher_degree_level')->get();
        return view('tutor-qualification', compact('degree_level', 'teachers'));

    }

    public function TeacherQualificationDisplayDocs($teacherid, $docname)
    {
        $status = "Qualification";
        return view('tutor-qualification-docs', compact('status', 'teacherid', 'docname'));
    }

    public function LoadQualifications()
    {

        $user = Auth::user();
        $teacher = DB::table('teachers')->where('user_id', '=', $user->id)->get();
        $teacherid = $teacher[0]->id;

        $qualifications = DB::table('teacher_qualifications')
            ->select('teacher_qualifications.*')
            ->where('teacher_id', '=', $teacherid)->get();

        $current_route = $this->CurrentUri;
        return view('teachers.qualifications', compact('qualifications','current_route'));

    }

    public function TeacherQualifications(Request $request)
    {
        //get form data
        $Q = Request::all();

        $qualification = new teacher_qualification;
        $qualification->teacher_id = $Q['teacherid'];
        $qualification->save();

        $path = base_path() . '/teachers/4/';
        if (!File::exists($path)) {

            File::makeDirectory($path, 0777, true);

            $file = Request::file('degree1');
            // echo 'File Name: '.$file->getClientOriginalName();
            // print_r($file); die();

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



}
