@extends('layouts.main')

@section('htmlheader_title')
    TutorsIQ
@endsection

@section('main-content')
    @include('layouts.main.partials.contents')
@endsection

@section('pagemodal')
    @include('layouts.main.partials.modal')
@endsection

@section('page_specific_styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('page_specific_scripts')

    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
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

    </script>

    @if (session('status'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('{{session('status')}}');
            });
        </script>
    @endif
@endsection
