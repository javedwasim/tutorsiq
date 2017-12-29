<?php

namespace Acme\Constants;

class Constants{
    public function BulkEmailTemplate(){
        return [
            'BULK_EMAIL_TEMPLATE'=>3,
            'MARK_REGULAR'=>1,
            'MARK_ASSIGNED'=>2,
            'MARK_APPROVED'=>9,
            'RESET_PASSWORD'=>5,
        ];
    }
}