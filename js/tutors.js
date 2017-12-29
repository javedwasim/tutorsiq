/**
 * Created by javed on 1/13/2017.
 */
jQuery(document).ready(function ($) {

    $('.send-btn').on('click', function() {

    //highlight selected row of teacher table
    $('#example1 tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $(this).addClass('selected');
            $( "div" ).removeClass( "collapsed-box" );
            $( ".teacher-detial" ).removeClass( "fa-minus" );
            $( ".teacher-detial" ).addClass( "fa-plus" );
            $("#teacher-detial").css("display", "block");
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $( "div" ).removeClass( "collapsed-box" );
            $( ".teacher-detial" ).removeClass( "fa-plus" );
            $( ".teacher-detial" ).addClass( "fa-minus" );
            $("#teacher-detial").css("display", "block");
        }
    } );

    var teacher_tuitions = $("#teacher_tuitions").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
       // columnDefs: [  { targets: 4, render: $.fn.dataTable.render.ellipsis( 40, true ) }, ]
    });

    var history_list = $("#history_list").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
        columnDefs: [  { targets: 3, render: $.fn.dataTable.render.ellipsis( 40, true ) }, ]
    });

    var grade_list = $("#subject_grade_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
    });

    var table1 = $("#qualification_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
    }).order( [ 1, 'asc' ] );

    var institute_list = $("#institute_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    }).order( [ 0, 'asc' ] );

    var reference_list = $("#reference_list").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });
    var preference_list = $("#preference_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });
    var location_list = $("#location_list").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });
    var label_list = $("#label_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });




    //Teacher id of selected row
    var teacherid = this.id;

    //add qualification button
    $( "div.add_qualification" ).empty();
    $('div.add_qualification').append('<a href="teacher/qualification/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //add preferences button
    $( "div.add_preference" ).empty();
    $('div.add_preference').append('<a href="teacher/preference/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //add preferences button  reference
    $( "div.add_reference" ).empty();
    $('div.add_reference').append('<a href="teacher/reference/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //add preferences button  reference
    $( "div.location_preference" ).empty();
    $('div.location_preference').append('<a href="teacher/location/preference/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');


    //ajax call to populate teacher detail data.
    $.ajax({

        url: 'detail',
        type: "post",
        data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

        success: function (response) {

            //clear previous loaded data
            $(".child").remove();

            $('#teacher_tuitions').DataTable().clear().draw();
            $('#history_list').DataTable().clear().draw();
            $('#qualification_list').DataTable().clear().draw();
            $('#label_list').DataTable().clear().draw();
            $('#location_list').DataTable().clear().draw();
            $('#preference_list').DataTable().clear().draw();
            $('#reference_list').DataTable().clear().draw();
            $('#institute_list').DataTable().clear().draw();
            $('#subject_grade_list').DataTable().clear().draw();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            //populate qualification tab
            if(data['teacher_qualification'].length>0){

                for (var i = 0; i < data['teacher_qualification'].length; i++) {

                    var level = data['teacher_qualification'][i]['highest_degree'];
                    var passing_year = data['teacher_qualification'][i]['passing_year'];
                    var institution = data['teacher_qualification'][i]['institution'];
                    var qualificationName = data['teacher_qualification'][i]['qualification_name'];
                    var grade = data['teacher_qualification'][i]['grade'];
                    var degree_document = data['teacher_qualification'][i]['degree_document'];
                    var id = data['teacher_qualification'][i]['qid'];
                    var teacherid = data['teacher_qualification'][i]['teacherid'];

                    var qualificationDoc = '<a href="javascript:void(0);" id="'+teacherid+'-'+degree_document+'" ' +
                                            'onclick="QualificationDocs('+id+');" title="download '+degree_document+'">' +
                                            '<i class="fa fa-fw fa-download fa-lg"></i></a>';

                    var rowNode = table1
                        .row.add( [ qualificationName,level, passing_year, institution,grade,qualificationDoc,
                            '<a class="btn edit-btn" href="teacher/qualification/update/' + id + '" title="Edit" style="padding: 0 0;">' +
                            '<span class="label label-success"><i class="fa fa-fw fa-edit" style="font-size: 10px;"></i></span></a>' +
                            '<a class="btn  del-btn" onclick="DeleteQualification('+id+','+teacherid+')"  href="javascript:void(0);" title="Delete">' +
                            '<span class="label label-danger"><i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();

                }
            }
            //populate institute tab
            if(data['teacher_institutes'].length>0){
                for (var j = 0; j < data['teacher_institutes'].length; j++) {

                    var id = data['teacher_institutes'][j]['id'];
                    var teacherid = data['teacher_institutes'][j]['teacher_id'];
                    var institute_id = data['teacher_institutes'][j]['institute_id'];
                    var name = data['teacher_institutes'][j]['name'];

                    var rowNode = institute_list
                        .row.add( [ name,
                            '<a class="btn del-btn" onclick="DeletePreferredInstitute('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }

            }

            //populate institute tab
            if(data['grade_subjects'].length>0){

                for (var j = 0; j < data['grade_subjects'].length; j++) {

                    var id = data['grade_subjects'][j]['id'];
                    var teacherid = data['grade_subjects'][j]['teacher_id'];
                    var tuition_category_id = data['grade_subjects'][j]['tuition_category_id'];
                    var name = data['grade_subjects'][j]['name'];

                    var rowNode = grade_list
                        .row.add( [ name,
                            '<a class="btn del-btn" onclick="DeleteGradesCategories('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }

            }

            //populate preference tab
            if(data['teacher_preference'].length>0){
                for (var m = 0; m < data['teacher_preference'].length; m++) {
                    var subjects = data['teacher_preference'][m]['subjects'];
                    var class_name = data['teacher_preference'][m]['class_name'];
                    var id = data['teacher_preference'][m]['tpid'];
                    var teacherid = data['teacher_preference'][m]['teacher_id'];
                    var cid = data['teacher_preference'][m]['cid'];

                    var rowNode = preference_list
                        .row.add( [ subjects,
                            '<a class="btn  del-btn" onclick="DeleteSubjectPreferrence('+cid+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }

            //populate tuition history tab
            if (data['tuition_history'].length>0){

                for (var n = 0; n < data['tuition_history'].length; n++) {

                    var assign_date = data['tuition_history'][n]['assign_date'];
                    var feedback_rating = data['tuition_history'][n]['feedback_rating'];
                    var reason = data['tuition_history'][n]['feedback_comment'];
                    var year = 2010;
                    var month = 1;
                    var day = 15;
                    var tuitionStartDate = year+'-'+month+'-'+day;
                    var tuitionEndDate = data['tuition_history'][n]['end_date'];
                    var token = $('meta[name="_token"]').attr('content');
                    var tuitionCode = data['tuition_history'][n]['tuition_code'];
                    var code = '<form action="tuitionsshortview" method="post" id="tuitiondetailshortview" target="_blank">'
                        +'<input type="hidden" name="_token" value="'+token+'">'
                        +'<input type="hidden" name="tuition_date" value="custom">'
                        +'<input type="hidden" name="tuition_code" value="'+tuitionCode+'">'
                        +'<input type="hidden" id="start_date" name="start_date"value="'+tuitionStartDate+'">'
                        +'<input type="hidden" id="end_date" name="end_date"value="'+tuitionEndDate+'">'
                        +'<button type="submit" class="btn btn-link">'+tuitionCode+'</button>'
                        +'</form>';
                    var id = data['tuition_history'][n]['id'];
                    var history_subjects = data['tuition_history'][n]['subjects'];
                    var history_location = data['tuition_history'][n]['locations'];
                    var teacherid = data['tuition_history'][n]['teacher_id'];

                    var action = '<a class="btn btn-primary btn-xs reason-btn" id="'+id+'" ' +
                        'href="javascript:void(0);" title="Add Reason" onclick="AddReson('+id+','+teacherid+',\'' + reason + '\');">' +
                        '<i class="fa fa-fw fa-plus-square"></i>Reason</a>';

                    var tuition_status = data['tuition_history'][n]['tuition_status'];
                    if(tuition_status == 0)
                    {
                        tuition_status = "Regular"
                        var status_class = 'badge bg-green';
                    }
                    else{
                        tuition_status = "Trial"
                        var status_class = 'badge bg-yellow';
                    }
                    var status = '<span class="'+status_class+'">'+tuition_status+'</span>';

                    var date = new Date(assign_date);
                    var process_date =( date.getDate()+'/'+ (date.getMonth() + 1)+'/'+date.getFullYear() )

                    var rowNode = history_list
                        .row.add( [ code, process_date,history_subjects,history_location, feedback_rating,reason,status,action] )
                        .draw()
                        .node();
                }
            }

            //populate teacherlocation tab
            if(data['teacher_locations'].length>0){

                for (var n = 0; n < data['teacher_locations'].length; n++) {
                    var location = data['teacher_locations'][n]['zone_locations'];
                    var id = data['teacher_locations'][n]['id'];
                    var zoneid = data['teacher_locations'][n]['zoneid'];
                    var teacherid = data['teacher_locations'][n]['teacher_id'];


                    var rowNode = location_list
                        .row.add( [ location,
                            '<a class="btn  del-btn" onclick="DeleteZoneLocations('+zoneid+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }
            //populate label tab
            if(data['teacher_labels'].length>0){
                for (var n = 0; n < data['teacher_labels'].length; n++) {
                    var label = data['teacher_labels'][n]['name'];
                    var id = data['teacher_labels'][n]['id'];
                    var teacherid = data['teacher_labels'][n]['teacher_id'];

                     var rowNode = label_list
                        .row.add( [ label,
                            '<a class="btn  del-btn" onclick="DeleteLabel('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }
            if(data['tuitions_applied'].length>0){

                for (var n = 0; n < data['tuitions_applied'].length; n++) {
                    var year = 2010;
                    var month = 1;
                    var day = 15;
                    var tuitionStartDate = year+'-'+month+'-'+day;
                    var tuitionEndDate = data['tuitions_applied'][n]['end_date'];
                    var token = $('meta[name="_token"]').attr('content');
                    var tuition_application_id = data['tuitions_applied'][n]['tuition_code'];
                    var tuition_application_code = '<form action="tuitionsapplicationview" method="post" target="_blank">'
                        +'<input type="hidden" name="_token" value="'+token+'">'
                        +'<input type="hidden" name="tuition_date" value="custom">'
                        +'<input type="hidden" name="tuition_code" value="'+tuition_application_id+'">'
                        +'<input type="hidden" id="start_date" name="start_date"value="'+tuitionStartDate+'">'
                        +'<input type="hidden" id="end_date" name="end_date"value="'+tuitionEndDate+'">'
                        +'<button type="submit" class="btn btn-link">'+tuition_application_id+'</button>'
                        +'</form>';
                    var applied_date = data['tuitions_applied'][n]['created_at'];
                    var band_name = data['tuitions_applied'][n]['band_name'];
                    var labels = data['tuitions_applied'][n]['labels'];
                    var notes = data['tuitions_applied'][n]['notes'];
                    var subject_name = data['tuitions_applied'][n]['subject_name'];
                    var location_name = data['tuitions_applied'][n]['locations'];
                    var date = new Date(applied_date);
                    var process_date =( date.getDate()+'/'+ (date.getMonth() + 1)+'/'+date.getFullYear() )
                    var rowNode = teacher_tuitions
                        .row.add( [ tuition_application_code, process_date,subject_name,location_name] )
                        .draw()
                        .node();
                }
            }

        }

    });


    });

    //add teachers to broadcastlist bulk action
    $('.broadcast-selected').on('click', function() {

        //var confirms =  confirm("Are you sure to add selected teachers in broadcast list!");

        var globalTeachers = [];
        $("input[name='globalTeachers[]']:checked").each(function(){globalTeachers.push(

            $(this).val() );

        });

        if((globalTeachers.length>0) ){

            $('#ids').val(globalTeachers);
            var ids = $('#ids').val();
            $.ajax({

                url: 'global',
                type: "post",
                data: {'ids': ids, '_token': $('input[name=_token]').val()},

                success: function (response) {
                    toastr.success('Teachers added to broadcast list!');
                }

            });

            //$("#globalTeachers").submit();

        }else if((globalTeachers.length == 0) ){
            toastr.warning('Please select teachers to add in broadcast list');
        }


    });

    //add teachers to broadcastlist
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

    //click event for teacher photo
    $('.teacher_photo').on('click', function () {

        $("#teacher_profile_image").attr("src", this.id);
        $("#teacher_profile_photo").modal();

    });
    //reset filters
    $('#reset').click(function () {
        $(this).closest('form').find("input[type=text], select").val("");
    });

    //Copy Text To Clipboard
    $(".copyButton").click(function () {

        copyToClipboard(document.getElementById("phone_list"));
        toastr.success('Phone Numbers Copied Successfully!');

    });

    //Copy Text To Clipboard for broadcast screen
    $(".broadcast_copyButton").click(function () {

        copyToClipboard(document.getElementById("broadcast_phone_list"));
        toastr.success('Phone Numbers Copied Successfully!');

    });
    


    //copy function
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

    //convert string to new line
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

    //remove firsst charachter of string seperated by ;
    $('#remove_first').change(function() {

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

});

//load tuition history reason modal
function AddReson(hid,tid,reason) {

    $('#th_tid').val(tid);
    $('#th_id').val(hid);
    $('#reason').val(reason);

    $("#history_reason").modal();
}

//save reason for tuition history
$('#tuitionHistoryForm').on('submit', function (e) {

    e.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({

        url: 'tuition/history/reason',
        type: "POST",
        data: formData,
        async: false,
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (response) {

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var teacherid = data['teacherid'];

            $('#wait').modal('hide');
            var token = $('input[name=_token]').val();
            LoadTeachers(teacherid,token);

            toastr.success('Reason Added Successfully!');


        },
        cache: false,
        contentType: false,
        processData: false
    });

});


function ExperienceDocs(id) {

    var experienceId = id;
    $.ajax({

        url: 'experience/docs',
        type: "post",
        data: {'id': experienceId, '_token': $('input[name=_token]').val()},

        success: function (response) {

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var pathToFile = data['pathToFile'];
            var fileName = data['fileName'];
            $('#pathExpDoc').val(pathToFile);
            $('#filenameExpDoc').val(fileName);
            //console.log(pathToFile);
            $("#experience_docs_download").submit();
        }

    });
}

function QualificationDocs(id) {
    var qualificatinId = id;

    $.ajax({

        url: 'download',
        type: "post",
        data: {'id': qualificatinId, '_token': $('input[name=_token]').val()},

        success: function (response) {

            var test = JSON.stringify(response);
            var data = JSON.parse(test);
            var pathToFile = data['pathToFile'];
            var fileName = data['fileName'];

            $('#path').val(pathToFile);
            $('#filename').val(fileName);
            //console.log(pathToFile);
            $("#javedwasim").submit();
        }

    });

}

function DeleteSubjectPreferrence(cid,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'subject/preference/delete',
            type: "post",
            data: {'id': cid,'teacherid':teacherid, '_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);

                toastr.success('Preferred Subjects Deleted Successfully!.');


            }
        });
    }
}


function DeleteZoneLocations(zid,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'teacher/location/preference/delete',
            type: "post",
            data: {'id': zid,'_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);
                toastr.success('Preferred Location Deleted Successfully!.');


            }
        });
    }
}

function DeletePreferredInstitute(id,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'teacher/institute/delete',
            type: "post",
            data: {'id': id,'_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);
                toastr.success('Preferred Institute Deleted Successfully!.');


            }
        });
    }
}


function DeleteGradesCategories(id,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'teacher/grade/delete',
            type: "post",
            data: {'id': id,'_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);
                toastr.success('Grade/Subject Category Deleted Successfully!.');


            }
        });
    }
}

function DeleteLabel(id,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'teacher/label/delete',
            type: "post",
            data: {'id': id,'_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);
                toastr.success('Label Deleted Successfully!.');


            }
        });
    }
}

function DeleteQualification(id,teacherid) {

    var answer = confirm ("Are you sure you want to delete this item?");
    if (answer)
    {
        $.ajax({

            url: 'teacher/qualification/delete',
            type: "post",
            data: {'id': id,'_token': $('input[name=_token]').val()},
            async: false,
            beforeSend: function () {
                $("#wait").modal();
            },

            success: function (response) {

                $('#wait').modal('hide');
                var test = JSON.stringify(response);
                var data = JSON.parse(test);

                var token = $('input[name=_token]').val();
                LoadTeachers(teacherid,token);
                toastr.success('Qualification Deleted Successfully!.');


            }
        });
    }
}


function LoadTeachers(teacherid,token) {

    var table1 = $("#qualification_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
    }).order( [ 1, 'asc' ] );

    var label_list = $("#label_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });

    var grade_list = $("#subject_grade_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
    });

    var preference_list = $("#preference_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });

    var history_list = $("#history_list").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],
        columnDefs: [  { targets: 3, render: $.fn.dataTable.render.ellipsis( 40, true ) }, ]
    });

    var location_list = $("#location_list").DataTable({
        "paging":   true,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    });

    var institute_list = $("#institute_list").DataTable({
        "paging":   false,
        "ordering": true,
        "info":     true,
        'searching':false,
        "pagingType": "full_numbers",
        destroy: true,
        "bLengthChange": false,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },

        ],

    }).order( [ 0, 'asc' ] );

    //Teacher id of selected row
    var teacherid = teacherid;

    //add qualification button
    $( "div.add_qualification" ).empty();
    $('div.add_qualification').append('<a href="teacher/qualification/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //add preferences button
    $( "div.add_preference" ).empty();
    $('div.add_preference').append('<a href="teacher/preference/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //add location preferences button
    $( "div.location_preference" ).empty();
    $('div.location_preference').append('<a href="teacher/location/preference/add/'+teacherid+'" title="Add" class="btn btn-primary pull-right">' +
        '<i class="fa fa-plus-circle fa-lg"></i> Add New</a>');

    //ajax call to populate teacher detail data.
    $.ajax({

        url: 'detail',
        type: "post",
        data: {'teacherid': teacherid, '_token': $('input[name=_token]').val()},

        success: function (response) {

            $(".child").remove();
            $('#qualification_list').DataTable().clear().draw();
            $('#preference_list').DataTable().clear().draw();
            $('#history_list').DataTable().clear().draw();
            $('#location_list').DataTable().clear().draw();
            $('#institute_list').DataTable().clear().draw();
            $('#subject_grade_list').DataTable().clear().draw();
            $('#label_list').DataTable().clear().draw();

            var test = JSON.stringify(response);
            var data = JSON.parse(test);

            //populate qualification tab
            if(data['teacher_qualification'].length>0){

                for (var i = 0; i < data['teacher_qualification'].length; i++) {

                    var level = data['teacher_qualification'][i]['highest_degree'];
                    var passing_year = data['teacher_qualification'][i]['passing_year'];
                    var institution = data['teacher_qualification'][i]['institution'];
                    var qualificationName = data['teacher_qualification'][i]['qualification_name'];
                    var grade = data['teacher_qualification'][i]['grade'];
                    var degree_document = data['teacher_qualification'][i]['degree_document'];
                    var id = data['teacher_qualification'][i]['qid'];
                    var teacherid = data['teacher_qualification'][i]['teacherid'];

                    var qualificationDoc = '<a href="javascript:void(0);" id="'+teacherid+'-'+degree_document+'" ' +
                        'onclick="QualificationDocs('+id+');" title="download '+degree_document+'">' +
                        '<i class="fa fa-fw fa-download fa-lg"></i></a>';

                    var rowNode = table1
                        .row.add( [ qualificationName,level, passing_year, institution,grade,qualificationDoc,
                            '<a class="btn edit-btn" href="teacher/qualification/update/' + id + '" title="Edit" style="padding: 0 0;">' +
                            '<span class="label label-success"><i class="fa fa-fw fa-edit" style="font-size: 10px;"></i></span></a>' +
                            '<a class="btn  del-btn" onclick="DeleteQualification('+id+','+teacherid+')"  href="javascript:void(0);" title="Delete">' +
                            '<span class="label label-danger"><i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();

                }
            }

            //populate preference tab
            if(data['teacher_preference'].length>0){
                for (var m = 0; m < data['teacher_preference'].length; m++) {
                    var subjects = data['teacher_preference'][m]['subjects'];
                    var class_name = data['teacher_preference'][m]['class_name'];
                    var tid = data['teacher_preference'][m]['tpid'];
                    var teacherid = data['teacher_preference'][m]['teacher_id'];
                    var cid = data['teacher_preference'][m]['cid'];

                    var rowNode = preference_list
                        .row.add( [ subjects,
                            '<a class="btn  del-btn" onclick="DeleteSubjectPreferrence('+cid+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }

            //populate tuition history tab
            if (data['tuition_history'].length>0){

                for (var n = 0; n < data['tuition_history'].length; n++) {

                    var assign_date = data['tuition_history'][n]['assign_date'];
                    var feedback_rating = data['tuition_history'][n]['feedback_rating'];
                    var reason = data['tuition_history'][n]['feedback_comment'];
                    var year = 2010;
                    var month = 1;
                    var day = 15;
                    var tuitionStartDate = year+'-'+month+'-'+day;
                    var tuitionEndDate = data['tuition_history'][n]['end_date'];
                    var token = $('meta[name="_token"]').attr('content');
                    var tuitionCode = data['tuition_history'][n]['tuition_code'];
                    var code = '<form action="tuitionsshortview" method="post" id="tuitiondetailshortview" target="_blank">'
                        +'<input type="hidden" name="_token" value="'+token+'">'
                        +'<input type="hidden" name="tuition_date" value="custom">'
                        +'<input type="hidden" name="tuition_code" value="'+tuitionCode+'">'
                        +'<input type="hidden" id="start_date" name="start_date"value="'+tuitionStartDate+'">'
                        +'<input type="hidden" id="end_date" name="end_date"value="'+tuitionEndDate+'">'
                        +'<button type="submit" class="btn btn-link">'+tuitionCode+'</button>'
                        +'</form>';
                    var id = data['tuition_history'][n]['id'];
                    var history_subjects = data['tuition_history'][n]['subjects'];
                    // var history_subject_name = data['tuition_history'][n]['subjects'];
                    // var history_class_name = data['tuition_history'][n]['classes'];
                    var history_location = data['tuition_history'][n]['locations'];

                    // if(history_class_name != null) {
                    //     if(history_subject_name != null){
                    //         var grade_and_subject = history_class_name+': '+history_subject_name;
                    //     }
                    // }else{
                    //     var grade_and_subject = '';
                    // }

                    var teacherid = data['tuition_history'][n]['teacher_id'];

                    var action = '<a class="btn btn-primary btn-xs reason-btn" id="'+id+'" ' +
                        'href="javascript:void(0);" title="Add Reason" onclick="AddReson('+id+','+teacherid+',\'' + reason + '\');">' +
                        '<i class="fa fa-fw fa-plus-square"></i>Reason</a>';

                    var tuition_status = data['tuition_history'][n]['tuition_status'];
                    if(tuition_status == 0)
                    {
                        tuition_status = "Regular"
                        var status_class = 'badge bg-green';
                    }
                    else{
                        tuition_status = "Trial"
                        var status_class = 'badge bg-yellow';
                    }
                    var status = '<span class="'+status_class+'">'+tuition_status+'</span>';

                    var date = new Date(assign_date);
                    var process_date =( date.getDate()+'/'+ (date.getMonth() + 1)+'/'+date.getFullYear() )

                    var rowNode = history_list
                        .row.add( [ code, process_date,history_subjects,history_location, feedback_rating,reason,status,action] )
                        .draw()
                        .node();
                }
            }

            //populate teacherlocation tab
            if(data['teacher_locations'].length>0){

                for (var n = 0; n < data['teacher_locations'].length; n++) {
                    var location = data['teacher_locations'][n]['zone_locations'];
                    var id = data['teacher_locations'][n]['id'];
                    var zoneid = data['teacher_locations'][n]['zoneid'];
                    var teacherid = data['teacher_locations'][n]['teacher_id'];


                    var rowNode = location_list
                        .row.add( [ location,
                            '<a class="btn  del-btn" onclick="DeleteZoneLocations('+zoneid+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }

            //populate institute tab
            if(data['teacher_institutes'].length>0){
                for (var j = 0; j < data['teacher_institutes'].length; j++) {

                    var id = data['teacher_institutes'][j]['id'];
                    var teacherid = data['teacher_institutes'][j]['teacher_id'];
                    var institute_id = data['teacher_institutes'][j]['institute_id'];
                    var name = data['teacher_institutes'][j]['name'];

                    var rowNode = institute_list
                        .row.add( [ name,
                            '<a class="btn del-btn" onclick="DeletePreferredInstitute('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }

            }

            //populate institute tab
            if(data['grade_subjects'].length>0){

                for (var j = 0; j < data['grade_subjects'].length; j++) {

                    var id = data['grade_subjects'][j]['id'];
                    var teacherid = data['grade_subjects'][j]['teacher_id'];
                    var tuition_category_id = data['grade_subjects'][j]['tuition_category_id'];
                    var name = data['grade_subjects'][j]['name'];

                    var rowNode = grade_list
                        .row.add( [ name,
                            '<a class="btn del-btn" onclick="DeleteGradesCategories('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }

            }

            //populate label tab
            if(data['teacher_labels'].length>0){
                for (var n = 0; n < data['teacher_labels'].length; n++) {
                    var label = data['teacher_labels'][n]['name'];
                    var id = data['teacher_labels'][n]['id'];
                    var teacherid = data['teacher_labels'][n]['teacher_id'];

                    var rowNode = label_list
                        .row.add( [ label,
                            '<a class="btn  del-btn" onclick="DeleteLabel('+id+','+teacherid+');"  href="javascript:void(0);" title="Delete" style="padding: 0 0;"><span class="label label-danger">' +
                            '<i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i></span></a>' ] )
                        .draw()
                        .node();
                }
            }
            //


        }
    });


}

//set zone locations
$("#zone").change(function () {

    var zone_id = $("#zone").val();

    $.ajax({

        url: 'teacher/zones/'+zone_id,
        type: "GET",
        data: {'zone_id':zone_id},
        async: false,

        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (data) {

            var options = data['options']

            $('#locations')
                .find('option')
                .remove()
                .end()
                .append(options);

            $('#wait').modal('hide');


        },
        cache: false,
        contentType: false,
        processData: false

    });

});
//Change Grade Event
$("#class").change(function () {

    var class_id = $("#class").val();
    $.ajax({

        url: 'teacher/subjects/'+class_id,
        type: "GET",
        data: {'class_id':class_id},
        async: false,

        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (data) {

            var options = data['options']

            $('#subjects')
                .find('option')
                .remove()
                .end()
                .append(options);

            $('#wait').modal('hide');


        },
        cache: false,
        contentType: false,
        processData: false

    });

});

//tuition short view
$('.short-view').click(function () {

    var id = this.id;
    $.ajax({

        url: 'teachers/short/view/'+id,
        type: "GET",
        data: {'teacherid':id},
        beforeSend: function () {
            $("#wait").modal();
        },
        success: function (data) {

            $('#wait').modal('hide');

            $( ".teacher-short-view" ).empty();
            $('.teacher-short-view').append(data);
            $('#teacher_short_view').modal('show');

        },
        cache: false,
        contentType: false,
        processData: false

    });




});
//matched teacher broadcast button
$('.select_all').on('ifChecked', function(event){
    $('input').iCheck('check');

});

$('.select_all').on('ifUnchecked', function(event){
    $('input').iCheck('uncheck');
});
//add to broadcast list
$('.teacherbb-broadcast-selected').on('click', function() {

    //var confirms =  confirm("Are you sure to add selected teachers in broadcast list!");

    var globalTeachers = [];
    $("input[name='globalTeachers[]']:checked").each(function(){globalTeachers.push(

        $(this).val() );

    });

    if((globalTeachers.length>0) ){

        $('#ids').val(globalTeachers);
        var ids = $('#ids').val();
        $.ajax({

            url: 'teacherbb',
            type: "post",
            data: {'ids': ids, '_token': $('input[name=_token]').val()},

            success: function (response) {
                toastr.success('Added to broadcast list!');
            }

        });

        //$("#globalTeachers").submit();

    }else if((globalTeachers.length == 0) ){
        toastr.warning('Please select teachers to add in broadcast list');
    }

});

$('#broadcast_new_line').on('ifChecked', function(event){

    var str = $('#broadcast_phone_list').val();
    var newStr = str.split(";").join("\n");
    $('#broadcast_phone_list').val(newStr);

});

$('#broadcast_remove_first').on('ifChecked', function(event){

    var newStr = "";
    var values = $('#broadcast_phone_list').val();
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
    $('#broadcast_phone_list').val(newStr);

});

$('#broadcast_new_line').on('ifUnchecked', function(event){

    var str = $('#broadcast_phone_list').val();
    $('#broadcast_phone_list').val(str.replace(/\n/g, ";"));

});

$(".broadcast_phone_numbers").click(function(){

    document.getElementById("broadcast_remove_first").checked= false;

    var str  = $('#broadcast_contact_no').val();
    $('#broadcast_phone_list').val(str);
    $("#broadcast_new_line").prop("checked", false);

    $('#broadcast_phone_number').modal('show');

});