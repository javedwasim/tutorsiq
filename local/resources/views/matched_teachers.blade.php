@extends('layouts.matched_teachers')

    @section('contentheader_description')

    @endsection


    @section('main-content')
            <!-- Tutor Search Filters -->
    @include('layouts.partials.matched_teacher_filters')
            <!-- Parent Grid View -->
    @include('layouts.partials.matched_teachers')

@endsection