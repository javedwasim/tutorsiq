<!-- SELECT2 EXAMPLE -->
<div class="box box-primary">
    <div class="box-header with-border"><i class="pull-right" style="font-size:12px;
        margin-top: 5px;"></i>
        <h1 class="box-title">Choose Class & Subjects</h1>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- form start -->

        <form class="" method="post" action="{{ url('/admin/class/subject/mappings') }}" id="mapping">
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row" id="first-row">
                <!-- /.col -->
                <div class="col-md-12">

                    <div class="form-group">
                        <label>Classes</label>
                        <select class="form-control target" name="classes" id="classes"  required>
                            <option value="">Please Select</option>
                            <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class->id; ?>"
                                    <?php if(isset($class_id)&&$class_id==$class->id) echo "selected"; ?> ><?php echo $class->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

            </div>

            <div class="row" id="sencond-row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Subjects</label>
                        <select class="form-control" name="subjects" id="subjects" required>
                            <option value="">Please Select</option>
                            <?php foreach($subjects as $subject): ?>
                            <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right send-btn"><i class="fa fa-fw fa-save"></i> Save</button>
            </div>
            <!-- /.box-footer -->
        </form>
        <!-- end form -->
    </div>
</div>
<!-- /.box -->

<div class="box box-primary">
    <div class="box-header with-border"><i class="pull-right" style="font-size:12px;
        margin-top: 5px;"></i>
        <h1 class="box-title">Associated Subjects</h1>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <table class="table table-hover" id="mapping">
            <tbody>
            <tr style="background-color: #3c8dbc; color: #fefefe;">
                <th>Class</th>
                <th>Subject</th>
                <th>&nbsp;</th>
            </tr>
            <?php if(isset($mappings) && !empty($mappings) ): ?>

                <?php  foreach($mappings as $mapping): ?>
                    <tr class="child">
                        <td><?php echo $mapping->c_name; ?></td>
                        <td><?php echo $mapping->name; ?></td>
                        <td style="text-align: right;">
                            <a class="btn  del-btn" onclick="return ConfirmDelete();" href="mapping/delete/<?php echo $mapping->id; ?>" title="Delete" style="padding: 0 0;">
                                <span class="label label-danger"><i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else : ?>
            <tr> <td class="child" colspan="3" style="text-align: center;"><strong>No Record</strong></td></tr>
            <?php endif; ?>


            </tbody>
        </table>
    </div>

</div>
@include('layouts.partials.modal')
@section('page_specific_inline_scripts')
    @if (session('class_id'))
        <script>
            jQuery(document).ready(function ($) {
                toastr.success('Mappings Deleted Successfully!');
            });
        </script>
    @endif
    <script>

        $( ".target" ).change(function() {
            $(".child").remove();
            $(".add").remove();
            //alert( $(this).val() );
            var classid = $(this).val();

            $.ajax({
                url:'{{url("admin/gradesubject/mapping")}}',
                type: "POST",
                data: {'classid':classid, '_token': $('input[name=_token]').val()},

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
                            //console.log(data['subjectname'][j]['id']);
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

                        toastr.success('Mappings Updated Successfully!');

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