@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Application | Status
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Application Status') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.applications')

    @endsection








