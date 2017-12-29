@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Tuition History
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuition History') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.tuitionhistorylist')

    @endsection








