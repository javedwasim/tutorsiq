@extends('layouts.main')

@section('htmlheader_title')
    TutorsIQ
@endsection

@section('main-content')
    @include('students.partials.search_teachers')
@endsection

@section('pagemodal')
    @include('layouts.main.partials.modal')
@endsection

@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection
@section('page_specific_scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
@endsection
@section('page_specific_inline_scripts')

    <script>
        $(document).ready(function () {

            $(".select2").select2();

            $(".owl-carousel").owlCarousel({
                items: 1,
                nav: true,
                loop: true,
                autoPlay: 3000,
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
        });

        //load  zone locations
        $("#zone").change(function () {

            var zone_id = $("#zone").val();

            $.ajax({

                url: 'search-teacher/zones/'+zone_id,
                type: "GET",
                data: {'zone_id':zone_id},
                async: false,

                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {

                    var options = data['options']

                    $('#locations')
                        .find('option')
                        .remove()
                        .end()
                        .append(options);

                    $('#wait').modal('hide');


                },
                cache: false,
                contentType: false,
                processData: false

            });

        });
        //load subjects for grade
        $("#class").change(function () {

            var class_id = $("#class").val();
            $.ajax({

                url: 'search-teacher/subjects/'+class_id,
                type: "GET",
                data: {'class_id':class_id},
                async: false,

                beforeSend: function () {
                    $("#wait").modal();
                },
                success: function (data) {

                    var options = data['options']

                    $('#subjects')
                        .find('option')
                        .remove()
                        .end()
                        .append(options);

                    $('#wait').modal('hide');


                },
                cache: false,
                contentType: false,
                processData: false

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
