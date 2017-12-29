<?php

namespace App\Http\Controllers;

use App\special_note;
use Auth;
use Mail;
use Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use File;
use Storage;
use Acme\SpecialNotes\SpecialNoteEvents;
use Illuminate\Support\Facades\Route;

class SpecialNotes extends Controller
{
    protected $notes;
    protected $CurrentUri;

    public function __construct(SpecialNoteEvents $notes)
    {
        $this->notes = $notes;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function AdminNotes(){

        return redirect("admin/notes")->with('status','Note Save Successfully!');
    }

    public function LoadNotes(Request $request){

        $filters = Request::all();

        $notes = DB::table('special_notes')->get();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->notes->load($notes,'notes',$last_filters);


        }else{
            $response = $this->notes->load($notes,'notes',$filters);
        }

        //$response = $this->notes->load($notes,'notes',$filters);
        $notes = $response['records'];
        $offset = $response['offset'];
        $count_notes = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('note',compact('notes','offset','count_notes','perpage_record','pagesize','current_route'));

    }


    public function NoteView(){
        $status = 'add';
        return view('note_save',compact('status'));

    }

    public function NoteSave(Request $request){
        //validate form values
        $this->validate(request(), [
            'name' => 'required',

        ]);
        //get form data
        $note = Request::all();
        $redirect =  $this->notes->save($note,new special_note());
        return response()->json(['success' =>$redirect]);

    }

    public function NoteEditView($id){
        $status = 'update';
        $Notes = DB::table('special_notes')->where('id',$id)->get();
        $note = $Notes[0];

        return view('note_save',compact('status','note'));

    }

    public function DeleteNote($id){
        $this->notes->delete(new special_note(),$id);
        return redirect("admin/notes")->with('status','Record Deleted Successfully!');

    }

}
