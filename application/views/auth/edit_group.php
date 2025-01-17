<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo lang('edit_group_heading');?></h1>
            <p><?php echo lang('edit_group_subheading');?></p>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4 col-md-offset-4">

            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessageStatic" class="alert alert-info text-center"><?php echo $message; ?></div>
            <?php } ?>
                
            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>   

            <?php echo form_open(current_url()); ?>

            <div class="form-group">
                <label for="<?php echo $group_name['id'] ?>"><?php echo lang('edit_group_name_label', 'group_name'); ?></label>
                 <?php echo form_input($group_name); ?>
            </div> 
                
            <div class="form-group">
                <label for="<?php echo $group_description['id'] ?>"><?php echo lang('edit_group_desc_label', 'description'); ?></label>
                <?php echo form_input($group_description); ?>
            </div>

            <p>
                <?php echo form_submit('submit', lang('edit_group_submit_btn'), 'class="btn btn-primary btn-block"'); ?>
                <a href="auth/<?php echo $parent; ?>" class="btn btn-default btn-block">Powrót</a>
                <?php echo '<a href="javascript:confirmDelete('.$group->id.')" class="btn btn-danger btn-block">Usuń</a>' ;?>
            </p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>
    
<div class="modal fade" id="confirmDelete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Usuń grupę</h4>
      </div>
      <div class="modal-body">
        <p>Czy na pewno usunąć wybraną grupę?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
        <button type="button" class="btn btn-danger" onclick="deleteGroup()">Tak</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->