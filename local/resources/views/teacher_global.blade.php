@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Teachers BroadCast
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Teachers BroadCast') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

            <!-- Parent Grid View -->
    @include('layouts.partials.tutors_global')


    @endsection








