<?php

namespace Acme\Mailers;

use App\email_template;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Request;
use App\Http\Requests;
use Validator;

class TemplateEvent{

    public function load($records, $links, $filters)
    {
        $last_filters = session(['last_filters' => $filters]);
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //Create a new Laravel collection from the array data
        $collection = new Collection($records);
        //Define how many items we want to be visible in each page
        if (isset($filters['pagesize']) && $filters['pagesize'] > 0) {

            $perPage = $filters['pagesize'];
        } else {
            $perPage = 50;
        }

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //Create our paginator and pass it to the view
        $records = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);
        //set pagination path
        $records->setPath($links);
        //Total records
        $count_records = count($collection);
        //count of records on current page.
        if ($currentPage == 1) {

            $perpage_record = count($currentPageSearchResults);
            $offset = $currentPage;

        } else {

            $offset = ($perPage * $currentPage) - ($perPage - 1);
            $perpage_record = $offset + (count($currentPageSearchResults) - 1);
        }

        return $data = array(
            'count' => $count_records,
            'perpage_record' => $perpage_record,
            'offset' => $offset,
            'records' => $records,
            'pagesize' => $perPage
        );


    }


    public function save($formdata)
    {
        $status = $formdata['status'];

        if ($status == 'add') {

            email_template::create($formdata);

        } else {

            //dd($formdata['body']);
            $id = $formdata['id'];
            $obj = email_template::find($id);
            $obj->title = $formdata['title'];
            $obj->subject = $formdata['subject'];
            $obj->body = $formdata['body'];
            $obj->is_active = $formdata['is_active'];
            $obj->updated_at = date('Y-m-d H:i:s', time());
            $obj->save();

        }

        if (!empty($formdata['submitbtnValue']) && $formdata['submitbtnValue'] == 'saveadd') {

            return 'saveandadd';

        } else {

            return 'save';

        }


    }


    public function delete($id)
    {

        $obj = email_template::find($id);

        try {
            $obj->delete();
        } catch (\Illuminate\Database\QueryException $e) {

            //return redirect($link)->with('warning', $e->errorInfo[2]);
        }
    }

}
