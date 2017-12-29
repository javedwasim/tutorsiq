<div class="box box-primary">
    <a href="#">
        <div class="box-header with-border" data-widget="collapse">
            <i class="fa fa-minus pull-right" style="font-size:12px;
        margin-top: 5px;"></i>
            <h1 class="box-title">Tuitions List</h1>
        </div>
    </a>
    <div class="box-body" style="padding: 0px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header" style="padding-bottom: 5px">

                        <form class="pull-right form-group" method="post" action="{{ url('admin/tuition/global') }}" id="globalTuitions">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="ids" id="ids" value="">
                            <button type="button" class="btn btn-primary pull-right broadcast-selected" >
                                <i class="fa fa-fw fa-volume-up"></i>Add to BroadCast List</button>&nbsp;&nbsp;

                        </form>

                        <form class="pull-right form-group" method="post"  id="tuitionsSummary">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="summaryids" id="summaryids" value="">
                            <button type="submit" class="btn btn-primary pull-right" >
                                <i class="fa fa-fw fa-list-ul"></i>Show Summary</button>&nbsp;&nbsp;

                        </form>

                        <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="changeStatusbtn" style="margin-left: 10px;">
                            <i class="fa fa-exchange fa-lg"></i> Update  Status
                        </a>

                        <a href="{{ url('#') }}" class="btn btn-primary pull-right" id="changeLabelbtn" style="margin-left: 10px;">
                            <i class="fa fa-stack-exchange fa-lg"></i> Change Labels
                        </a>

                        <a href="{{ url('admin/tuition/add') }}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New
                        </a>

                        <span class="text" style="font-weight: 700;">Please select below <small class="label label-primary"><i class="fa fa fa-fw fa-reorder"></i> </small>
                            &nbsp;to view tuition details
                        </span>

                    </div>

                    <?php /* ?>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <?php */ ?>
                        <div class="box-body"  style="padding-top: 5px">

                            <input type="checkbox" class="minimal select_all" name="select_all" id="select_all" value="">
                            Select All <span class="text" style="font-weight: 700; padding-left: 5px">Number of tuitions selected:<small class="label label-warning badge slelected-tuitions"></small></span>

                            <table id="example1" class="table table-bordered table-striped responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr style="background-color: #367fa9; color: #fefefe;">
                                    <th>&nbsp;</th>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Contact Person</th>
                                    <th>Grade&Subjects</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($tuitions as $tuition): ?>
                                <tr>
                                    <td>
                                        <div id="element1">
                                            <a class="btn  tuition_detail"  id="<?php echo $tuition->id;  ?>" title="View Detail">
                                            <span class="label label-primary">
                                                 <i class="fa fa-fw fa-reorder" style="font-size: 10px;"></i>
                                            </span>
                                            </a>
                                        </div>
                                    </td>

                                    <td><?php echo $tuition->id; ?></td>

                                    <td>
                                        <div id="element1">
                                            <input type="checkbox" class="minimal selected-count" name="globalTuition[]" value="<?php echo $tuition->id; ?>">
                                            <?php echo $tuition->tuition_code; ?>
                                        </div>
                                        <div id="element2">
                                            <a href="javascript:void(0);" class="volume text-red tuition-global-list" title="Add to Global Teachers List" id="<?php echo $tuition->id; ?>">
                                                <i class="fa fa-fw fa-bullhorn"></i>
                                            </a>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if($tuition->contact_person != ''): ?>
                                            <div><?php echo $tuition->contact_person; ?></div>
                                        <?php endif; ?>
                                        <?php if($tuition->contact_no != ''): ?>
                                            <div><span class=""><a href="tel:<?php echo $tuition->contact_no; ?>"><?php echo $tuition->contact_no ?></a></span></div>
                                        <?php endif; ?>
                                        <?php if($tuition->contact_no != ''): ?>
                                            <div><span class=""><a href="tel:<?php echo $tuition->contact_no2; ?>"><?php echo $tuition->contact_no2 ?></a></span></div>
                                        <?php endif; ?>


                                    </td>

                                    <td><?php echo $tuition->subjects; ?></td>

                                    <td>

                                        <?php if(isset($tuition->tuition_status) ): ?>

                                        <div id="element1" >
                                            <span type="button" class="btn tuitio-status <?php echo $tuition->id; ?>" style="background:<?php echo $tuition->color; ?>;}"> &nbsp;
                                            </span>
                                        </div>
                                        <div id="element2" class="tuition-status-size">
                                            <select class="form-control select2 tuition_status_p"  id="tuition_status_p" name="tuition_status_p"
                                                    data-placeholder="Select Status">
                                                <option value=""></option>
                                                <?php foreach($assign_status as $status) { ?>
                                                <option value="<?php echo $status->id."|".$tuition->id."|".$status->color; ?>"
                                                <?php if(isset($tuition->tuition_status_id) && ($status->id ==  $tuition->tuition_status_id) ) echo "selected"; ?> ><?php echo $status->name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <?php endif; ?>

                                    </td>
                                    <td><?php echo $tuition->location_name; ?></td>

                                    <td>
                                        <div class="btn-group open tuittion-actions">
                                            <button type="button" class="btn btn btn-default">Action</button>
                                            <button type="button" class="btn btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">

                                                <li><a class="btn  edit-btn" href="tuitions/copy/<?php echo $tuition->id; ?>" title="Copy Tuittion" style="padding: 0 0;">
                                                    <span class="label label-primary">
                                                        <i class="fa fa-fw fa-copy" style="font-size: 10px;"></i>
                                                    </span>
                                                    </a></li>

                                                <li><a class="btn send-btn" title="View Matched" id="<?php echo $tuition->id;  ?>" style="padding: 0 0;">
                                                    <span class="label label-primary">
                                                        <i class="fa fa-fw fa-bookmark" style="font-size: 10px;"></i>
                                                    </span>
                                                    </a></li>
                                                <li><a class="btn short-view" title="Short View" id="<?php echo $tuition->id;  ?>" style="padding: 0 0;">
                                                        <span class="label label-primary">
                                                            <i class="fa fa-fw fa-eye" style="font-size: 10px;"></i>
                                                        </span>

                                                    </a></li>

                                                <li><a class="btn  edit-btn" href="tuitions/update/<?php echo $tuition->id; ?>" title="Edit" style="padding: 0 0;">
                                                    <span class="label label-success">
                                                        <i class="fa fa-fw fa-edit" style="font-size: 10px;"></i>
                                                    </span>
                                                    </a></li>

                                                <li><a class="btn  del-btn" href="tuitions/delete/<?php echo $tuition->id; ?>" title="Delete" style="padding: 0 0;">
                                                    <span class="label label-danger">
                                                        <i class="fa fa-fw fa-trash-o" style="font-size: 10px;"></i>
                                                    </span>
                                                    </a></li>

                                            </ul>

                                        </div>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="box-footer" style="padding: 0px;">
                                <div style="display:inline-block;">
                                    <select class="form-control" id="page_size" name="page_size" style="width: 110px;">
                                        <option value="50"<?php if(isset($pagesize) && $pagesize==50) echo "selected"; ?>>50</option>
                                        <option value="100"<?php if(isset($pagesize) && $pagesize==100) echo "selected"; ?>>100</option>
                                        <option value="150"<?php if(isset($pagesize) && $pagesize==150) echo "selected"; ?>>150</option>
                                        <option value="200"<?php if(isset($pagesize) && $pagesize==200) echo "selected"; ?>>200</option>
                                    </select>
                                </div>
                                <?php echo $tuitions->render(); ?>
                                <div class="" style="display:inline-block;">Showing
                                    <?php echo isset($offset) ? $offset:''; ?> to
                                    <?php echo isset($perpage_record) ? $perpage_record:''; ?> of
                                    <?php echo $count_tuitions; ?> entries
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.partials.modal')
