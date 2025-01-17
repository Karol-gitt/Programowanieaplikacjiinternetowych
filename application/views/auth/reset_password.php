<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo lang('reset_password_heading');?></h1>
            <p><?php echo lang('reset_password_subheading');?></p>
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
                <label for="<?php echo $new_password['id'] ?>"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
                 <?php echo form_input($new_password); ?>
            </div> 
                
            <div class="form-group">
                <label for="<?php echo $new_password_confirm['id'] ?>"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
                <?php echo form_input($new_password_confirm); ?>
            </div>

            <?php echo form_input($user_id);?>
            <?php echo form_hidden($csrf); ?>
                
            <p>
                <?php echo form_submit('submit', lang('reset_password_submit_btn'), 'class="btn btn-primary btn-block"'); ?>
            </p>
            
            

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<!--
<h1><?php echo lang('reset_password_heading');?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open('auth/reset_password/' . $code);?>

	<p>
		<label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
		<?php echo form_input($new_password);?>
	</p>

	<p>
		<?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
		<?php echo form_input($new_password_confirm);?>
	</p>

	<?php echo form_input($user_id);?>
	<?php echo form_hidden($csrf); ?>

	<p><?php echo form_submit('submit', lang('reset_password_submit_btn'));?></p>

<?php echo form_close();?>
-->