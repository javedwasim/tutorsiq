@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Locations
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Locations') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('layouts.partials.locations')

    @endsection








