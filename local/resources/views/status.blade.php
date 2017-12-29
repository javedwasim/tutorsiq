@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Tuitio  Status
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuition Status') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.tuitionstatus')

    @endsection








