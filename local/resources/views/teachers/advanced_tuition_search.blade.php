@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Tuition
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuitions') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')
            <!-- Tutor Search Filters -->
    @include('teachers.tuitionsearchfilter')
            <!-- Parent Grid View teachers.tuitionsearched -->
    @include('teachers.tuitionsearched')

@endsection