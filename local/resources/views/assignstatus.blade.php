@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Tuition Assignment Status
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuition Assignment Status') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.assignments')

    @endsection








