@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Experiences
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Past Experiences') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tutorexperienceslist')

    @endsection








