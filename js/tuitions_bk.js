/**
 * Created by javed on 27/01/2017.
 */
function TeacherBM(teacher_id, tuition_id,tbm_id){

    $.ajax({

        url: 'teacher/unbookmark',
        type: "post",
        data: {
            'teacher_id': teacher_id,
            'tuition_id': tuition_id,
            'tbm_id': tbm_id,
            '_token': $('input[name=_token]').val()
        },
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },

        success: function (response) {

            $('#selectall').attr('checked', false); // Unchecks it
            $(".child").remove();
            $('#teacher_bookmark_list').DataTable().clear().draw();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            $("#wait").modal('hide');

            var teacher_bookmark = $("#teacher_bookmark_list").DataTable({

                "paging":   true,
                "ordering": true,
                "info":     true,
                'searching':false,
                "pagingType": "full_numbers",
                destroy: true,
                "bLengthChange": false,
                responsive: true,
                "order": [[ 2, "asc" ]]


            });

            var success = data['success'];

            //populate teacherbookmark tab
            if(data['teacher_bookmark'].length>0){

                //console.log(data['teacher_bookmark']);
                for (var i = 0; i < data['teacher_bookmark'].length; i++) {

                    var firstname = data['teacher_bookmark'][i]['firstname'];
                    var lastname = data['teacher_bookmark'][i]['lastname'];
                    var email = data['teacher_bookmark'][i]['email'];
                    var photo = data['teacher_bookmark'][i]['teacher_photo'];
                    var teacher_id = data['teacher_bookmark'][i]['teacher_id'];
                    var tuition_id = data['teacher_bookmark'][i]['tuition_id'];
                    var td_id = data['teacher_bookmark'][i]['td_id'];
                    var id = data['teacher_bookmark'][i]['id'];
                    var band_name = data['teacher_bookmark'][i]['band_name'];
                    var age = data['teacher_bookmark'][i]['agey'];
                    var experience = data['teacher_bookmark'][i]['experience'];
                    var label = data['teacher_bookmark'][i]['label_name'];
                    var mobile1 = data['teacher_bookmark'][i]['mobile1'];
                    var csrf_token = $('input[name=_token]').val();

                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;


                    var rowNode = teacher_bookmark

                        .row.add( [

                            '<div id="element1" class="element1"><input type="checkbox" name="teacher_broadcast_list" class="teacher_broadcast_list" value="'+teacher_id+'"></div>' +

                            '<div id="element2"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                            '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                            ' <input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                            '<input type="hidden" name="email" value="'+email+'">'+
                            '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                            '</form></div>' +
                            '<br /><span class="label badge bg-green-active mobile1">'+mobile1+'</span>',

                            '<div id="element3"><a href="#" class="showphoto" id='+src+' >' +
                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                            '<div id="element4"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                            '<i class="fa fa-fw fa-volume-up"></i></a></div>',


                            band_name,Math.round(age),experience,label,
                            '<a class="btn teacher-bookmark" id="' + td_id + '" onclick="AssignTuition(' + teacher_id + ',' + tuition_id + ');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Assign</span></a>',

                            '<a class="btn teacher-bookmark" id="' + td_id + '" onclick="TeacherBM(' + teacher_id + ',' + tuition_id + ','+id+');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-warning"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Unookmark</span></a>' ] )
                        .draw()
                        .node();

                }

            }

        }

    });

    $( ".showphoto" ).click(function() {

        $("#teacher_profile_image").attr("src",this.id);
        $("#teacher_profile_photo").modal();
    });

    $('.global-list').on('click', function () {

        var teacherid = this.id;

        $.ajax({

            url: 'global',
            type: "post",
            data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

            success: function (response) {

                var test = JSON.stringify(response);
                var data = JSON.parse(test);
                var success = data['success'];
                console.log(data['teacher']);
                $('#myModal').modal();

            }

        });

    });

}

