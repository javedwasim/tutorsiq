<div class="teacher-search">
    <div class="row">
        <div class="col-lg-9">
            <div class="box">
                <table id="applied_tuitions" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>subjects</th>
                        <th>Tutor</th>
                        <th>Applied Date</th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>
        @include('students.partials.right_sidebar')
    </div>
</div>
@section('page_specific_styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.css"/>
@endsection

@section('page_specific_scripts')

    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>


@endsection


@section('page_specific_inline_scripts')
    <script>
        $(document).ready(function () {


            $('#applied_tuitions').DataTable({
                "ajax": {
                    "url": "student/tuitions",
                    "dataSrc": ""
                },
                "columns": [
                    {"data": "subjects"},
                    {"data": "TutorReg"},
                    {"data": "tuition_date"},
                ],
                "paging": false,
                "info": false,
                'searching': true,
                "order": [[ 2, "ASC" ]],
            });
        });

    </script>

@endsection