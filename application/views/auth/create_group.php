<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo lang('create_group_heading');?></h1>
            <p><?php echo lang('create_group_subheading');?></p>
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
                <label for="<?php echo $group_name['id'] ?>"><?php echo lang('create_group_name_label', 'group_name'); ?></label>
                 <?php echo form_input($group_name); ?>
            </div> 
                
            <div class="form-group">
                <label for="<?php echo $description['id'] ?>"><?php echo lang('create_group_desc_label', 'description');?></label>
                <?php echo form_input($description); ?>
            </div>

            <p>
                <?php echo form_submit('submit', lang('create_group_submit_btn'), 'class="btn btn-primary btn-block"'); ?>
                <a href="auth" class="btn btn-default btn-block">Powrót</a>
            </p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>