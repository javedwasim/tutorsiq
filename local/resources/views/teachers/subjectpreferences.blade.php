@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Subject Preferences
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Subject Preferences') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.subjectpreferencelist')

    @endsection