function MarkRegular(tuition_status_regular_id, tuiion_history_id, tuition_id,teacher_id) {

    $(".child").remove();

    $.ajax({

        url: 'tuitions/mark/regular',
        type: "post",
        data: {
            'tuition_status_regular_id': tuition_status_regular_id,
            'tuiion_history_id': tuiion_history_id,
            'tuition_id': tuition_id,
            'teacher_id': teacher_id,
            '_token': $('input[name=_token]').val()
        },
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },

        success: function (response) {
            // alert(response);
            $("#wait").modal('hide');
            $('#tuition_details_list').DataTable().clear().draw();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            var success = data['success'];
            if (success) {

                var tuition_details = $("#tuition_details_list").DataTable({

                    "paging":   true,
                    "ordering": true,
                    "info":     true,
                    'searching':false,
                    "pagingType": "full_numbers",
                    destroy: true,
                    "bLengthChange": false,
                    responsive: true,


                });

                //populate tuition_details tab
                if(data['tuition_details'].length>0){

                    for (var i = 0; i < data['tuition_details'].length; i++) {

                        var firstname = data['tuition_details'][i]['firstname'];
                        var lastname = data['tuition_details'][i]['lastname'];
                        var email = data['tuition_details'][i]['email'];
                        var photo = data['tuition_details'][i]['teacher_photo'];
                        var class_name = data['tuition_details'][i]['class_name'];
                        var subject_name = data['tuition_details'][i]['subject_name'];
                        var teacher_id = data['tuition_details'][i]['teacher_id'];
                        var tuition_id = data['tuition_details'][i]['tuition_id'];
                        var td_id = data['tuition_details'][i]['id'];
                        var assign_date = data['tuition_details'][i]['assign_date'];
                        var tuition_status = data['tuition_details'][i]['tuition_status'];
                        var tuiion_history_id = data['tuition_details'][i]['tuiion_history_id'];
                        var mobile1 = data['tuition_details'][i]['mobile1'];
                        var tuition_status_regular_id = 2;
                        var csrf_token = $('input[name=_token]').val();
                        var src = '../local/teachers/'+teacher_id+'/photo/'+photo;

                        if (tuition_status == 'Trial') {

                            var rowNode = tuition_details
                                .row.add( [ class_name, subject_name,

                                    '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                    '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                    '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                    '<input type="hidden" name="email" value="'+email+'">'+
                                    '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                                    '</form></div>' +
                                    '<br /><span class="label badge bg-green-active mobile_number">'+mobile1+'</span>',

                                    '<div id="element1"><a href="#" class="showphoto" id='+src+' >' +
                                    '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                    '<div id="element2"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                                    '<i class="fa fa-fw fa-volume-up"></i></a></div>',

                                    assign_date,

                                    '<span class="badge bg-yellow">' + tuition_status + '</span>',
                                    '<a href="#" id="markregular" onclick="MarkRegular(' + tuition_status_regular_id + ',' + tuiion_history_id + ',' + tuition_id + ','+teacher_id+');">' +
                                    '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Mark Regular</span></a>' ] )
                                .draw()
                                .node();

                        } else {

                            var rowNode = tuition_details
                                .row.add( [ class_name, subject_name,

                                    '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                    '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                    '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                    '<input type="hidden" name="email" value="'+email+'">'+
                                    '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                                    '</form></div>' +
                                    '<br /><span class="label badge bg-green-active mobile_number">'+mobile1+'</span>',

                                    '<div id="element1"><a href="#" class="showphoto" id='+src+' >' +
                                    '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                    '<div id="element2"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                                    '<i class="fa fa-fw fa-volume-up"></i></a></div>',

                                    assign_date,

                                    '<span class="badge bg-green">' + tuition_status + '</span>',
                                    '&nbsp;' ] )
                                .draw()
                                .node();

                        }

                    }
                }

            }

        }

    });

    $('.global-list').on('click', function () {

        var teacherid = this.id;

        $.ajax({

            url: 'global',
            type: "post",
            data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

            success: function (response) {

                var test = JSON.stringify(response);
                var data = JSON.parse(test);
                var success = data['success'];
                console.log(data['teacher']);
                $('#myModal').modal();

            }

        });

    });

}

function AssignBookmarkTeacher(teacher_id, td_id, tuition_id) {

    $.ajax({

        url: 'tuitions/assign/bookmark/teacher',
        type: "post",
        data: {
            'teacher_id': teacher_id,
            'td_id': td_id,
            'tuition_id': tuition_id,
            '_token': $('input[name=_token]').val()
        },
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },

        success: function (response) {
            //alert(response);
            $("#wait").modal('hide');

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            var success = data['success'];
            if (success) {

                $('#teacher_assigned').modal();
            }


        }

    });
}

