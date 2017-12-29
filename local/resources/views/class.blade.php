@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Classes
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Classes') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.classes')

    @endsection








