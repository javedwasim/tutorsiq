@section('page_specific_styles')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection
@section('page_specific_scripts')
            <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
@endsection

<!-- SELECT2 EXAMPLE -->
<div class="box box-primary collapsed-box">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse"><i class="fa fa-plus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>

            <h1 class="box-title">Search Filters</h1>
        </div>
    </a>

    <!-- /.box-header -->
    <div class="box-body">
        <!-- form start -->
        <form class="" method="post" action="{{ url('admin/broadcast/tuittions/sms') }}" id="filterform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <!-- /.row -->
            <div class="row" id="first-row">

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['subjectPreference_p']) && $filters['subjectPreference_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="subjectPreference" id="subjectPreference">
                                <input type="hidden" value="<?php if(isset($filters['subjectPreference_p']) && $filters['subjectPreference_p'] == 0 ) echo "0"; else echo "1"; ?>" name="subjectPreference_p" id="subjectPreference_p" />
                            </label>
                            <label style="margin-left: 10px;">Subject</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['location_p']) && $filters['location_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="location" id="location">
                                <input type="hidden" value="<?php if(isset($filters['location_p']) && $filters['location_p']== 0 ) echo "0"; else echo "1"; ?> " name="location_p" id="location_p" />
                            </label>
                            <label style="margin-left: 10px;">Location</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['institution_p']) && $filters['institution_p']== 0 ) echo "unchecked"; else echo "checked"; ?> name="institution" id="institution">
                                <input type="hidden" value="<?php if(isset($filters['teacher_band_id_p']) && $filters['teacher_band_id_p'] == 0 ) echo "0"; else echo "1"; ?>" name="institution_p" id="institution_p" />
                            </label>
                            <label style="margin-left: 10px;">Institute</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['special_requirement_p']) && $filters['special_requirement_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="special_requirement" id="special_requirement" />
                                <input type="hidden" value="<?php if(isset($filters['special_requirement_p']) && $filters['special_requirement_p'] == 0 ) echo "0"; else echo "1"; ?>" name="special_requirement_p" id="special_requirement_p" />
                            </label>
                            <label style="margin-left: 10px;">Special Requirements</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->


            </div>
            <!-- /.row -->

            <!-- /.row -->
            <div class="row" id="second-row">



                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['fee_p']) && $filters['fee_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="fee" id="fee" />
                                <input type="hidden" value="<?php if(isset($filters['fee_p']) && $filters['fee_p'] == 0 ) echo "0"; else echo "1"; ?>" name="fee_p" id="fee_p" />
                            </label>
                            <label style="margin-left: 10px;">Fee Range</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['suitable_timings_p']) && $filters['suitable_timings_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="suitable_timings" id="suitable_timings" />
                                <input type="hidden" value="<?php if(isset($filters['suitable_timings_p']) && $filters['suitable_timings_p'] == 0 ) echo "0"; else echo "1"; ?>" name="suitable_timings_p" id="suitable_timings_p" />
                            </label>
                            <label style="margin-left: 10px;">Suitable Timing</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="minimal checkbox" value="1"
                                       <?php if(isset($filters['teaching_duration_p']) && $filters['teaching_duration_p'] == 0 ) echo "unchecked"; else echo "checked"; ?> name="teaching_duration" id="teaching_duration" />
                                <input type="hidden" value="<?php if(isset($filters['teaching_duration_p']) && $filters['teaching_duration_p'] == 0 ) echo "0"; else echo "1"; ?>" name="teaching_duration_p" id="teaching_duration_p" />
                            </label>
                            <label style="margin-left: 10px;">Time Duration</label>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->


            <div class="row" id="third-row">
            </div>
            <!-- /.row -->
            <!-- /.box-body -->
            <div class="box-footer">

                <button type="submit" id="submit_pagesize" class="btn btn-success pull-right" style="margin-right: 5px;">
                    <i class="fa fa-fw fa-reply"></i> Apply Filter
                </button>

            </div>
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>



            <!-- /.box -->
@section('page_specific_inline_scripts')
    <script type="text/javascript">
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": true,
            //"toastClass": "animated fadeInDown",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "0",
            "timeOut": "0",
            "extendedTimeOut": "1000",
            "showEasing": "linear",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <script>
        //load mathced teachers
        jQuery(document).ready(function ($) {

            // For oncheck callback gender
            $('#gender').on('ifChecked', function () { $('#gender_p').val(1); })
                // For onUncheck callback gender
            $('#gender').on('ifUnchecked', function () {  $('#gender_p').val(0); })

            // For oncheck callback subjectPreference
            $('#subjectPreference').on('ifChecked', function () { $('#subjectPreference_p').val(1); })
            // For onUncheck callback subjectPreference
            $('#subjectPreference').on('ifUnchecked', function () {  $('#subjectPreference_p').val(0); })

            // For oncheck callback location
            $('#location').on('ifChecked', function () { $('#location_p').val(1); })
            // For onUncheck callback location
            $('#location').on('ifUnchecked', function () {  $('#location_p').val(0); })


            // For oncheck callback experience
            $('#experience').on('ifChecked', function () { $('#experience_p').val(1); })
            // For onUncheck callback experience
            $('#experience').on('ifUnchecked', function () {  $('#experience_p').val(0); })

            // For oncheck callback fee
            $('#fee').on('ifChecked', function () { $('#fee_p').val(1); })
            // For onUncheck callback fee
            $('#fee').on('ifUnchecked', function () {  $('#fee_p').val(0); })

            // For oncheck callback suitable_timings
            $('#suitable_timings').on('ifChecked', function () { $('#suitable_timings_p').val(1); })
            // For onUncheck callback suitable_timings
            $('#suitable_timings').on('ifUnchecked', function () {  $('#suitable_timings_p').val(0); })

            // For oncheck callback age
            $('#teaching_duration').on('ifChecked', function () { $('#teaching_duration_p').val(1); })
            // For onUncheck callback age
            $('#teaching_duration').on('ifUnchecked', function () {  $('#teaching_duration_p').val(0); })

            // For oncheck callback special_requirement
            $('#special_requirement').on('ifChecked', function () { $('#special_requirement_p').val(1); })
            // For onUncheck callback special_requirement
            $('#special_requirement').on('ifUnchecked', function () {  $('#special_requirement_p').val(0); })

            // For oncheck callback tuition_label
            $('#tuition_label').on('ifChecked', function () { $('#tuition_label_p').val(1); })
            // For onUncheck callback tuition_label
            $('#tuition_label').on('ifUnchecked', function () {  $('#tuition_label_p').val(0); })

            // For oncheck callback institution
            $('#institution').on('ifChecked', function () { $('#institution_p').val(1); })
            // For onUncheck callback institution
            $('#institution').on('ifUnchecked', function () {  $('#institution_p').val(0); })




            $("body").addClass('skin-blue sidebar-mini sidebar-collapse');
           //set datatable attributes
            var table = $('#example1').DataTable({
                "paging": false,
                "ordering": true,
                "info": false,
                'searching': false,
                "order": [[ 2, "asc" ]]

            });


            $("#page_size").change(function () {

                var page_size = $("#page_size").val();
                $('#pagesize').val(page_size);

                $('#submit_pagesize').trigger('click');

            });

        });
    </script>

    <script>

        $(function () {

            $(".select2").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        <!-- Copy Text -->
        $("#copyButton").click(function () {

            $('#smsText').val();
            copyToClipboard(document.getElementById("smsText"));
            toastr.success('Text Copied  Successfully!');

        });

        function copyToClipboard(elem) {
            // create hidden text element, if it doesn't already exist
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // can just use the original source element for the selection and copy
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // must use a temporary form element for the selection and copy
                target = document.getElementById(targetId);
                if (!target) {
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // select the content
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");

            } catch (e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }

            if (isInput) {
                // restore prior selection
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // clear temporary content
                target.textContent = "";
            }
            return succeed;
        }

        <!-- Copy Text -->


    </script>


@endsection