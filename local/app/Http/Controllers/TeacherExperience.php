<?php

namespace App\Http\Controllers;

use App\teacher_experience;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;



class TeacherExperience extends Controller
{

    public function SetExperienceMessage(){

        return redirect('experiences')->with('status', 'Record Save Successfully!');
    }

    public function TeacherExperienceAddView($teacherid)
    {
        //$experience = DB::table('teacher_experiences')->get();
        $status = 'add';
        return view('tutor-experience', compact('experience', 'status', 'teacherid'));

    }

    public function TeacherExperienceEditView($eid)
    {

        $teacher_experiences = DB::table('teacher_experiences')->where('id', $eid)->get();
        $experiences = $teacher_experiences[0];
        $status = 'update';
        return view('tutor-experience', compact('experiences', 'status'));

    }

    public function TeacherExperienceSave(Request $request)
    {

        //validate form values
        $this->validate(request(), [
            'expeience' => 'required',
            'experience_document' => 'mimes:jpeg,bmp,png,pdf,docx,xlsx'
        ]);

        //get form data
        $Experience = Request::all();
        $teacherid = $Experience['teacherid'];
        $eid = $Experience['id'];
        $status = $Experience['status'];
        $expeience = $Experience['expeience'];
        $experience_document = Request::file('experience_document');



        if ($status == 'add') {

            $teacher_experience = new teacher_experience();
            $teacher_experience->teacher_id = $teacherid;
            $teacher_experience->experience = $expeience;
            $teacher_experience->created_at = date('Y-m-d H:i:s', time());
            $teacher_experience->updated_at = date('Y-m-d H:i:s', time());

            if (isset($experience_document)) {

                $filename = $experience_document->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/experience/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }

                $teacher_experience->experience_document = $filename;
                $this->upload_teacher_documents($experience_document, $path, $filename);

            } else {

                $teacher_experience->experience_document = $Experience['document'];
            }

            $teacher_experience->save();


        } //update experience
        else {

            $teacher_experience = teacher_experience::find($eid);

            $teacher_experience->teacher_id = $teacherid;
            $teacher_experience->experience = $expeience;

            //save experience document
            if (isset($experience_document)) {

                $filename = $experience_document->getClientOriginalName();
                $path = base_path() . "/teachers/$teacherid/experience/";

                if (file_exists($path . $filename)) {
                    $filename = time() . '-' . $filename;
                }

                $teacher_experience->experience_document = $filename;
                $this->upload_teacher_documents($experience_document, $path, $filename);

            } else {

                $teacher_experience->experience_document = $Experience['document'];
            }
            $teacher_experience->updated_at = date('Y-m-d H:i:s', time());

            $teacher_experience->save();


        }

        if (!empty($Experience['submitbtnValue']) && $Experience['submitbtnValue'] == 'saveadd') {

            return response()->json(['success' => 'saveandadd', 'teacherid' => $teacherid]);

        } else {

            return response()->json(['success' => 'save', 'teacherid' => '']);

        }


    }

    public function TeacherExperienceDelete($eid)
    {

        $newStr = explode("-", $eid);

        if (count($newStr)>1) {
            $eid = $newStr[0];
        } else {
            $eid = $eid;
        }

        $experience = teacher_experience::find($eid);
        try {
            $experience->delete();

            if (isset($experience->experience_document)) {

                $filename = $experience->experience_document;
                $teacherid = $experience->teacher_id;

                $path = base_path() . "/teachers/$teacherid/experience/";

                if (file_exists($path . $filename)) {

                    File::delete($path . $filename);

                }

            }

            if(count($newStr)>1){
                return redirect('experiences')->with('deleted', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('deleted', 'Record Deleted Successfully!');
            }



        } catch (\Illuminate\Database\QueryException $e) {

            return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
        }

    }

    public function TeacherExperienceDisplayDocs($teacherid, $docname)
    {
        $status = "Experience";
        return view('tutor-experience-docs', compact('status', 'teacherid', 'docname'));
    }

    public function showTeacherExperience()
    {

        $degree_level = DB::table('teacher_degree_level')->get();
        return view('tutor-experience', compact('degree_level'));

    }

    public function EditTeacherExperience()
    {

        $degree_level = DB::table('teacher_degree_level')->get();
        return view('tutor-experience', compact('degree_level'));

    }

    public function AddExperience(Request $request)
    {

        $name = Request::input('organization');
        dd(count($name));

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
