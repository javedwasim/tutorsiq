@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Institutes
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Institutes') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.institutes')

    @endsection








