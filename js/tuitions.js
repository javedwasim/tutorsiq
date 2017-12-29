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

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            $("#wait").modal('hide');
            var success = data['success'];
            LoadTuitionDetails(tuition_id,$('input[name=_token]').val());
            toastr.success('Teacher Unbookmarked Successfully!');

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
                toastr.success('Teaher Broadcast successfully');

            }

        });

    });

}

function MarkRegular(tuition_status_regular_id, tuiion_history_id, tuition_id,teacher_id,td_id) {

    $(".child").remove();

    $.ajax({

        url: 'tuitions/mark/regular',
        type: "post",
        data: {
            'tuition_status_regular_id': tuition_status_regular_id,
            'tuiion_history_id': tuiion_history_id,
            'tuition_id': tuition_id,
            'teacher_id': teacher_id,
            'td_id': td_id,
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

                toastr.success('Tuition Mark Regular Successfully!');
                LoadTuitionDetails(tuition_id,$('input[name=_token]').val());
            }

        }

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

function DeleteTuitionLabel(tuition_label_id,tuition_id) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'tuition/label/delete',
            type: "post",
            data: {'id': tuition_label_id, '_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                LoadTuitionDetails(tuition_id,$('input[name=_token]').val());
                toastr.success('Tuition Label Deleted Successfully!.');


            }
        });
    }
}

function  DeleteCSM(tuition_id, td_id) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'csm/delete',
            type: "post",
            data: {'id': td_id, '_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);
                //$('#tuition').modal('hide');
                toastr.success('Tuition Deleted Successfully!.');
                LoadTuitionDetails(tuition_id,$('input[name=_token]').val());


            }
        });
    }

}

function UnAssignTeacher(tuition_status_regular_id, tuiion_history_id, tuition_id,teacher_id,td_id) {

    $(".child").remove();

    $.ajax({

        url: 'unassign/teacher',
        type: "post",
        data: {
            'tuition_status_regular_id': tuition_status_regular_id,
            'tuiion_history_id': tuiion_history_id,
            'tuition_id': tuition_id,
            'teacher_id': teacher_id,
            'td_id': td_id,
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
                toastr.success('Teacher Unassigned Successfully!');
                LoadTuitionDetails(tuition_id,$('input[name=_token]').val());
            }

        }

    });



}

