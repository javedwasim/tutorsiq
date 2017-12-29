@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Location Preferences
    @endrole
    @role('admin')
    Admin | Bands
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Prefered Locations') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

    @include('teachers.locationpreferencelist')

    @endsection








