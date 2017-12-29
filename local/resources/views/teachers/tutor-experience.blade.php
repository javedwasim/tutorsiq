@extends('layouts.app')

@section('htmlheader_title')
    @role('teacher')

    @endrole

    <?php if($status != 'add'): ?>
    {{ trans('Teacher | Experience | Update') }}
    <?php else: ?>
    {{ trans('Teacher | Experience | Add') }}
    <?php endif; ?>
@endsection

@section('contentheader_title')
    <?php if($status != 'add'): ?>
    {{ trans('Update Expeience') }}
    <?php else: ?>
    {{ trans('Add New Expeience') }}
    <?php endif; ?>
@endsection

@section('main-content')

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    {!! Form::open(array('url'=>'teacher/experiences','method'=>'POST', 'id'=>'experience',
                        'enctype'=>'multipart/form-data','class'=>'form-horizontal')) !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="teacherid" id="teacherid" value="<?php echo isset($experiences) ? $experiences->teacher_id:$teacherid; ?>">
                        <input type="hidden" name="id" id="id" value="<?php echo  isset($experiences->id) ?  $experiences->id:'';?>">
                        <input type="hidden" name="status" id="status" value="<?php echo isset($status) ? $status:''; ?>">
                        <input type="hidden" name="document" value="<?php echo  isset($experiences->experience_document) ?  $experiences->experience_document:'';?>">
                        <input type="hidden" name="submitbtnValue" id="submitbtnValue" value="">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#qualification"
                                           aria-expanded="true" aria-controls="qualification">
                                            Experience Detail
                                        </a>
                                    </h4>
                                </div>
                                <div id="qualification" class="panel-collapse collapse in" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="reference_for_rent" class="col-sm-12 control-label" style="text-align: left">Teaching Experience in Full Details
                                                    (Home Tuition or/and intitution)<span style="color: red">*</span></label>

                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" rows="10" name="expeience" required
                                                                  maxlength="1000"><?php echo isset($experiences->experience) ? $experiences->experience : ''; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="degree_document" class="col-sm-2 control-label">Experience Document</label>

                                            <div class="col-sm-4">
                                                <input type="file" id="experience_document" name="experience_document">

                                            </div>
                                            <?php if(!empty($experiences->experience_document)) :?>
                                                <label for="degree_document" class="col-sm-6 control-label" style="text-align: left;">
                                                    <a href="#" class="btn btn-primary btn-experience-docs" >
                                                        <i class="fa fa-fw fa-cloud-download"></i>View Document
                                                    </a>
                                                </label>
                                           <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /personal information -->
                        <div class="box-footer">
                            <a href="{{url('experiences')}}" class="btn btn-warning pull-right">
                                <i class="fa fa-w fa-remove"></i> Cancel
                            </a>
                            <?php if($status != 'add'): ?>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save"></i> Update</button>
                            <?php else: ?>
                            <button type="submit" class="btn btn-primary pull-right" value="saveadd" name="saveadd" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save & Add</button>
                            <button type="submit" class="btn btn-primary pull-right" value="save" name="save" style="margin-right:5px;"><i class="fa fa-fw fa-save" ></i> Save</button>
                            <?php endif; ?>

                        </div>
                        <!-- /.box-footer -->
                    {!! Form::close() !!}
                </div>
                <!-- /.box -->
            </div>
        </div>

    </div>
    <?php if(isset($experiences)): ?>
    <form class="pull-right form-group" method="post" action="{{ url('download') }}"
          id="exp-docs" style="display: none;">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="path" id="path"
               value="<?php echo base_path()."/teachers/".$experiences->teacher_id."/experience/$experiences->experience_document"; ?>" >
        <input type="hidden" name="filename" id="filename" value="<?php echo $experiences->experience_document; ?>">

        <button type="submit" class="btn btn-link" id="experience-btn">
            <?php echo $experiences->experience_document; ?></button>

    </form>
    <?php endif; ?>
   @include('layouts.partials.modal')
    @endsection

    @section('page_specific_scripts')

            <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('page_specific_inline_scripts')
    <script>

        jQuery(document).ready(function() {

            $(".alert").fadeOut(6000);

            $('.btn-experience-docs').on('click', function () {
                //jQuery('.experience-image').toggle('show');
                $("#exp-docs").submit();
            });
        });

        jQuery( document ).ready( function( $ ) {

            $(document).on("click", ":submit", function(e){
                $("#submitbtnValue").val($(this).val());
            });

            $( '#experience' ).on( 'submit', function(e) {
                e.preventDefault();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url:'{{url("teacher/experiences")}}',
                    type: "POST",
                    data: formData,
                    async: true,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (data) {
                        //alert(data['teacherid']);
                        $('#wait').modal('hide');
                        var teacherid = data['teacherid'];
                        var success = data['success'];
                        if(success=='saveandadd'){

                            var redirect_url = '{{url("teacher/experiences")}}';

                            $( ".modal-footer" ).append( $( '<a class="btn btn-outline" ' +
                                    'href="'+redirect_url+'">OK</a>' ) );
                            toastr.success('Expereince Save Successfully!');

                        }else{
                            var redirect_url = '{{url("emessage")}}'; //experiences
                            window.location.replace(redirect_url);
                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });

        $('#datepicker1').datepicker({
            autoclose: true,
        });
    </script>
@endsection