function LoadTuitionDetails(tuition_id,token){

     //clear previous data in datatables
    $('#tuition_details_list').DataTable().clear().draw();
    $('#teacher_bookmark_list').DataTable().clear().draw();
    $('#label_list').DataTable().clear().draw();
    $('#teacher_applications_listing').DataTable().clear().draw();



    var tuition_details = $("#tuition_details_list").DataTable({

        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [

            { "orderable": false,
                "targets": 0
            },
            {
                "orderable": false,
                "targets": -1

            }
        ]


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
        "order": [[ 2, "asc" ]],
        "columnDefs": [

            { "orderable": false,
                "targets": 0
            },
            {
                "orderable": false,
                "targets": -1

            }
        ]


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
        "columnDefs": [

            { "orderable": false,
                "targets": 0
            },
            {
                "orderable": false,
                "targets": -1

            }
        ]


    });

    var teacher_applications = $("#teacher_applications_listing").DataTable({

        "paging":   false,
        "ordering": true,
        "info":     false,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "order": [[ 2, "desc" ]]

    });

    $.ajax({

        url: 'tuitions/detail',
        type: "post",
        data: {'tuition_id': tuition_id, '_token': token},

        success: function (response) {

            $("#teacher-detial").css("display", "block");
            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            $(".child").remove();
            $('#selectall').attr('checked', false); // Unchecks it
            $('#teacher_id').val(''); //empty previous loaded teacher ids.
            $('#teacher_id2').val(''); //empty previous loaded teacher ids.

            //console.log(data['tuition_details']);
            //populate tuition_details tab
            if(data['tuition_details'].length>0){

                for (var i = 0; i < data['tuition_details'].length; i++) {

                    var firstname = data['tuition_details'][i]['firstname'];
                    var lastname = data['tuition_details'][i]['lastname'];
                    var fullname = data['tuition_details'][i]['fullname'];
                    var email = data['tuition_details'][i]['email'];
                    var photo = data['tuition_details'][i]['teacher_photo'];
                    var class_name = data['tuition_details'][i]['class_name'];
                    var subject_name = data['tuition_details'][i]['subject_name'];
                    var location_name = data['tuition_details'][i]['locations'];
                    var teacher_id = data['tuition_details'][i]['teacher_id'];
                    var tuition_id = data['tuition_details'][i]['tuition_id'];
                    var td_id = data['tuition_details'][i]['id'];
                    var assign_date = data['tuition_details'][i]['assign_date'];

                    var tuiion_history_id = data['tuition_details'][i]['tuiion_history_id'];
                    var mobile1 = data['tuition_details'][i]['mobile1'];
                    var is_trial = data['tuition_details'][i]['is_trial'];
                    var tuition_status_regular_id = 2;
                    var csrf_token = $('input[name=_token]').val();
                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;

                    if(is_trial == 1 ){var tuition_status = 'Trial';}else{var tuition_status = 'Regular';}

                    if (!!fullname){

                        var teacher_name = '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                            '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                            '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                            '<input type="hidden" name="email" value="'+email+'">'+
                                            '<button type="submit" class="btn btn-link">'+fullname+'</button>'+
                                            '</form></div>' +
                                            '<br /><span class="mobile_number"><a href="tel:'+mobile1+'">'+mobile1+'</a></span>';

                        var photo  =        '<div id="element3"><a href="javascript:void(0);" title="profile Pic" class="showphoto" id='+src+' >' +
                                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                            '<div id="element4"><a href="javascript:void(0);" class="volume text-red global-list" title="Add to Global Teachers List" id="'+teacher_id+'">' +
                                            '<i class="fa fa-fw fa-bullhorn"></i></a></div>';

                        var trial_status =  '<span class="badge bg-yellow">' + tuition_status + '</span>';

                        var markregular       =  '<a href="javascript:void(0);" title="Mark Regular" id="markregular" ' +
                                                    'onclick="MarkRegular(' + tuition_status_regular_id + ',' + tuiion_history_id + ',' + tuition_id + ','+teacher_id+','+td_id+');">' +
                                                    '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i></span></a>';

                        var regular_status = '<span class="badge bg-green">' + tuition_status + '</span>';

                        var uassigned_tuition  =  '<a href="javascript:void(0);" title="Unassign Teacher" id="unassign" ' +
                            'onclick="UnAssignTeacher(' + tuition_status_regular_id + ',' + tuiion_history_id + ',' + tuition_id + ','+teacher_id+','+td_id+');">' +
                            '<span class="label label-warning"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i></span></a>';


                    }else{

                        var teacher_name= '';
                        var photo = '';
                        var trial_status = '';
                        var regular_status ='';
                        var assign_date = '';
                    }

                    if (is_trial == '1') {

                        var rowNode = tuition_details
                                        .row.add( [ class_name, subject_name,location_name,teacher_name,photo,assign_date,trial_status,markregular+" "+uassigned_tuition ] )
                                        .draw()
                                        .node();

                    } else {

                        //if no teacher is assigned
                        if(teacher_name == ''){

                            //can delete subjects
                            var action = '<a href="javascript:void(0);" title="delete" id="deletecsm" ' +
                                            'onclick="DeleteCSM('+tuition_id+','+td_id+');">' +
                                            '<span class="label label-danger"><i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i>&nbsp;</span></a>';
                            var rowNode = tuition_details
                                .row.add( [ class_name, subject_name,location_name,teacher_name,photo, assign_date,regular_status,action ] )
                                .draw()
                                .node();



                        }
                        //if teacher status is not trial.
                        else if(trial_status != '1'){

                            //unassign teacher

                            var rowNode = tuition_details
                                .row.add( [ class_name, subject_name,location_name,teacher_name,photo, assign_date,regular_status,uassigned_tuition ] )
                                .draw()
                                .node();


                        }


                    }

                }
            }

            //populate teacherbookmark tab
            if(data['teacher_bookmark'].length>0){

                //console.log(data['teacher_bookmark']);
                for (var i = 0; i < data['teacher_bookmark'].length; i++) {

                    var firstname = data['teacher_bookmark'][i]['firstname'];
                    var lastname = data['teacher_bookmark'][i]['lastname'];
                    var fullname = data['teacher_bookmark'][i]['fullname'];
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
                            '<button type="submit" class="btn btn-link button-padding">'+fullname+'</button>'+
                            '</form></div>' +
                            '<br /><span class="mobile1"><a href="tel:'+mobile1+'">'+mobile1+'</a></span>',

                            '<div id="element3"><a href="javascript:void(0);" title="profile Pic" class="showphoto" id='+src+' >' +
                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                            '<div id="element4"><a href="javascript:void(0);" class="volume text-red global-list" title="Add to Global Teachers List" id="'+teacher_id+'">' +
                            '<i class="fa fa-fw fa-bullhorn"></i></a></div>',

                            band_name,Math.round(age),experience,label,
                            '<div id="element1"><a class="btn teacher-bookmark" id="' + td_id + '" onclick="AssignTuition(' + teacher_id + ',' + tuition_id + ');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-primary"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Assign</span></a></div>'+
                            '<div id="element2"><a class="btn teacher-bookmark" id="' + td_id + '" onclick="TeacherBM(' + teacher_id + ',' + tuition_id + ','+id+');" title="Assign" style="padding: 0 0;">' +
                            '<span class="label label-warning"><i class="fa fa-fw fa-external-link" style="font-size: 10px;"></i>Unookmark</span></a></div>' ] )
                        .draw()
                        .node();

                }

            }

            //populate label tab
            if(data['tuition_labels'].length>0){

                $('#tuitionID').val(tuition_id);

                for (var n = 0; n < data['tuition_labels'].length; n++) {

                    var label = data['tuition_labels'][n]['name'];
                    var id = data['tuition_labels'][n]['id'];


                    var rowNode = label_list

                        .row.add( [ label,
                            '<a class="btn  del-btn" href="javascript:void(0);" title="Delete" onclick="DeleteTuitionLabel('+id+','+tuition_id+');"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>'
                        ] )
                        .draw()
                        .node();

                }
            }

            //populate teacher_applications tab
            if(data['teacher_applications'].length>0){

                //console.log(data['teacher_applications']);
                for (var i = 0; i < data['teacher_applications'].length; i++) {

                    var fullname = data['teacher_applications'][i]['fullname'];
                    var email = data['teacher_applications'][i]['email'];
                    var photo = data['teacher_applications'][i]['teacher_photo'];
                    var teacher_id = data['teacher_applications'][i]['teacher_id'];
                    var band_name = data['teacher_applications'][i]['band_name'];
                    var age = data['teacher_applications'][i]['agey'];
                    var mobile1 = data['teacher_applications'][i]['mobile1'];
                    var application_id = data['teacher_applications'][i]['id'];


                    if(age == null){
                        age = ''
                    }else{
                        age = Math.round(age);
                    }
                    var experience = data['teacher_applications'][i]['experience'];
                    var applied_date = new Date(data['teacher_applications'][i]['created_at']);
                    var applied_date = applied_date.getDate() + '/' + (applied_date.getMonth()+1) + '/' + applied_date.getFullYear()

                    var applied_notes = data['teacher_applications'][i]['notes'];
                    var teacher_labels = data['teacher_applications'][i]['labels'];
                    var src = '../local/teachers/'+teacher_id+'/photo/'+photo;

                    if (!!fullname){

                        var status = '<div id="element1"><div id="application_status" class="application_status"><input type="checkbox" name="application_status_list"  value="'+application_id+'">' +
                                     '<input type="hidden" name="tuitionid2" class="tuitionid2" value='+tuition_id+'> </div></div>'+
                                     '<div id="element2">'+data['teacher_applications'][i]['status_name']+'</div>';

                        var teacher_name =  '<div id="element3" class="element3"><input type="checkbox" name="teacher_broadcast_list" class="teacher_broadcast_list2" value="'+teacher_id+'"></div>' +

                                            '<div id="element4"><form method="POST" action="teachers" accept-charset="UTF-8" id="myform" target="_blank">'+
                                            '<input type="hidden" name="_token" value="'+csrf_token+'">'+
                                            '<input type="hidden" name="teacher_id" value="'+teacher_id+'">'+
                                            '<input type="hidden" name="email" value="'+email+'">'+
                                            '<button type="submit" class="btn btn-link">'+fullname+'</button>'+
                                            '</form></div>' +
                                            '<br /><span style="margin-left: 35px;"><a href="tel:'+mobile1+'">'+mobile1+'</a></span>';

                        var photo =         '<div id="element1"><a href="javascript:void(0);" title="profile Pic" class="showphoto" id='+src+' >' +
                                            '<img src="'+src+'" alt="profile Pic" class="img-circle teacher-photo"></a></div>' +
                                            '<div id="element2"><a href="javascript:void(0);" class="volume text-red global-list" title="Add to Global Teachers List" id="'+teacher_id+'">' +
                                            '<i class="fa fa-fw fa-bullhorn"></i></a></div>';



                    }else{

                        var teacher_name= '';
                        var photo= '';

                    }

                    var rowNode = teacher_applications

                        .row.add( [teacher_name,photo,status,applied_date ] )
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
                        //console.log(data['teacher']);
                        toastr.success('Teaher Broadcast successfully');


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
                    $(".application_status input:checkbox").prop("checked", true);

                }else{

                    //find class with element1 and uncheck all checkboxes.
                    $('.element3').find(':checkbox').each(function(){

                        jQuery(this).attr('checked', $('.element1').is(':checked'));

                    });

                    $('.application_status').find(':checkbox').each(function(){

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

            $('#remove_first1').change(function() {

                if($(this).is(":checked")) {

                    var newStr = "";
                    var values = $('#phone_list').val();
                    var phoneNumbers = values.split(';');

                    for(var i = 0; i < phoneNumbers.length; i++) {
                        // Trim the excess whitespace.
                        phoneNumbers[i] = phoneNumbers[i].substr(1);
                        // Add additional code here, such as:
                        if(i < phoneNumbers.length-1){

                            newStr += phoneNumbers[i]+";";

                        }else{

                            newStr += phoneNumbers[i];
                        }


                    }
                    $('#phone_list').val(newStr);

                }
            });

            //assign tuition id to add label button.
            $('#tuitionID').val(tuition_id)

        }

    });

}

//phone numbers broad cast
$('#phone_numbers').on('submit', function (e) {

    $("#teacher_ids").val('');
    e.preventDefault();
    var allVals = [];
    $('#element1 :checked').each(function() {

        allVals.push($(this).val());

    });

    $('#teacher_ids').val(allVals);

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

            $('#phone_list').val(str);
            $("#new_line").prop("checked", false);
            $('#phone_number').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

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

        start_date = SplitString(resultDate);;
        end_date = SplitString(resultDate);;

        $("#start_date").val(start_date);
        $("#end_date").val(end_date);


    }
    else if(date_filter==7){

        end_date = SplitString(resultDate);
        newDate  = rightNow.setDate(rightNow.getDate() - 7);

        rightNow = new Date(newDate);
        resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        start_date = SplitString(resultDate);

        $("#start_date").val(start_date);
        $("#end_date").val(end_date);

    }
    else if(date_filter==14){

        end_date = SplitString(resultDate);
        newDate  = rightNow.setDate(rightNow.getDate() - 14);

        rightNow = new Date(newDate);
        resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        start_date = SplitString(resultDate);

        $("#start_date").val(start_date);
        $("#end_date").val(end_date);
    }
    else if(date_filter==30){

        end_date = SplitString(resultDate);
        newDate  = rightNow.setDate(rightNow.getDate() - 30);

        rightNow = new Date(newDate);
        resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        start_date = SplitString(resultDate);

        $("#start_date").val(start_date);
        $("#end_date").val(end_date);


    }
    else if(date_filter==90){

        end_date = SplitString(resultDate);
        newDate  = rightNow.setDate(rightNow.getDate() - 90);

        rightNow = new Date(newDate);
        resultDate = rightNow.toISOString().slice(0,10).replace(/-/g,"-");
        start_date = SplitString(resultDate);

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

function SplitString(str) {

    var fields = str.split('-');
    var year = fields[0];
    var month = fields[1];
    var day = fields[2];

    return day+'/'+month+'/'+year;
}

//load tuition details.
$('.tuition_detail').click(function () {

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
    var token = $('input[name=_token]').val();
    LoadTuitionDetails(tuition_id,token);
    //alert(tuition_id);

});

//uncheck select all on tab change event for bookmark and teacher applications.
$(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
    //console.log(e.target) // activated tab

    // Unchecks it teacher applications
    $('#selectall2').attr('checked', false);
    // Unchecks it bookmark
    $('#selectall').attr('checked', false);

    // Unchecks it teacher applications
    $('.element3').find(':checkbox').each(function(){

        jQuery(this).attr('checked', $('.element1').is(':checked'));

    });
    // Unchecks it bookmark
    $('.element1').find(':checkbox').each(function(){

        jQuery(this).attr('checked', $('.element1').is(':checked'));

    });
    // Unchecks it teacher applications status
    $('.application_status').find(':checkbox').each(function(){

        jQuery(this).attr('checked', $('.element1').is(':checked'));

    });

})

//apply tuition application status bulk action
$('#applicationStatusForm').on('submit', function (e) {

    e.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'application/update/status',
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
            var currentTuitionId = data['currentTuitionId2'];
            console.log(currentTuitionId);

            // Unchecks it
            $('#selectall2').attr('checked', false);
            $('.element3').find(':checkbox').each(function(){

                jQuery(this).attr('checked', $('.element1').is(':checked'));

            });

            $('.application_status').find(':checkbox').each(function(){

                jQuery(this).attr('checked', $('.element1').is(':checked'));

            });

            LoadTuitionDetails(currentTuitionId,$('input[name=_token]').val());
            $('#applicationStatus').modal('hide');

            toastr.success('Application Status Changed Successfully!');

        },
        cache: false,
        contentType: false,
        processData: false
    });
});

