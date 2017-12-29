@extends('layouts.app')

@section('page_specific_scripts')
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea'});</script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('htmlheader_title')


@endsection

@section('contentheader_title')

@endsection

@section('main-content')

    <div class="col-md-9">

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Email Template</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

            <form class="form-horizontal" id="templater" method="post" action="{{ url('admin/teacher/bulk/email') }}"
                  enctype="multipart/form-data" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="email_title" id="email_title" value="">

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

                <div class="box-body" style="display: block;">

                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <select class="form-control target" name="title" id="title" required>
                                <option value="">Please Select</option>
                                <?php foreach($templates as $template): ?>
                                <option value="<?php echo $template->id ?>"><?php echo $template->title ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">Subject<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="subject"  value=""
                                   name="subject" placeholder="Enter Subject" maxlength="100"
                                   required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="body" class="col-sm-2 control-label">Body<span
                                    style="color: red">*</span></label>

                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" name="body"  placeholder="Enter ..." required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="body" class="col-sm-2 control-label"><span style="color: red">&nbsp;</span></label>

                        <div class="col-sm-10">
                            <input type="radio" name="email_type" id="email_type" value="all"
                            <?php echo isset($email_type) && ($email_type == 'all') ? 'checked' : ''; ?> >&nbsp;All
                            &nbsp;

                            <input type="radio" name="email_type" id="email_type" value="bulk"
                            <?php echo isset($email_type) && ($email_type == 'bulk') ? 'checked' : ''; ?> >&nbsp;Bulk

                            <input type="radio" name="email_type" id="email_type" value="active"
                            <?php echo isset($email_type) && ($email_type=='active') ? 'checked' : ''; ?> >&nbsp;Active
                            &nbsp;
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-warning pull-right" value="save"
                                name="save" style="margin-right:5px;"><i
                                    class="fa fa-fw fa-envelope"></i> Send
                        </button>
                    </div>


                </div>
                <!-- /.box-body -->
            </form>
        </div>
        <!-- /.box -->

    </div>

    <div class="col-md-3">

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Place Holders</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <ul class="nav nav-stacked">
                    <li><a href="#">Full Name <span class="pull-right badge bg-blue">#fullname#</span></a></li>
                    <li><a href="#">Email <span class="pull-right badge bg-aqua">#email#</span></a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>


    </div>

@endsection

@include('layouts.partials.modal')

@section('page_specific_inline_scripts')
    <script>

        $( ".target" ).change(function() {

            $(".child").remove();
            $(".add").remove();
            //alert( $(this).val() );
            var template_id = $(this).val();

            $.ajax({

                url:'{{url("admin/global/email/load")}}',
                type: "POST",
                data: {'template_id':template_id, '_token': $('input[name=_token]').val()},

                beforeSend: function(){
                    $("#wait").modal();
                },
                success: function (response) {

                    $('#wait').modal('hide');

                    var test = JSON.stringify(response);
                    var data = JSON.parse(test);
                    //console.log(data);

                    $('#subject').val(data['subject']);
                    $('#email_title').val(data['title']);
                    tinyMCE.activeEditor.setContent(data['body']);

                }
            });

        });

        jQuery( document ).ready( function( $ ) {

            $( '#mapping' ).on( 'submit', function(e) {
                e.preventDefault();

                //remove previously populated data
                $(".child").remove();

                var formData = new FormData($(this)[0]);
                $.ajax({
                    url:'{{url("admin/class/subject/mappings")}}',
                    type: "POST",
                    data: formData,
                    async: true,
                    beforeSend: function(){
                        $("#wait").modal();
                    },
                    success: function (response) {
                        $('#wait').modal('hide');

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);
                        var classname = data['classname'];
                        if(data['subjectname'].length>0) {
                            for (var j = 0; j < data['subjectname'].length; j++) {

                                // console.log(data['subjectname'][j]['id']);
                                var mappingid = data['subjectname'][j]['id'];
                                var subjectname = data['subjectname'][j]['name'];

                                $('#mapping tbody').append('<tr class="child">' +
                                        '<td>' + classname + '</td><td>' + subjectname + '</td><td style="text-align: right;;">' +
                                            //eidt button
                                        '<a class="btn  del-btn" onclick="return ConfirmDelete();"  href="mapping/delete/' + mappingid + '" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                                        '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' +
                                        '</td></tr>');

                            }
                        }else{

                            $('#mapping tbody').append('<tr> <td class="child" colspan="3" style="text-align: center;"><strong>No Record</strong></td></tr>');

                        }

                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });


            });
        });

        function ConfirmDelete(){
            return confirm("Are you sure to delete this item!");
        }
    </script>

@endsection
