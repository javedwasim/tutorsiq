<?php

namespace App\Http\Controllers;


use App\teacher_reference;
use Auth;
use Illuminate\Support\Facades\Input;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;

class TeacherReference extends Controller
{
    public function TeacherReferenceAddView($teacherid)
    {
        $status = 'add';
        return view('tutor-reference', compact('references', 'status', 'teacherid'));
    }

    public function TeacherReferenceEditView($rid)
    {

        $teacher_reference = DB::table('teacher_references')->where('id', $rid)->get();
        $references = $teacher_reference[0];
        $status = 'update';
        return view('tutor-reference', compact('references', 'status'));

    }

    public function TeacherReferenceSave(Request $request)
    {

        //validate form values
        $this->validate(request(), [
            'name' => 'required',
            'contact_no' => 'required',
            'cnic_no' => 'required',
            'address' => 'required',
            'relationship' => 'required'
        ]);

        //get form data
        $Reference = Request::all();

        $teacherid = $Reference['teacher_id'];
        $rid = $Reference['id'];
        $status = $Reference['status'];

        if ($status == 'add') {

            teacher_reference::create(Input::all());


        } else {

            $teacher_reference = teacher_reference::findOrFail($rid);
            $teacher_reference->fill(Input::all());
            $teacher_reference->save();
        }

        if (!empty($Reference['submitbtnValue']) && $Reference['submitbtnValue'] == 'saveadd') {

            return response()->json(['success' => 'saveandadd', 'teacherid' => $teacherid]);

        } else {

            return response()->json(['success' => 'save', 'teacherid' => '']);

        }


    }

    public function TeacherReferenceDelete($rid)
    {
        $newStr = explode("-", $rid);

        if (count($newStr)>1) {
            $rid = $newStr[0];
        } else {
            $rid = $rid;
        }

        $reference = teacher_reference::find($rid);
        try {
            $reference->delete();

            if(count($newStr)>1){
                return redirect('references')->with('status', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('status', 'Record Deleted Successfully!');
            }


        } catch (\Illuminate\Database\QueryException $e) {

            if(count($newStr)>1){
                return redirect('references')->with('status', 'Record Deleted Successfully!');
            }else{
                return redirect('admin/teachers')->with('warning', $e->errorInfo[2]);
            }


        }

    }
}
