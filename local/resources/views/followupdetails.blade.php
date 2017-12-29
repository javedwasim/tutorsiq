@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')
    Follow Up Tuitions
    @endrole
    @role('admin')
    Admin | Tuitions Follow Up
    @endrole
    @endsection

    @section('contentheader_title')
    {{ trans('Tuitions Follow Up') }}
    @endsection

    @section('contentheader_description')

    @endsection

    @section('main-content')

        @include('layouts.partials.searchfiltersfollowuptuitions')
        @include('layouts.partials.followuptuitions')

    @endsection