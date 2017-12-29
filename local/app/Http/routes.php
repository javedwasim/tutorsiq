<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('/welcome');
});
Route::get('/contactus', function () {
    return view('/contact_us');
})->name('contactus');

Route::post ('tutorsignup/troubleemail', 'TeacherPortal@SendEmailToUser');
Route::post ('province/cities', 'TeacherPortal@LoadProvinceCities');
Route::get ('tutorsignup', 'TeacherPortal@TeacherSignup');
Route::post ('tutorsignup/step2', 'TeacherPortal@Step2');
Route::get ('tutorsignup/step2', 'TeacherPortal@Step2');
Route::get ('tutorsignup/step2', 'TeacherPortal@Step2');
Route::post ('tutorsignup/step3', 'TeacherPortal@Step3');
Route::get ('tutorsignup/step3', 'TeacherPortal@Step3');

Route::post ('tutorsignup/step4', 'TeacherPortal@Step4');
Route::get ('tutorsignup/step4', 'TeacherPortal@Step4');
Route::post ('tutorsignup/step5', 'TeacherPortal@Step5');
Route::get ('tutorsignup/step5', 'TeacherPortal@Step5');
Route::post ('tutorsignup/step6', 'TeacherPortal@Step6');
Route::get ('tutorsignup/step6', 'TeacherPortal@Step6');
Route::get ('tutorsignup/finish', 'TeacherPortal@finish');
Route::post ('tutorsignup/finish', 'TeacherPortal@finish');
Route::get ('thankyou', 'TeacherPortal@thankyou');

Route::get ('classified', 'TuitionDetails@TuitionClassified');

Route::post ('send/email', 'Templates@SendEmailToAdmin');
Route::post ('send/email/location', 'Templates@SendEmailToAdminAboutLocation');


//For Registering Users
Route::get ('register/student', 'StudentController@showRegistrationForm');
Route::post ('register/student', 'StudentController@register');
//For Registering Users
Route::get ('registers', 'RegistrationController@showRegistrationForm');
Route::post ('registers', 'RegistrationController@register');
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegistrationController@confirm'
]);

Route::get ('registered/{email}', 'RegistrationController@showRegistrationLandingPage');


Route::get ('userrole', 'AdminController@userrole');

