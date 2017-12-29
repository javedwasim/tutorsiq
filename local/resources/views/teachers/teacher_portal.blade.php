@extends('layouts.app')

@section('htmlheader_title')
	@role('teacher')
	Teacher Profile
	@endrole
	@role('admin')
	Admin | Teacher
	@endrole
	@endsection

	@section('contentheader_title')
	{{ trans('Profile') }}
	@endsection

	@section('contentheader_description')

	@endsection

	@section('main-content')

		@include('teachers.teacherprofile')

	@endsection
