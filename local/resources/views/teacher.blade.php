@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Teachers
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Teachers') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')
            <!-- Tutor Search Filters -->
    @include('layouts.partials.tutorsearchfilter')
            <!-- Parent Grid View -->
    @include('layouts.partials.tutors')

            <!-- Child Grid View -->
    @include('layouts.partials.tutordetails')


    @endsection