Route::group(['middleware' => ['role:admin,administrator']], function () {

    Route::post('admin/tuitions/summary', 'TuitionDetails@ShowSummary');
    Route::get('admin/teacher/profile/saved', 'TeacherDetail@LoadTeachersWithMessage');
    Route::post('admin/teacher/preferences', 'TeacherPreference@TeacherPreferenceSaveForAdmin');

    Route::post('admin/tuitions/followup/summary', 'TuitionDetails@ShowFollowUpSummary');
    Route::post('admin/tuitions/followup/shortview', 'TuitionDetails@ShowShortView');

    Route::get('admin/templates', 'Templates@LoadTemplates');
    Route::post('admin/templates', 'Templates@LoadTemplates');
    Route::get('admin/template/add', 'Templates@TemplateView');
    Route::post('admin/template/save', 'Templates@TemplateSave');
    Route::get ('admin/template/update/{id}', 'Templates@TemplateEditView');
    Route::get ('admin/template/delete/{id}', 'Templates@DeleteTemplate');
    Route::get('admin/tuitions/followup','TuitionDetails@TuitionFollowUp');
    Route::post('admin/tuitions/followup','TuitionDetails@TuitionFollowUp');
    Route::post('admin/tuition/quick/edit','TuitionDetails@TuitionFollowUpQuickEdit');
    Route::post('admin/tuition/quick/update','TuitionDetails@updateQuicEditTuition');

    Route::post('admin/tuition/starred','TuitionDetails@tuitionstarred');
    Route::post('admin/tuition/starsave','TuitionDetails@StarSave');
    Route::post('admin/tuition/broadcast/approval','TuitionDetails@TuitionBraodcastIsApprove');

    Route::post ('admin/teacher/phone/broadcast', 'Templates@BroadCastPhoneNumber');
    Route::post ('admin/teacher/email/broadcast', 'Templates@BroadCastEmail');
    Route::post ('admin/global/email/load', 'Templates@LoadEmailTemplate');
    Route::post ('admin/teacher/bulk/email', 'Templates@SendBulkEmail');

    Route::get('admin/customer/phone', 'TuitionDetails@GetCustomerPhone');
    Route::get('admin/global/notes', 'AdminController@GlobalNotePad');
    Route::post('admin/global/notes', 'AdminController@SaveGlobalNotePad');

    Route::get('admin/labels', 'Labels@LoadLabels');
    Route::post('admin/labels', 'Labels@LoadLabels');
    Route::get('admin/label/add', 'Labels@ClassView');
    Route::post('admin/label/save', 'Labels@LabelSave');
    Route::get ('admin/labels/update/{id}', 'Labels@LabelEditView');
    Route::get ('admin/labels/delete/{id}', 'Labels@DeleteLabel');

    Route::get('admin/institutes', 'Institutes@LoadInstitutes');
    Route::post('admin/institutes', 'Institutes@LoadInstitutes');
    Route::get('admin/institute/add', 'Institutes@InstituteView');
    Route::post('admin/institute/save', 'Institutes@InstituteSave');
    Route::get ('admin/institute/update/{id}', 'Institutes@InstituteEditView');
    Route::get ('admin/institute/delete/{id}', 'Institutes@DeleteInstitute');
    Route::post ('admin/teacher/institute/delete', 'TeacherDetail@DeletePreferredInstitute');
    Route::post ('admin/teacher/grade/delete', 'TeacherDetail@DeleteGradeSubjectsCategory');

    Route::get('admin/teacher/label/delete/{id}', 'Labels@DeleteTeacherLabel');
    Route::post('admin/tuition/label/delete', 'Labels@DeleteTuitionLabel');
    Route::post('admin/teacher/label/delete', 'TeacherDetail@DeleteTeacherLabel');

    Route::get('admin/notes', 'SpecialNotes@LoadNotes');
    Route::post('admin/notes', 'SpecialNotes@LoadNotes');
    Route::get('admin/note/add', 'SpecialNotes@NoteView');
    Route::post('admin/notes/save', 'SpecialNotes@NoteSave');
    Route::get('admin/notes/update/{id}', 'SpecialNotes@NoteEditView');
    Route::get('admin/notes/delete/{id}', 'SpecialNotes@DeleteNote');

    Route::get('admin/tuitions', 'TuitionDetails@LoadTuitions');
    Route::post('admin/tuitionsshortview', 'TuitionDetails@LoadTuitions');
    Route::post('admin/tuitionsapplicationview', 'TuitionDetails@LoadTuitions');
    Route::get('admin/tuitions/{id}', 'TuitionDetails@SendEmail');
    Route::post('admin/tuitions', 'TuitionDetails@LoadTuitions');
    Route::get('admin/tuitions/update/{id}', 'TuitionDetails@TuitionEditView');
    Route::get('admin/tuitions/delete/{id}', 'TuitionDetails@DeleteTuition');
    Route::get('admin/tuition/add', 'TuitionDetails@TuitionDetailView');

    Route::get('admin/tutiions/student', 'TuitionDetails@LoadTuitions');

    Route::get('admin/tuitions/copy/{id}', 'TuitionDetails@CopyTuition');

    Route::get('admin/teachers/matched/{id}', 'TuitionDetails@MatchedTeacher');
    Route::post('admin/teachers/matched/{id}', 'TuitionDetails@MatchedTeacher');
    Route::post('admin/fee/tuition', 'TuitionDetails@FeeCollected');
    Route::post('admin/assigned/tuition', 'TuitionDetails@AssignedPendingTuition');

    Route::get('admin/global/teachers/matched', 'TuitionDetails@GlobalMatchedTeacher');
    Route::post('admin/global/teachers/matched', 'TuitionDetails@GlobalMatchedTeacher');

    Route::post('admin/tuition/detail/class/subjects', 'TuitionDetails@LoadClassSubjects');
    Route::post('admin/tuition/detail', 'TuitionDetails@SaveTuition');
    Route::post('admin/tuitions/matched', 'TuitionDetails@ViewMatched');

    Route::post('admin/tuitions/bookmark', 'TuitionDetails@BookmarkTeacher');
    Route::post('admin/tuitions/unbookmark', 'TuitionDetails@UnBookmarkTeacher');
    Route::post('admin/add/bookmark/list', 'TuitionDetails@AddToBookmarkList');
    Route::post('admin/global/teachers/addbookmark', 'TuitionDetails@AddToBookmarkList');
    Route::post('admin/remove/bookmark/list', 'TuitionDetails@Unbookmak');

    Route::post('admin/tuitions/assign', 'TuitionDetails@AssignTuitionView');
    Route::post('admin/tuitions/assigned', 'TuitionDetails@AssignedTuition');
    Route::post('admin/tuitions/detail', 'TuitionDetails@TuitionDetails');
    Route::post('admin/tuitions/assign/bookmark/teacher', 'TuitionDetails@AssignBookmarkTeacher');
    Route::post('admin/tuitions/date', 'TuitionDetails@SetTuitionDateFiler');
    Route::post('admin/tuitions/mark/regular', 'TuitionDetails@MarkRegular');
    Route::get ('admin/tuition/short/view/{id}', 'TuitionDetails@TuitionShortView');
    Route::get ('admin/tuitions/shortview/{id}', 'TuitionDetails@TuitionShortView');

    Route::get('admin/tuition/subjects/{id}', 'TuitionDetails@TuitionSubjects');
    Route::get('admin/teacher/subjects/{id}', 'TeacherDetail@GradeSubjects');
    Route::get('admin/teacher/zones/{id}', 'TeacherDetail@ZoneLocations');

    Route::post('admin/teacher/unbookmark', 'TuitionDetails@Unbookmark');

    Route::post('admin/csm/delete', 'TuitionDetails@DeleteCSM');
    Route::post('admin/unassign/teacher', 'TuitionDetails@UnAssignTuition');

    Route::post ('admin/tuition/global', 'TuitionDetails@CreateGlobalList');
    Route::get ('admin/global/tuitions', 'TuitionDetails@GlobalList');
    Route::post ('admin/global/tuitions', 'TuitionDetails@GlobalList');
    Route::post ('admin/global/tuition/broadcast', 'TuitionDetails@GlobalListBroadCast');
    Route::post ('admin/delete/selected/global/tuitions', 'TuitionDetails@DeleteGlobalTuitions');
    Route::get ('admin/global/empty/tuitions', 'TuitionDetails@EmptyGlobalList');
    Route::post ('admin/global/tuition/delete', 'TuitionDetails@DeleteGlobalTuitions');
    Route::post ('admin/bulk/add/labels', 'TuitionDetails@AddTuitionLabels');
    Route::post ('admin/bulk/add/followuplabels', 'TuitionDetails@AddTuitionFollowupLabels');
    Route::post ('admin/tuition/add/labels', 'TuitionDetails@SaveSingleTuitionLabels');

    Route::post ('admin/tuition/update/status', 'TuitionDetails@UpdateTutiionStatus');
    Route::post ('admin/tuitions/update/status', 'TuitionDetails@UpdateFollowUpTuitionStatus');
    Route::post ('admin/tuition/add/fee', 'TuitionDetails@UpdateTuitionFinalFee');
    Route::post ('admin/tuition/add/date', 'TuitionDetails@UpdateTuitionStartDate');

    Route::get ('admin/broadcast/tuittions/sms', 'TuitionDetails@GloablSmsText');
    Route::post ('admin/broadcast/tuittions/sms', 'TuitionDetails@GloablSmsText');

    Route::post('admin/tuitions/page', 'TuitionDetails@LoadTuitionsWithPageSize');

    Route::get('admin/tuition/categories', 'TuitionCategory@LoadCategories');
    Route::post('admin/tuition/categories', 'TuitionCategory@LoadCategories');
    Route::get('admin/tuition/category/add', 'TuitionCategory@CategoryView');
    Route::post('admin/tuition/category/save', 'TuitionCategory@CategorySave');
    Route::get ('admin/tuition/category/update/{id}', 'TuitionCategory@CategoryEditView');
    Route::get ('admin/tuition/category/delete/{id}', 'TuitionCategory@DeleteCategory');

    Route::get ('admin', 'AdminController@showAdminPortal');
    Route::get('admin/teachers', 'TeacherDetail@LoadTeachers');
    Route::post('admin/teachers', 'TeacherDetail@LoadTeachers');
    Route::get('admin/teacher/{id}/{doc}', 'TeacherDetail@TeacherCNICDocs');

    Route::get('admin/subjects', 'Subjects@LoadSubjects');
    Route::post('admin/subjects', 'Subjects@LoadSubjects');
    Route::get('admin/subject/add', 'Subjects@SubjectView');
    Route::post('admin/subject/save', 'Subjects@SubjectSave');
    Route::get ('admin/subjects/update/{id}', 'Subjects@SubjectEditView');
    Route::get ('admin/subjects/delete/{id}', 'Subjects@DeleteSubject');

    Route::get('admin/classes', 'Classes@LoadClasses');
    Route::post('admin/classes', 'Classes@LoadClasses');
    Route::get('admin/class/add', 'Classes@ClassView');
    Route::post('admin/class/save', 'Classes@ClassSave');
    Route::get ('admin/classes/update/{id}', 'Classes@ClassEditView');
    Route::get ('admin/classes/delete/{id}', 'Classes@DeleteClass');

    Route::get('admin/locations', 'Locations@LoadLocations');
    Route::post('admin/locations', 'Locations@LoadLocations');
    Route::get('admin/location/add', 'Locations@LocationView');
    Route::post('admin/location/save', 'Locations@LocationSave');
    Route::get ('admin/locations/update/{id}', 'Locations@LocationEditView');
    Route::get ('admin/locations/delete/{id}', 'Locations@DeleteLocation');

    Route::get('admin/zones', 'ZoneLocations@LoadZones');
    Route::post('admin/zones', 'ZoneLocations@LoadZones');
    Route::get('admin/zone/add', 'ZoneLocations@ZoneView');
    Route::post('admin/zone/save', 'ZoneLocations@ZoneSave');
    Route::get ('admin/zone/update/{id}', 'ZoneLocations@ZoneEditView');
    Route::get ('admin/zone/delete/{id}', 'ZoneLocations@DeleteZone');

    Route::get('admin/tuition/status', 'TuitionStatus@LoadTuitionStatus');
    Route::post('admin/tuition/status', 'TuitionStatus@LoadTuitionStatus');
    Route::get('admin/tuition/status/add', 'TuitionStatus@StatusView');
    Route::post('admin/tuition/status/save', 'TuitionStatus@TuitionStatusSave');
    Route::get ('admin/tuition/status/update/{id}', 'TuitionStatus@StatusEditView');
    Route::get ('admin/tuition/status/delete/{id}', 'TuitionStatus@DeleteStatus');

    //update tutiion status from listing screen
    Route::post ('admin/tuition/status/update', 'TuitionDetails@UpdateTuitionStatus');

    Route::get('admin/assignstatus', 'AssignStatus@LoadTuitionAssignStatus');
    Route::post('admin/assignstatus', 'AssignStatus@LoadTuitionAssignStatus');
    Route::get('admin/assignstatus/add', 'AssignStatus@StatusView');
    Route::post('admin/assignstatus/save', 'AssignStatus@StatusSave');
    Route::get ('admin/assignstatus/update/{id}', 'AssignStatus@StatusEditView');
    Route::get ('admin/assignstatus/delete/{id}', 'AssignStatus@DeleteStatus');

    Route::get('admin/teachers/location/preference', 'LocationPreference@LoadPreferedLocation');
    Route::get('admin/teacher/location/preference/add/{id}', 'LocationPreference@LocationView');
    Route::post('admin/teacher/location/preference/save', 'LocationPreference@StatusPreferedLocations');
    Route::get ('admin/teacher/location/preference/delete/{id}', 'LocationPreference@DeleteZoneLocations');
    Route::post ('admin/teacher/location/preference/delete', 'LocationPreference@DeleteZoneLocations');

    Route::get('admin/bands', 'Bands@LoadBands');
    Route::post('admin/bands', 'Bands@LoadBands');
    Route::get('admin/band/add', 'Bands@BandView');
    Route::post('admin/band/save', 'Bands@BandSave');
    Route::get ('admin/bands/update/{id}', 'Bands@BandEditView');
    Route::get ('admin/bands/delete/{id}', 'Bands@DeleteBand');

    Route::get('admin/class/subject/mappings', 'Mappings@MappingsView');
    Route::post('admin/class/subject/load/mappings', 'Mappings@LoadMappingsForAdmin');
    Route::post('admin/class/subject/mappings', 'Mappings@MappingsSave');
    Route::get('admin/class/subject/mapping/delete/{id}', 'Mappings@DeleteMapping');

    Route::post('admin/gradesubject/mapping', 'Mappings@LoadGradeSubjects');

    Route::get('admin/referrers', 'ReferredBy@LoadReferrs');
    Route::post('admin/referrers', 'ReferredBy@LoadReferrs');
    Route::get('admin/referrer/add', 'ReferredBy@ReferrerView');
    Route::post('admin/referrer/save', 'ReferredBy@ReferrerSave');
    Route::get ('admin/referrer/update/{id}', 'ReferredBy@ReferrerEditView');
    Route::get ('admin/referrer/delete/{id}', 'ReferredBy@DeleteReferrer');

    Route::get('admin/application/status', 'ApplicationStatus@LoadApplicationStatus');
    Route::post('admin/application/status', 'ApplicationStatus@LoadApplicationStatus');
    Route::get('admin/application/status/add', 'ApplicationStatus@StatusView');
    Route::post('admin/application/status/save', 'ApplicationStatus@StatusSave');
    Route::get ('admin/application/status/update/{id}', 'ApplicationStatus@StatusEditView');
    Route::get ('admin/application/status/delete/{id}', 'ApplicationStatus@DeleteStatus');

    Route::post ('admin/global', 'TeacherDetail@CreateGlobalList');
    Route::post ('admin/global/teachers/teacherbb', 'TeacherDetail@CreateGlobalList');
    Route::get ('admin/global/email', 'TeacherDetail@BulkEmailView');
    Route::get ('admin/global/empty', 'Templates@EmptyGlobalList');

    Route::get ('admin/global/teachers', 'TeacherDetail@GlobalList');
    Route::post ('admin/global/teachers', 'TeacherDetail@GlobalList');
    Route::get ('admin/global/teachers/delete/{id}', 'TeacherDetail@DeleteGlobalTeacher');
    Route::post ('admin/delete/selected/global/teachers', 'TeacherDetail@DeleteSelectedGlobalTeachers');

    Route::post ('admin/global/teachers/phonelist', 'Templates@BroadCastPhoneNumber');

    Route::get('admin/teachers/add', 'TeacherDetail@TeacherProfile');
    Route::get ('admin/teachers/update/{id}', 'TeacherDetail@EidtTeacherProfile');
    Route::get ('admin/teachers/delete/{id}', 'TeacherDetail@DeleteTeacher');
    Route::post ('admin/load/province/cities', 'TeacherDetail@LoadProvinceCities');

    Route::get ('admin/teachers/short/view/{id}', 'TeacherDetail@LoadTeacherShorView');

    Route::get('admin/teacher/qualification/add/{id}', 'TeacherQualification@TeacherQualificationAddView');
    Route::get('admin/teacher/qualification/update/{id}', 'TeacherQualification@TeacherQualificationEditView');
    Route::get('admin/teacher/qualification/delete/{id}', 'TeacherQualification@TeacherQualificationDelete');
    Route::get('admin/teacher/qualification/{id}/{doc}', 'TeacherQualification@TeacherQualificationDisplayDocs');

    Route::post('admin/teacher/qualification/delete', 'TeacherQualification@DeleteQualification');

    Route::get('admin/teacher/experience/add/{id}', 'TeacherExperience@TeacherExperienceAddView');
    Route::get('admin/teacher/experience/update/{id}', 'TeacherExperience@TeacherExperienceEditView');
    Route::get('admin/teacher/experience/delete/{id}', 'TeacherExperience@TeacherExperienceDelete');
    Route::get('admin/teacher/experience/{id}/{doc}', 'TeacherExperience@TeacherExperienceDisplayDocs');

    Route::get('admin/teacher/preference/add/{id}', 'TeacherPreference@TeacherPreferenceAddView');
    Route::get('admin/teacher/preference/update/{id}', 'TeacherPreference@TeacherPreferenceEditView');
    Route::get('admin/teacher/preference/delete/{id}', 'TeacherPreference@TeacherPreferenceDelete');
    Route::post('admin/subject/preference/delete', 'TeacherPreference@DeleteSubjectPreference');

    Route::get('admin/teacher/reference/add/{id}', 'TeacherReference@TeacherReferenceAddView');
    Route::get('admin/teacher/reference/update/{id}', 'TeacherReference@TeacherReferenceEditView');
    Route::get('admin/teacher/reference/delete/{id}', 'TeacherReference@TeacherReferenceDelete');

    Route::get('admin/teacher/tuitionhistory/add/{id}', 'TeacherReference@TeacherTuitionHistoryAddView');
    Route::get('admin/teacher/tuitionhistory/update/{id}', 'TeacherReference@TeacherReferenceEditView');
    Route::get('admin/teacher/tuitionhistory/delete/{id}', 'TeacherReference@TeacherReferenceDelete');
    Route::post('admin/tuition/history/reason', 'TeacherDetail@TuitionHistoryReason');

    Route::get('tutor-experience','TeacherDetail@showTeacherExperience');
    Route::get('tutor-experience/{id}','TeacherDetail@EditTeacherExperience');

    Route::post('teacher-post', ['as'=>'teacher-post','uses'=>'AdminController@teacherPost']);
    Route::post ('admin/teacher/qualification/', 'TeacherQualification@TeacherQualificationSave');
    Route::post ('admin/teacher/experience/', 'TeacherExperience@TeacherExperienceSave');
    Route::post ('admin/teacher/preference/', 'TeacherPreference@TeacherPreferenceSave');
    Route::post ('admin/teacher/reference/', 'TeacherReference@TeacherReferenceSave');
    Route::post('experience','TeacherDetail@AddExperience');
    Route::post('admin/qualification','TeacherDetail@AddExperience');
    Route::post('admin/detail','TeacherDetail@detail');

    Route::post('admin/download', 'TeacherDetail@getQualificationDocument');
    Route::post('admin/experience/docs', 'TeacherDetail@getExperienceDocument');
    Route::post('admin/doc/download', 'TeacherDetail@getDownLoad');

    Route::post('admin/teacher/application/status', 'TuitionDetails@ChangeApplicationStatus');
    Route::post('admin/application/update/status', 'TuitionDetails@ApplyTuitionStatus');

    Route::get('admin/smessage','Subjects@AdminSjubets');
    Route::get('admin/cmessage','Classes@AdminClasses');
    Route::get('admin/zmessage','ZoneLocations@AdminZones');
    Route::get('admin/lmessage','Locations@AdminLocations');
    Route::get('admin/bmessage','Bands@AdminBands');
    Route::get('admin/tsmessage','TuitionStatus@AdminTuitionStatus');
    Route::get('admin/tcmessage','TuitionCategory@AdminTuitionCategories');
    Route::get('admin/ncmessage','SpecialNotes@AdminNotes');
    Route::get('admin/labelmessage','Labels@AdminLabels');
    Route::get('admin/imessage','Institutes@AdminInstitutes');
    Route::get('admin/rmessage','ReferredBy@AdminReferrers');
    Route::get('admin/atmessage','TuitionDetails@AdminTuitions');
    Route::get('admin/statusmessage','ApplicationStatus@AdminApplicationStatus');

    Route::get('admin/teacher/details','TeacherDetail@TeacherDetails');

});

