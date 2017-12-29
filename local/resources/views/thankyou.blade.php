@extends('layouts.main')

@section('htmlheader_title')
    TutorsIQ
@endsection

@section('main-content')
    @include('layouts.main.partials.thankyou')
@endsection

@section('page_specific_styles')

    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">



@endsection

@section('page_specific_scripts')

    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/jQuerymask/jquery.mask.min.js') }}"></script>

    <script src="{{ asset('js/jquery.validate-1.14.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validate.bootstrap-tooltip.js') }}"></script>



@endsection
@section('page_specific_inline_scripts')

    <script>

        $(document).ready(function() {

            $("#signupform").validate({
                rules: {
                    email : {email:true, required: true},
                    fullname: {required: true},
                    password: {required: true},
                    confirm_password: {required: true},
                    father_name: {required: true},
                    gender_id: {required: true},
                    suitable_timings: {required: true},
                    livingin: {required: true},
                    age: {required: true},
                    experience: {required: true},
                    marital_status_id: {required: true},
                    reference_gurantor: {required: true},
                    expected_minimum_fee: {required: true},
                    religion: {required: true},

                },
                messages: {
                    fullname: "Please enter full name",
                    email: "Please enter email",
                    password: "Please enter password",
                    confirm_password: "Please confirm password",
                    father_name: "Please enter father's/husband name",
                    gender_id: "Please Select Gender",
                    suitable_timings: "Please Select Stuitable Timings",
                    livingin: "Please Select Living in",
                    age: "Please Select Age",
                    experience: "Please Select Experience",
                    marital_status_id: "Please Select Marital Status",
                    reference_gurantor: "Please Enter Reference of guarantor",
                    expected_minimum_fee: "Please Select Fee Package",
                    religion: "Please Enter Religion",
                },
                tooltip_options: {
                    fullname: {trigger:'focus'},
                    email: {trigger:'focus'},
                    password: {trigger:'focus'},
                    confirm_password: {trigger:'focus'},
                    father_name: {trigger:'focus'},
                    gender_id: {trigger:'focus'},
                    suitable_timings: {trigger:'focus'},
                    livingin: {trigger:'focus'},
                    age: {trigger:'focus'},
                    experience: {trigger:'focus'},
                    marital_status_id: {trigger:'focus'},
                    reference_gurantor: {trigger:'focus'},
                    expected_minimum_fee: {trigger:'focus'},
                    religion: {trigger:'focus'},

                },
            });

        });



        $(document).ready(function () {

            $('select').select2({
                minimumResultsForSearch: -1
            });

            //iCheck for checkbox and radio inputs
            //initialize icheck box
//            $(function () {
//
//                $('input.minimal').iCheck({
//                    checkboxClass: 'icheckbox_square-red',
//                    radioClass: 'iradio_square-red',
//                    increaseArea: '20%' // optional
//                });
//            });

            //continue button clicked
            $('input').on('ifClicked', function (event) {
                var value = $(this).val();
                if (value == 'continue') {
                    $('#higher_degree').attr('required', 'required');
                    $('#continue').show();
                } else {
                    $('#higher_degree').removeAttr('required');
                    $('#continue').hide();
                }

            });



            //Date picker
            $('#datepicker').datepicker({
                autoclose: true,
            });



            //load register view
            $('.register-student').click(function () {

                $.ajax({

                    url: 'register/student',
                    type: "GET",
                    data: {'teacherid': ''},
                    beforeSend: function () {
                        $("#wait").modal();
                    },
                    success: function (data) {

                        $('#wait').modal('hide');

                        $(".register-view").empty();
                        $('.register-view').html(data);
                        $('#register-view').modal();


                    },
                    cache: false,
                    contentType: false,
                    processData: false

                });

            });



            //save
            $( '#stage6form' ).on( 'submit', function(e) {

                e.preventDefault();
                var formData = new FormData($(this)[0]);

                $.ajax({

                    url:'{{url("tutorsignup/finish")}}',
                    type: "POST",
                    data: formData,
                    async: false,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (response) {

                        $('#wait').modal('hide');
                        toastr.success('Thanks for registeration. Please check your mail to activate your account');

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });

        });

    </script>

    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif
@endsection