$('#application_status').on('submit', function (e) {

    e.preventDefault();
    var allVals = [];
    $('#application_status :checked').each(function() {

        allVals.push($(this).val());

    });

    var currentTuitionId = $('.tuitionid2').val();

    $('#application_id2').val(allVals);
    $('#currentTuitionId').val(currentTuitionId);


    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'teacher/application/status',
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
            var options = data['application_list'];
            var applicationids = data['applicationids'];

            $('#applicationids').val(applicationids);


            $('#application_status2')
                .find('option')
                .remove()
                .end()
                .append(options);

            $('#currentTuitionId2').val($('.tuitionid2').val());
            console.log($('#currentTuitionId2').val());
            $('#applicationStatus').modal('show');

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

//load modal to add new labels
$('.addnewlabel').on('click', function() {
    $('#addNewLabels').modal();
});


//load modal to change or update tuition labels
$('#changeLabelbtn').on('click', function() {

    var confirms =  confirm("Are you sure to change selected tuitions labels!");

    var globalTuitions = [];
    $("input[name='globalTuition[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((confirms)  && (globalTuitions.length>0) ){

        $('#lids').val(globalTuitions);
        $('.appendLabels').val(globalTuitions);
        var ids = $('#lids').val();
        $('#updateTuitionLabels').modal();
        //console.log(ids);

    }else if((confirms) && (globalTuitions.length == 0) ){

        alert('Please Select tuitions!');
    }


});



//load modal to change status for tuitions i.e. bulk action
$('#changeStatusbtn').on('click', function() {

    var confirms =  confirm("Are you sure to change selected tuitions status!");

    var globalTuitions = [];
    $("input[name='globalTuition[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((confirms)  && (globalTuitions.length>0) ){

        $('#tids').val(globalTuitions);
        var ids = $('#tids').val();
        $('#updateTuitionStatus').modal();
        //console.log(ids);

    }else if((confirms) && (globalTuitions.length == 0) ){

        alert('Please Select tuitions!');
    }


});



//update tuition status i.e. bulk action
$('#tuitionStatusForm').on('submit', function (e) {

    e.preventDefault();
    var ids = $('#tids').val();
    var formData = new FormData($(this)[0]);
    $.ajax({

        url: 'tuition/update/status',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            //console.log(data);
            $('#wait').modal('hide');
            toastr.success('Tuitions Status Changed Successfully!');
            $('#submit_pagesize').trigger('click');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});

//load tutiion summary
$('#tuitionsSummary').on('submit', function (e) {

    e.preventDefault();
    var globalTuitions = [];
    $("input[name='globalTuition[]']:checked").each(function(){globalTuitions.push(
        $(this).val() );

    });

    $('#summaryids').val(globalTuitions);
    var ids = $('#summaryids').val();

    if(ids.length==0){
        alert('Please select tutiion for summary');
        return;
    }

    var formData = new FormData($(this)[0]);

    $.ajax({

        url: 'tuitions/summary',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $('#wait').modal('hide');
            //clear previous and new appended content
            $(".showSummary").empty();
            $(".showSummary").append( response );

            $("#showSummary").modal('show');

        },
        cache: false,
        contentType: false,
        processData: false
    });

});
//load tutiion summary

//add tuitions to broadcastlist bulk action
$('.broadcast-selected').on('click', function() {

    //var confirms =  confirm("Are you sure to add selected tuitions in broadcast list!");

    var globalTuitions = [];
    $("input[name='globalTuition[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((globalTuitions.length>0) ){

        $('#ids').val(globalTuitions);
        var ids = $('#ids').val();
        $.ajax({

            url: 'tuition/global',
            type: "post",
            data: {'ids': ids, '_token': $('input[name=_token]').val()},

            success: function (response) {
                toastr.success('Tuitions Broadcast Successfully!');
            }

        });

        //$("#globalTuitions").submit();

    }else if((globalTuitions.length == 0) ){
        toastr.warning('Please select tuitions to add in broadcast list');
    }


});


//add tuitions to global list
$('.tuition-global-list').on('click', function () {

    var tuitionid = this.id;
    $.ajax({

        url: 'tuition/global',
        type: "post",
        data: {'tuitionid': tuitionid, '_token': $('input[name=_token]').val()},

        success: function (response) {

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var success = data['success'];

            toastr.success('Tuitions Broadcast Successfully!');

        }

    });

});

//copy buttons
<!-- Copy Text -->
$("#copyTuitionText").click(function () {

    $('#smsText').val();
    copyToClipboard(document.getElementById("tuition_text"));
    toastr.success('Text Copied Successfully!');

});

$("#copysmsText").click(function () {

    $('#smsText').val();
    copyToClipboard(document.getElementById("sms_text"));
    toastr.success('Text Copied Successfully!');

});

$("#shortview_copyTuitionText").click(function () {

    $('#shortview_sms_text').val();
    copyToClipboard(document.getElementById("shortview_tuition_text"));
    toastr.success('Text Copied Successfully!');

});

$("#shortview_copysmsText").click(function () {

    $('#shortview_sms_text').val();
    copyToClipboard(document.getElementById("shortview_sms_text"));
    toastr.success('Text Copied Successfully!');

});

<!-- Copy Text -->
$(".copyButton").click(function () {

    copyToClipboard(document.getElementById("phone_list"));
    toastr.success('Text Copied Successfully!');

});

//Copy text to clip board
function copyToClipboard(elem) {
    // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");

    } catch (e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}



//initialize select2 plugin
$(function () {

    $(".select2").select2();

});

//delete button click
$('.del-btn').click(function () {

    return confirm("Are you sure to delete this item!");
});

//reset fitlers
$('#reset').click(function () {
    $(this).closest('form').find("input[type=text], select").val("");
});


function showPhoto(){

    $( ".img-circle" ).click(function() {

        $("#teacher_profile_image").attr("src",this.src);
        $("#teacher_profile_photo").modal();
    });

}

function ConfirmDelete() {
    return confirm("Are you sure to delete this item!");
}

//load values in Tuition Followups SelectAll
$('#followup-changeLabelbtn').on('click', function() {

    var confirms =  confirm("Are you sure to change selected tuitions Label?");
    var globalTuitions = [];
    $("input[name='tuitionFollowups[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((confirms)  && (globalTuitions.length>0) ){

        $('#lidss').val(globalTuitions);
        $('#updatelidss').val(globalTuitions);
        var ids = $('#lidss').val();
        $('#updateFollowupTuitionsStatus').modal();
        //console.log(ids);

    }else if((confirms) && (globalTuitions.length == 0) ){

        alert('Please Select tuitions!');
    }

});
$('#followup-changeStatusbtn').on('click', function() {


    var confirms =  confirm("Are you sure to change selected tuitions status?");

    var globalTuitions = [];
    $("input[name='tuitionFollowups[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((confirms)  && (globalTuitions.length>0) ){

        $('#tidss').val(globalTuitions);
        var ids = $('#tidss').val();
        $('#updateFollowupTuitions').modal();
        //console.log(ids);

    }else if((confirms) && (globalTuitions.length == 0) ){

        alert('Please Select tuitions!');
    }


});


$('#followup-isStarred').on('click', function() {


    var confirms =  confirm("Are you sure to change selected tuitions status?");

    var globalTuitions = [];
    $("input[name='tuitionFollowups[]']:checked").each(function(){globalTuitions.push(

        $(this).val() );

    });

    if((confirms)  && (globalTuitions.length>0) ){

        $('#starid').val(globalTuitions);
        var ids = $('#starid').val();
        $('#starunstar').modal();
        //console.log(ids);

    }else if((confirms) && (globalTuitions.length == 0) ){

        alert('Please Select tuitions!');
    }


});

$('#followup-summary').on('click', function() {

    var globalTuitions = [];
    $("input[name='tuitionFollowups[]']:checked").each(function(){globalTuitions.push(
        $(this).val() );

    });

    $('#fsummaryids').val(globalTuitions);
    var ids = $('#fsummaryids').val();

    if(ids.length==0){
        alert('Please select tutiion for summary');
        return;
    }

    $("#followupSummary").modal('show');


});
//load tutiion summary
$('#shareSummary').on('submit', function (e) {

    e.preventDefault();

    var formData = new FormData($(this)[0]);

    $.ajax({

        url: 'followup/summary',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            $('#wait').modal('hide');
            $("#followupSummary").modal('hide');
            //clear previous and new appended content
            $(".showSummary").empty();
            $(".showSummary").append( response );
            $("#showSummary").modal('show');


        },
        cache: false,
        contentType: false,
        processData: false
    });

});

    $('#followup-shortView').on('click', function() {

        var globalTuitions = [];
        $("input[name='tuitionFollowups[]']:checked").each(function(){globalTuitions.push(
            $(this).val() );

        });

        $('#fsummaryids').val(globalTuitions);
        var ids = $('#fsummaryids').val();

        if(ids.length==0){
            alert('Please select tutiion for summary');
            return;
        }

        var id = ids;
        $.ajax({

            url: 'followup/shortview',
            type: "POST",
            data: {'tuitionid':id, '_token': $('input[name=_token]').val()},
            beforeSend: function () {
                 $("#wait").modal();
            },
            success: function (response) {
                var test = JSON.stringify(response);
                var data = JSON.parse(test);
                //console.log(data);

                var shortViewSms = data['smsText'];
                var shortViewTuition = data['tuitionDetail'];

                $('#wait').modal('hide');
                $('#shortview_sms_text').val(shortViewSms);
                $('#shortview_tuition_text').val(shortViewTuition);
                $('#shortview_tuition_short_view').modal();
            }
        })


    });