function AssignTuition(teacher_id, tuition_id) {
    $(".tuitions").remove();
    $(".checkbox").remove();
    //alert(tuition_id);
    $.ajax({

        url: 'tuitions/assign',
        type: "post",
        data: {'id': tuition_id, 'teacher_id': teacher_id, '_token': $('input[name=_token]').val()},
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },

        success: function (response) {

            $('#wait').modal('hide');


            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            if (data['tuitions'].length > 0) {


                for (var j = 0; j < data['tuitions'].length; j++) {

                    var firstname = data['tuitions'][j]['firstname'];
                    var lastname = data['tuitions'][j]['lastname'];
                    var city = data['tuitions'][j]['city'];
                    var mobile1 = data['tuitions'][j]['mobile1'];
                    var email = data['tuitions'][j]['email'];
                    var teacher_id = data['tuitions'][j]['teacher_id'];
                    var tuition_id = data['tuitions'][j]['tuition_id_p'];
                    var class_name = data['tuitions'][j]['class_name'];
                    var subject_name = data['tuitions'][j]['subject_name'];
                    var td_id = data['tuitions'][j]['td_id'];


                    $('#subjects').append('<div class="checkbox"><label><input type="hidden" name="teacher_id" id="teacher_id" value="' + teacher_id + '">' +
                        '<input type="checkbox" name="subjects[]" id="' + subject_name + '" value="' + td_id + '" checked>' + subject_name + '</label>' +
                        '<input type="hidden" name="tuitionid" id="tuitionid" value="' + tuition_id + '" /></div>');


                }

            } else {
                $('#subjects').append('<div class="checkbox"><label><input type="checkbox" name="subjects[]" value="" disabled>Not Found</label></div>');
            }
            //$('#tuition').modal('hide');
            $('#assign_teacher').modal('show');


        }
    });

}

