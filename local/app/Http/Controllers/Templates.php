<?php

namespace App\Http\Controllers;

use App\Teacher;
use Request;
use App\email_template;
use Acme\Mailers\TemplateEvent;
use Acme\Mailers\Mailers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\teacher_global;

class Templates extends Controller
{
    protected $template;
    protected $mailers;
    protected $CurrentUri;

    public function __construct(TemplateEvent $template, Mailers $mailers)
    {
        $this->template = $template;
        $this->mailers = $mailers;
        $this->CurrentUri = Route::getFacadeRoot()->current()->uri();
    }

    public function LoadTemplates(){

        $filters = Request::all();

        $templates = email_template::all();

        if(isset($filters['page'])){

            $last_filters = session('last_filters');
            $response = $this->template->load($templates,'templates',$last_filters);


        }else{
            $response = $this->template->load($templates,'templates',$filters);
        }

        $templates = $response['records'];
        $offset = $response['offset'];
        $count = $response['count'];
        $perpage_record = $response['perpage_record'];
        $pagesize = $response['pagesize'];
        $current_route = $this->CurrentUri;

        return view('template',compact('templates','offset','count','perpage_record','pagesize','current_route'));



    }

    public function LoadEmailTemplate(Request $request){

        $request = Request::all();
        $id = $request['template_id'];
        $template = email_template::findOrFail($id);
        $title = $template->title;
        $subject = $template->subject;
        $body = $template->body;

        return response()->json(['success' => true, 'title' => $title,'subject'=>$subject,'body'=>$body ]);

    }

    public function TemplateView(){
        $status = 'add';
        return view('template_save',compact('status'));

    }


    public function TemplateSave(Request $request){

        //validate form values
        $this->validate(request(), [

            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'is_active' => 'required',

        ]);
        //get form data
        $Template = Request::all();


        $redirect =  $this->template->save($Template);
        //return response()->json(['success' =>$redirect]);
        return redirect('admin/templates')->with('status', 'Email Template Save Successfully!');

    }


    public function TemplateEditView($id){
        $status = 'update';
        $template = email_template::findOrFail($id);
        return view('template_save',compact('status','template'));

    }


    public function DeleteTemplate($id){

        $this->template->delete($id);
        return redirect("admin/templates")->with('status','Record Deleted Successfully!');

    }


    public function BroadCastPhoneNumber(Request $request){

        $request = Request::all();
        $str = $request['teacher_id'][0];
        $teacher_phone_numbers = array();

        if(!empty($str)){

            $teacher_id = explode(',', $str);
            foreach($teacher_id as $id){
                $obj = Teacher::find($id);
                $teacher_phone_numbers[$obj->fullname]= $obj->mobile1;

            }

        }

        return response()->json(['success' => true, 'phone_numbers'=>$teacher_phone_numbers ]);

    }

    public function SendEmailToAdmin(Request $request){

        //validate form values
        $this->validate(request(), [

            'subject' => 'required',
            'body' => 'required',

        ]);

        $request= Request::all();

        $this->mailers->EmailToAdmin($request);

        return response()->json(['success' => true]);

    }

    public function SendEmailToAdminAboutLocation(Request $request){

        //validate form values
        $this->validate(request(), [

            'subject' => 'required',
            'body' => 'required',

        ]);

        $request= Request::all();
        $this->mailers->EmailToAdmin($request);
        return response()->json(['success' => true]);

    }



    public function SendBulkEmail(Request $request){

        //validate form values
        $this->validate(request(), [

            'subject' => 'required',
            'body' => 'required',

        ]);

        $request= Request::all();


        if(isset($request['email_type']) ){

            $email_type = $request['email_type'];


            switch ($email_type) {

                case "bulk":

                    $global_teachers = DB::table('teacher_globals')->get();

                    foreach($global_teachers as $teacher){

                        $this->mailers->SendBulkEmail($teacher->teacher_id, $request);

                    }

                    break;
                case "all":

                    $teachers = Teacher::all();

                    foreach($teachers as $teacher){
                        $this->mailers->SendBulkEmail($teacher->id, $request);

                    }

                    break;
                case "approved":

                    $teachers = Teacher::where('is_approved','=','1')->get();

                    foreach($teachers as $teacher){
                        $this->mailers->SendBulkEmail($teacher->id, $request);

                    }
                    break;

                case "napproved":

                    $teachers = Teacher::where('is_approved','!=','1')->get();

                    foreach($teachers as $teacher){
                        $this->mailers->SendBulkEmail($teacher->id, $request);

                    }
                    break;

                default:
                    echo "Incorrect option";

            }




        }
        return redirect("admin/global/teachers")->with('status','Email sent to recipients successfully!');
    }

    public function EmptyGlobalList(){


        teacher_global::truncate();

        return redirect('admin/global/teachers')->with('status', 'List Deleted Successfully!');

    }

    public function BroadCastEmail(Request $request){

        $request = Request::all();
        $str = $request['teacher_id'][0];
        $teacher_emails = array();
        $template_id = 1;

        if(!empty($str)){

            $teacher_id = explode(',', $str);
            foreach($teacher_id as $id){

                $obj = Teacher::find($id);
                $teacher_emails[$obj->firstname.' '.$obj->lastname]= $obj->email;
                $this->mailers->SendMail($id, $template_id);

            }

        }
        return response()->json(['success' => true, 'emails'=>$teacher_emails ]);

    }



}
