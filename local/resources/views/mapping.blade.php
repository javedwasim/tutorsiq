@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Mappings
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Mappings') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.mappings')

    @endsection








