<?php if(!$flag): ?>
<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> Alert!</h4>
            All Subjects are already mapped for this grdae category!
        </div>
    </div>
</div>
<?php endif; ?>

<div class="form-group mapping">
    <label for="gender_id" class="col-sm-2 control-label">Subjects:<span
                style="color: red">*</span></label>
    <div class="col-sm-10">
        <?php foreach($mappings as $mapping): ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="subjects[]" id="<?php echo $mapping->name ?>" class="subjects"
                       value="<?php echo $mapping->id ?>" <?php echo isset($mapping->class_subject_mapping_id)?"checked":""; ?>>
                <?php echo $mapping->name ?></label>
        </div>
        <?php endforeach ?>
    </div>
</div>

<div class="box-footer">
    <a href="{{url('admin/teachers')}}" class="btn btn-warning pull-right">
        <i class="fa fa-w fa-home"></i> Back
    </a>
    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;" value="save" name="save">
        <i class="fa fa-fw fa-save" ></i> Save</button>
</div>

<script>
    //uncheck subject checkbox
    $('.subjects').click(function(){

        id = this.id
        if($(this).is(':checked')){
            $('#'+id).attr('checked', true);
        } else {

            $('#'+id).attr('checked', false);
        }
    });
</script>