//set date filters
$("#tuition_date").change(function () {



    var date_filter = $("#tuition_date").val();
    var rightNow = new Date();
    var resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");

    $('#start_date').attr("readonly", true);
    $('#end_date').attr("readonly", true);
    $('#start_date').datepicker({autoclose: false});
    $('#end_date').datepicker({autoclose: false});

    if(date_filter==0){

        start_date = resultDate;
        end_date = resultDate;
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);


    }
    else if(date_filter==7){

        end_date = resultDate;
        newDate  = rightNow.setDate(rightNow.getDate() - 7);
        rightNow = new Date(newDate);
        start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);

    }
    else if(date_filter==14){

        end_date = resultDate;
        newDate  = rightNow.setDate(rightNow.getDate() - 14);
        rightNow = new Date(newDate);
        start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);
    }
    else if(date_filter==30){
        end_date = resultDate;
        newDate  = rightNow.setDate(rightNow.getDate() - 30);
        rightNow = new Date(newDate);
        start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);


    }
    else if(date_filter==90){

        end_date = resultDate;
        newDate  = rightNow.setDate(rightNow.getDate() - 90);
        rightNow = new Date(newDate);
        start_date = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);

    }
    else{

        $('#start_date').attr("readonly", false);
        $('#end_date').attr("readonly", false);
        $('#start_date').datepicker({autoclose: true});
        $('#end_date').datepicker({autoclose: true});

    }

});
//load tuition details.
$('.tuition_detail').click(function () {

    var tuition_details = $("#tuition_details_list").DataTable({

        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,


    });

    var teacher_bookmark = $("#teacher_bookmark_list").DataTable({

        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "order": [[ 2, "asc" ]]


    });

    var label_list = $("#label_list").DataTable({

        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,


    });

    var teacher_applications = $("#teacher_applications_listing").DataTable({

        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "order": [[ 2, "desc" ]]


    });

    //clear previous data in datatables
    $('#tuition_details_list').DataTable().clear().draw();
    $('#teacher_bookmark_list').DataTable().clear().draw();
    $('#label_list').DataTable().clear().draw();
    $('#teacher_applications_listing').DataTable().clear().draw();

    //highlight selected row of teacher table
    $('#example1 tbody').on('click', 'tr', function () {

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $(this).addClass('selected');
            $("div").removeClass("collapsed-box");
            $(".teacher-detial").removeClass("fa-plus");
            $(".teacher-detial").addClass("fa-minus");
            $("#teacher-detial").css("display", "block");
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("div").removeClass("collapsed-box");
            $(".teacher-detial").removeClass("fa-minus");
            $(".teacher-detial").addClass("fa-plus");
            $("#teacher-detial").css("display", "block");
        }

    });

    var tuition_id = this.id;

    //alert(tuition_id);
    $.ajax({

        url: 'tuitions/detail',
        type: "post",
        data: {'tuition_id': tuition_id, '_token': $('input[name=_token]').val()},

        success: function (response) {

            $("#teacher-detial").css("display", "block");
            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            $(".child").remove();
            $('#selectall').attr('checked', false); // Unchecks it
            $('#teacher_id').val(''); //empty previous loaded teacher ids.
            $('#teacher_id2').val(''); //empty previous loaded teacher ids.

            //populate tuition_details tab
            if(data['tuition_details'].length>0){

                for (var i = 0; i < data['tuition_details'].length; i++) {

                    var firstname = data['tuition_details'][i]['firstname'];
                    var lastname = data['tuition_details'][i]['lastname'];
                    var email = data['tuition_details'][i]['email'];
                    var photo = data['tuition_details'][i]['teacher_photo'];
                    var class_name = data['tuition_details'][i]['class_name'];
                    var subject_name = data['tuition_details'][i]['subject_name'];
                    var teacher_id = data['tuition_details'][i]['teacher_id'];
                    var tuition_id = data['tuition_details'][i]['tuition_id'];
                    var td_id = data['tuition_details'][i]['id'];
                    var assign_date = data['tuition_details'][i]['assign_date'];
                    var tuition_status = data['tuition_details'][i]['tuition_status'];
                    var tuiion_history_id = data['tuition_details'][i]['tuiion_history_id'];
                    var mobile1 = data['tuition_details'][i]['mobile1'];
                    var tuition_status_regular_id = 2;
                    var csrf_token = $('input[name=_token]').val();
                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;

                    if (tuition_status == 'Trial') {

                        var rowNode = tuition_details
                            .row.add( [ class_name, subject_name,

                                '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                '<input type="hidden" name="email" value="'+email+'">'+
                                '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                                '</form></div>' +
                                '<br /><span class="label badge bg-green-active mobile_number">'+mobile1+'</span>',

                                '<div id="element3"><a href="#" class="showphoto" id='+src+' >' +
                                '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                '<div id="element4"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                                '<i class="fa fa-fw fa-volume-up"></i></a></div>',

                                assign_date,

                                '<span class="badge bg-yellow">' + tuition_status + '</span>',
                                '<a href="#" id="markregular" onclick="MarkRegular(' + tuition_status_regular_id + ',' + tuiion_history_id + ',' + tuition_id + ','+teacher_id+');">' +
                                '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Mark Regular</span></a>' ] )
                            .draw()
                            .node();

                    } else {

                        var rowNode = tuition_details
                            .row.add( [ class_name, subject_name,

                                '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                '<input type="hidden" name="email" value="'+email+'">'+
                                '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                                '</form></div>' +
                                '<br /><span class="label badge bg-green-active mobile_number">'+mobile1+'</span>',

                                '<div id="element1"><a href="#" class="showphoto" id='+src+' >' +
                                '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                '<div id="element2"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                                '<i class="fa fa-fw fa-volume-up"></i></a></div>',

                                assign_date,

                                '<span class="badge bg-green">' + tuition_status + '</span>',
                                '&nbsp;' ] )
                            .draw()
                            .node();

                    }

                }
            }

            //populate teacherbookmark tab
            if(data['teacher_bookmark'].length>0){

                //console.log(data['teacher_bookmark']);
                for (var i = 0; i < data['teacher_bookmark'].length; i++) {

                    var firstname = data['teacher_bookmark'][i]['firstname'];
                    var lastname = data['teacher_bookmark'][i]['lastname'];
                    var email = data['teacher_bookmark'][i]['email'];
                    var photo = data['teacher_bookmark'][i]['teacher_photo'];
                    var teacher_id = data['teacher_bookmark'][i]['teacher_id'];
                    var tuition_id = data['teacher_bookmark'][i]['tuition_id'];
                    var td_id = data['teacher_bookmark'][i]['td_id'];
                    var id = data['teacher_bookmark'][i]['id'];
                    var band_name = data['teacher_bookmark'][i]['band_name'];
                    var age = data['teacher_bookmark'][i]['agey'];
                    var experience = data['teacher_bookmark'][i]['experience'];
                    var label = data['teacher_bookmark'][i]['label_name'];
                    var mobile1 = data['teacher_bookmark'][i]['mobile1'];
                    var csrf_token = $('input[name=_token]').val();

                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;


                    var rowNode = teacher_bookmark

                        .row.add( [

                            '<div id="element1" class="element1"><input type="checkbox" name="teacher_broadcast_list" class="teacher_broadcast_list" value="'+teacher_id+'"></div>' +

                            '<div id="element2"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                            '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                            ' <input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                            '<input type="hidden" name="email" value="'+email+'">'+
                            '<button type="submit" class="btn btn-link button-padding">'+firstname+' '+lastname+'</button>'+
                            '</form></div>' +
                            '<br /><span class="label badge bg-green-active mobile1">'+mobile1+'</span>',

                            '<div id="element3"><a href="#" class="showphoto" id='+src+' >' +
                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                            '<div id="element4"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                            '<i class="fa fa-fw fa-volume-up"></i></a></div>',


                            band_name,Math.round(age),experience,label,
                            '<a class="btn teacher-bookmark" id="' + td_id + '" onclick="AssignTuition(' + teacher_id + ',' + tuition_id + ');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Assign</span></a>',

                            '<a class="btn teacher-bookmark" id="' + td_id + '" onclick="TeacherBM(' + teacher_id + ',' + tuition_id + ','+id+');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-warning"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Unookmark</span></a>' ] )
                        .draw()
                        .node();

                }

            }

            //populate label tab
            if(data['tuition_labels'].length>0){

                for (var n = 0; n < data['tuition_labels'].length; n++) {

                    var label = data['tuition_labels'][n]['name'];
                    var id = data['tuition_labels'][n]['id'];

                    var rowNode = label_list

                        .row.add( [ label,
                            '<a class="btn  del-btn" onclick="return ConfirmDelete();"  href="tuition/label/delete/' + id + '" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>'
                        ] )
                        .draw()
                        .node();

                }
            }

            //populate teacher_applications tab
            if(data['teacher_applications'][0]['firstname']!=''){

                console.log(data['teacher_applications']);
                for (var i = 0; i < data['teacher_applications'].length; i++) {

                    var firstname = data['teacher_applications'][i]['firstname'];
                    var lastname = data['teacher_applications'][i]['lastname'];
                    var email = data['teacher_applications'][i]['email'];
                    var photo = data['teacher_applications'][i]['teacher_photo'];
                    var teacher_id = data['teacher_applications'][i]['teacher_id'];
                    var tuition_id = data['teacher_applications'][i]['tuition_id'];
                    var band_name = data['teacher_applications'][i]['band_name'];
                    var age = data['teacher_applications'][i]['agey'];
                    var experience = data['teacher_applications'][i]['experience'];
                    var applied_date = data['teacher_applications'][i]['created_at'];
                    var applied_notes = data['teacher_applications'][i]['notes'];
                    var teacher_labels = data['teacher_applications'][i]['labels'];
                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;

                    var rowNode = teacher_applications
                        .row.add( [

                            '<div id="element3" class="element3"><input type="checkbox" name="teacher_broadcast_list" class="teacher_broadcast_list2" value="'+teacher_id+'"></div>' +

                            '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                            '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                            '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                            '<input type="hidden" name="email" value="'+email+'">'+
                            '<button type="submit" class="btn btn-link">'+firstname+' '+lastname+'</button>'+
                            '</form></div>' +
                            '<br /><span class="label badge bg-green-active mobile1">'+mobile1+'</span>',

                            '<div id="element1"><a href="#" class="showphoto" id='+src+' >' +
                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                            '<div id="element2"><a href="#" class="volume global-list" title="Mark Global" id="'+teacher_id+'">' +
                            '<i class="fa fa-fw fa-volume-up"></i></a></div>',

                            band_name,Math.round(age),experience,teacher_labels,applied_date ] )
                        .draw()
                        .node();


                }

            }

            $( ".showphoto" ).click(function() {

                $("#teacher_profile_image").attr("src",this.id);
                $("#teacher_profile_photo").modal();
            });

            $('.global-list').on('click', function () {

                var teacherid = this.id;

                $.ajax({

                    url: 'global',
                    type: "post",
                    data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

                    success: function (response) {

                        var test = JSON.stringify(response);
                        var data = JSON.parse(test);
                        var success = data['success'];
                        console.log(data['teacher']);
                        $('#myModal').modal();

                    }

                });

            });

            $('.teacher_broadcast_list').change(function() {

                $('#selectall').attr('checked', false); // Unchecks it


            });

            $('.teacher_broadcast_list2').change(function() {

                $('#selectall2').attr('checked', false); // Unchecks it


            });

            $('#selectall').change(function() {

                if($(this).is(":checked")) {

                    $(".element1 input:checkbox").prop("checked", true);

                }else{

                    //find class with element1 and uncheck all checkboxes.
                    $('.element1').find(':checkbox').each(function(){

                        jQuery(this).attr('checked', $('.element1').is(':checked'));

                    });

                }


            });

            $('#selectall2').change(function() {

                if($(this).is(":checked")) {

                    $(".element3 input:checkbox").prop("checked", true);

                }else{

                    //find class with element1 and uncheck all checkboxes.
                    $('.element3').find(':checkbox').each(function(){

                        jQuery(this).attr('checked', $('.element1').is(':checked'));

                    });

                }


            });



            $('#new_line').change(function() {

                if($(this).is(":checked")) {

                    var str = $('#phone_list').val();
                    var newStr = str.split(";").join("\n");
                    $('#phone_list').val(newStr);


                }else{

                    var str = $('#phone_list').val();
                    $('#phone_list').val(str.replace(/\n/g, ";"));

                }
            });


        }

    });
});
//phone numbers broad cast
$('#phone_numbers').on('submit', function (e) {
    e.preventDefault();
    var allVals = [];
    $('#element1 :checked').each(function() {

        allVals.push($(this).val());

    });

    $('#teacher_id').val(allVals);

    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'teacher/phone/broadcast',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $('#wait').modal('hide');
            $(".child").remove();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var str = '';


            for (var key in data['phone_numbers']) {

                var value = data['phone_numbers'][key];

                var str = str.concat(value+";");

                //console.log(key, data['phone_numbers'][key]);
            }
            //console.log(str);
            $('#phone_list').val(str);
            $("#new_line").prop("checked", false);
            $('#phone_number').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

$('#phone_numbers2').on('submit', function (e) {
    e.preventDefault();
    var allVals = [];
    $('#element3 :checked').each(function() {

        allVals.push($(this).val());

    });

    $('#teacher_id2').val(allVals);

    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'teacher/phone/broadcast',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $('#wait').modal('hide');
            $(".child").remove();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var str = '';


            for (var key in data['phone_numbers']) {

                var value = data['phone_numbers'][key];

                var str = str.concat(value+";");
            }
            console.log(str);
            $('#phone_list').val(str);
            $("#new_line").prop("checked", false);
            $('#phone_number').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

$('#email_brodcast').on('submit', function (e) {

    e.preventDefault();

    var allVals = [];

    $('#element1 :checked').each(function() {

        allVals.push($(this).val());

    });

    $('.teacher_id_email').val(allVals);

    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'teacher/email/broadcast',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $(".child").remove();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            for (var key in data['emails']) {

                var value = data['emails'][key];

                $('#email_list tbody').append('<tr class="child">' +
                    '<td><span>'+key+'</span></td>' +
                    '<td><span class="badge bg-yellow">'+value+'</span></td></tr>');

                //console.log(key, data['phone_numbers'][key]);
            }
            $('#wait').modal('hide');
            $('#teacher_email').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

$('#email_brodcast2').on('submit', function (e) {

    e.preventDefault();

    var allVals = [];

    $('#element3 :checked').each(function() {

        allVals.push($(this).val());

    });
    console.log(allVals);
    $('.teacher_id_email').val(allVals);

    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'teacher/email/broadcast',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $(".child").remove();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            for (var key in data['emails']) {

                var value = data['emails'][key];

                $('#email_list tbody').append('<tr class="child">' +
                    '<td><span>'+key+'</span></td>' +
                    '<td><span class="badge bg-yellow">'+value+'</span></td></tr>');

                //console.log(key, data['phone_numbers'][key]);
            }
            $('#wait').modal('hide');
            $('#teacher_email').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

