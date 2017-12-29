<?php

namespace Acme\Mailers;
use Mail;
use App\email_template;
use App\Teacher;
use Acme\Config\Constants;

class Mailers{

    protected $BulkEmailTemplate;

    public function __construct(Constants $constants)
    {

        $this->BulkEmailTemplate = $constants->BulkEmailTemplate();


    }

    public function mail( $user, $subject, $view, $data=[] ){

        Mail::queue($view, $data, function ($message) use($user,$subject) {

            $message->from('home@tuition.com', 'Home Tuition');

            $message->bcc($user->email)->subject($subject);

        });
    }


    public function SendMail($teacher_id){

        $template = email_template::findOrFail($this->BulkEmailTemplate['BULK_EMAIL_TEMPLATE']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        $data = ['body'=>$message];
        $this->mail($teacher,$subject,$view,$data);

    }


    public function UnAssignedTuition($teacher_id){

        $template = email_template::findOrFail($this->BulkEmailTemplate['UNASSIGNED_TUITION']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);


        //data for email view
        $data = ['body'=>$message];

        $this->mail($teacher,$subject,$view,$data);

    }


    public function MarkAssigned($teacher_id){

        $template = email_template::findOrFail($this->BulkEmailTemplate['MARK_ASSIGNED']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        //data for email view
        $data = ['body'=>$message];

        $this->mail($teacher,$subject,$view,$data);

    }

    public function MarkApproved($teacher_id){

        $template = email_template::findOrFail($this->BulkEmailTemplate['MARK_APPROVED']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        $data = ['body'=>$message];
        $this->mail($teacher,$subject,$view,$data);

    }

    public function ResetPassword($teacher_id,$pwd){

        $template = email_template::findOrFail($this->BulkEmailTemplate['RESET_PASSWORD']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        $data = ['body'=>$message];
        $this->mail($teacher,$subject,$view,$data);
    }

    public function MarkRegular($teacher_id){

        $template = email_template::findOrFail($this->BulkEmailTemplate['MARK_REGULAR']);
        $subject = $template->subject; //email subject
        $body = $template->body;        //email body
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        $data = ['body'=>$message];
        $this->mail($teacher,$subject,$view,$data);

    }

    public function SendBulkEmail($teacher_id,$request){

       // dd($request);
        $title = $request['email_title'];
        $subject = $request['subject'];
        $body = $request['body'];
        $view = 'auth.emails.markregular';

        $teacher = Teacher::find($teacher_id); //techer object to pass email function

        //replace placeholders form body of message.
        $message = $this->replacePlaceHolders($teacher,$body);

        $data = ['body'=>$message];
        $this->mail($teacher,$subject,$view,$data);

    }

    public function EmailToAdmin($request){

        $subject = $request['subject'];
        if(isset($request['gradeSelected'])){

            $gradeSelected = $request['gradeSelected'];
            $body  = "Grade: ".$gradeSelected."<br>";
            $body .= $request['body'];

        }else{

            $body = $request['body'];

        }

        $view = 'auth.emails.markregular';
        $emailTo = 'admin@admin.com';
        $data = ['body'=>$body];

        Mail::queue($view, $data, function ($message) use($emailTo,$subject) {

            $message->from('home@tuition.com', 'Home Tuition');

            $message->to($emailTo)->subject($subject);

        });
    }


    public function EmailToStudent($data){

        $body = $data['body'];
        $emailTo = $data['emailTo'];
        $subject=$data['subject'];

        $view = 'auth.emails.markregular';
        $data = ['body'=>$body];


        Mail::queue($view, $data, function ($message) use($emailTo,$subject) {

            $message->from('home@tuition.com', 'Home Tuition');

            $message->to($emailTo)->subject($subject);

        });


    }



    public function replacePlaceHolders($teacher,$body){

        //replace placeholders form body of message.
        $message = str_replace("#fullname#", $teacher->fullname, $body);
        $message = str_replace("#Dateofbirth#", $teacher->dob, $message);
        $message = str_replace("#email#", $teacher->email, $message);

        return $message;
    }


}