@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Templates
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Email Templates') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.templates')

    @endsection








