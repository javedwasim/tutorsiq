<?php
namespace Acme\Config;
class Constants{

    public function BulkEmailTemplate(){
        return [
            'BULK_EMAIL_TEMPLATE'=>3,
            'MARK_REGULAR'=>1,
            'MARK_ASSIGNED'=>2,
            'MARK_APPROVED'=>9,
            'RESET_PASSWORD'=>5,
            'UNASSIGNED_TUITION'=>8,
        ];
    }


    public function TuitionFollowUp(){

       return  [
            'Assigned Pending Tuitions'=>6,
            'Evening Follow Up'=>7,
            'Report of Evening Follow Up'=>8,
            'Routine Morning Follow Up'=>9,
           ];

    }
}