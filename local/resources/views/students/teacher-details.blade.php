@extends('layouts.main')

@section('htmlheader_title')
    TutorsIQ
@endsection

@section('main-content')
    @include('students.partials.teacher-details')
@endsection

@section('pagemodal')
    @include('layouts.main.partials.modal')
@endsection
