@extends('layouts.main')

@section('htmlheader_title')
    TutorsIQ
@endsection

@section('main-content')
    @include('layouts.main.partials.classified')
@endsection

@section('page_specific_styles')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link href="{{ asset('/css/skins/_all-skins.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Morris charts -->
    <link href="{{ asset('plugins/morris/morris.css') }}" rel="stylesheet" type="text/css"/>


@endsection

@section('page_specific_scripts')

    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.pie.min.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.categories.min.js') }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>

    <script src="{{ asset('js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('js/utills.js') }}"></script>


@endsection

