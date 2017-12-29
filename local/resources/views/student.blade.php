@extends('layouts.app')

@section('htmlheader_title')
	@role('student')
		Student Portal
	@else
		Home Portal
	@endrole
@endsection
<?php
$user = Auth::user();
$roles = $user->roles()->pluck('name');
//dd($roles);
?>

@section('main-content')
	<div class="container spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Student Portal</div>

					<div class="panel-body">

						{{ trans('adminlte_lang::message.logged') }}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
