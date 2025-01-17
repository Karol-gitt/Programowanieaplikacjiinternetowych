<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo lang('create_user_heading');?></h1>
            <p><?php echo lang('create_user_subheading');?></p>
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

            <?php echo form_open("auth/create_user");?>

            <div class="form-group">
                <label for="<?php echo $first_name['id'] ?>"><?php echo lang('create_user_fname_label', 'first_name'); ?></label>
                <?php echo form_input($first_name); ?>
            </div>

            <div class="form-group">
                <label for="<?php echo $last_name['id'] ?>"><?php echo lang('create_user_lname_label', 'last_name'); ?></label>
                <?php echo form_input($last_name); ?>
            </div>
                
            <div class="form-group">
                <label for="<?php echo $email['id'] ?>"><?php echo lang('create_user_email_label', 'email');?></label>
                <?php echo form_input($email); ?>
            </div>

            <div class="form-group">
                <label for="<?php echo $password['id'] ?>"><?php echo lang('create_user_password_label', 'password'); ?></label>
                <?php echo form_input($password); ?>
            </div>    

            <div class="form-group">
                <label for="<?php echo $password_confirm['id'] ?>"><?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?></label>
                <?php echo form_input($password_confirm); ?>
            </div>


            <p>
                <?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary btn-block"'); ?>
                <a href="auth" class="btn btn-default btn-block">Powrót</a>
            </p>

            <?php echo form_close(); ?>
            
        </div>
    </div>
</div>