Route::group(['middleware' => ['role:student,student postal']], function () {

    Route::get ('search-teacher', 'StudentController@showStudentPortal')->name('studenthome');
    Route::post ('search-teacher', 'StudentController@showStudentPortal');
    Route::get ('teacher-details/{id}', 'StudentController@showTeacherDetails');
    Route::get('search-teacher/subjects/{id}', 'StudentController@GradeSubjects');
    Route::get('search-teacher/zones/{id}', 'StudentController@ZoneLocations');
    Route::post('teacher-details/tutor/request', 'StudentController@TutorRequestForm');
    Route::post('teacher-details/tutor/applied/request', 'StudentController@TutorRequested');
    Route::get('applied/tuitions', 'StudentController@AppliedTuitionView');
    Route::get('applied/student/tuitions', 'StudentController@AppliedTuition');
    Route::get('call/academy', 'StudentController@CallAcademy');
    Route::post('call/save', 'StudentController@SaveCallTuition');

});

Route::group(['middleware' => ['role:teacher,teacher postal']], function () {

    Route::get('teacher', 'TeacherPortal@showTeacherPortal');
    Route::post('download', 'TeacherPortal@getDownload');

    Route::post ('admin/send/email', 'Templates@SendEmailToAdmin');
    Route::post ('admin/send/email/location', 'Templates@SendEmailToAdminAboutLocation');

    Route::get('qualifications', 'TeacherQualification@LoadQualifications');
    Route::get('teacher/qualifications', 'TeacherPortal@TeacherQualificationAddView');
    Route::get('qualifications/update/{id}', 'TeacherPortal@TeacherQualificationEditView');
    Route::get('qualification/delete/{id}', 'TeacherQualification@TeacherQualificationDelete');
    Route::post ('teacher/qualifications', 'TeacherQualification@TeacherQualificationSave');

    Route::get('experiences', 'TeacherPortal@LoadExperiences');
    Route::get('teacher/experiences', 'TeacherPortal@TeacherExperienceAddView');
    Route::get('experience/update/{id}', 'TeacherPortal@TeacherExperienceEditView');
    Route::get('experience/delete/{id}', 'TeacherExperience@TeacherExperienceDelete');
    Route::post('teacher/experiences', 'TeacherExperience@TeacherExperienceSave');

    Route::get('preferences', 'TeacherPortal@LoadSubjectPreferences');
    Route::get('preference/delete/{id}', 'TeacherPortal@TeacherPreferenceDelete');
    Route::get('teacher/preferences', 'TeacherPortal@TeacherPreferenceAddView');
    Route::post('teacher/preferences', 'TeacherPreference@TeacherPreferenceSave');

    Route::get('references', 'TeacherPortal@LoadReferences');
    Route::get('teacher/references', 'TeacherPortal@TeacherReferenceAddView');
    Route::get('reference/update/{id}', 'TeacherPortal@TeacherReferenceEditView');
    Route::get('reference/delete/{id}', 'TeacherReference@TeacherReferenceDelete');
    Route::post('teacher/references', 'TeacherReference@TeacherReferenceSave');

    Route::get('tuitions', 'TeacherPortal@LoadTuitions');
    Route::get('tuition/details', 'TeacherPortal@LoadTuitionDetails');

    Route::get('locations', 'TeacherPortal@TeacherLocationAddView');
    Route::post('teacher/location/preference/save', 'LocationPreference@StatusPreferedLocations');
    Route::get ('location/delete/{id}', 'LocationPreference@DeleteLocations');

    Route::post('class/subject/load/mappings', 'Mappings@LoadMappings');
    Route::get ('applicationshortview/{id}', 'TuitionDetails@TuitionShortView');
    Route::get ('historyshortview/{id}', 'TuitionDetails@TuitionShortView');

    Route::get('tuition/location', 'TeacherPortal@LoadTuitionByLocation');
    Route::post('tuition/location', 'TeacherPortal@LoadTuitionByLocation');
    Route::get('tuition/gender', 'TeacherPortal@LoadTuitionByGender');
    Route::post('tuition/gender', 'TeacherPortal@LoadTuitionByGender');
    Route::get('tuition/matched/{id}', 'TeacherPortal@LoadTuitionByCategory');
    Route::get('tuition/subject', 'TeacherPortal@LoadTuitionByClassSubject');
    Route::post('tuition/subject', 'TeacherPortal@LoadTuitionByClassSubject');
    Route::get('tuition/class', 'TeacherPortal@LoadTuitionByClassSubject');
    Route::post('tuition/class', 'TeacherPortal@LoadTuitionByClassSubject');

    Route::post('tuition/automatched', 'TeacherPortal@LoadTuitionBYAutoMatched');
    Route::get('tuition/automatched', 'TeacherPortal@LoadTuitionBYAutoMatched');
    Route::post('automatched', 'TeacherPortal@LoadTuitionBYAutoMatched');

    Route::get('tuition/search', 'TeacherPortal@LoadTuitionSearch');
    Route::post('tuition/search', 'TeacherPortal@LoadTuitionSearch');

    Route::post('tuition/date', 'TuitionDetails@SetTuitionDateFiler');

    Route::post('saveprofile', 'TeacherPortal@saveTeacherProfile');
    Route::post('viewdetail','TeacherPortal@TuitionDetail');

    Route::post('teacher/tuition_applied','TeacherPortal@TuitionApplied');

    Route::get('applications','TeacherPortal@TuitionApplications');
    Route::post('applications','TeacherPortal@TuitionApplications');
    Route::get('applications/delete/{id}','TeacherPortal@DeleteApplicationTuition');

    Route::post('load/province/cities','TeacherDetail@LoadProvinceCities');
    Route::get('tuition/categories','TeacherPortal@TuitionCategoriesView');
    Route::get('tuition/teacher/categories','TeacherPortal@TuitionCategoriesView');
    Route::post('teacher/tuition/categories/save','TeacherPortal@SaveTuitionCategories');
    Route::get('tuition/categories/delete/{id}','TeacherPortal@DeleteTuitionCategories');

    Route::get('prefered/institutes','TeacherPortal@PreferedInstituteView');
    Route::get('prefered/institutes/add','TeacherPortal@PreferedInstituteView');
    Route::post('prefered/institutes/save','TeacherPortal@SavePreferedInstitutes');
    Route::get('prefered/institute/delete/{id}','TeacherPortal@DeletePreferedInstitute');

    Route::get('qmessage', 'TeacherQualification@SetQualificationMessage');
    Route::get('emessage', 'TeacherExperience@SetExperienceMessage');
    Route::get('zone/locations/{id}', 'TeacherPortal@ZoneLocations');
    Route::get('class/subjects/{id}', 'TeacherPortal@GradeSubjects');


});


?>