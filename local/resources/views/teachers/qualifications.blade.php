@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Qualifications
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Qualifications') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tutorqualificationlist')

    @endsection








