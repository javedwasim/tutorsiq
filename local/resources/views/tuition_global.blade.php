@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Teacher Portal
    @endrole
    @role('admin')
    Admin | Tuition BroadCast
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuition BroadCast') }}
    @endsection

    @section('contentheader_description')

    @endsection


    @section('main-content')

            <!-- Parent Grid View -->
    @include('layouts.partials.tuitions_global')


    @endsection








