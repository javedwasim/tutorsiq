@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Tuition | My Applications
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('My Applications') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tuitionappliedlist')

    @endsection








