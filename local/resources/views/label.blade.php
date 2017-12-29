@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Labels
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Labels') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.labels')

    @endsection








