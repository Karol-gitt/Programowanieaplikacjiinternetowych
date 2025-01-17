<div class="container">

    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo lang('forgot_password_heading'); ?></h1>
            <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>
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
                
            <?php if ($this->session->flashdata('error_message') != '') { ?>  
                <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $this->session->flashdata('error_message'); ?></div>
            <?php } ?> 

            <?php echo form_open(current_url()); ?>

            <div class="form-group">
                <label for="email"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label); ?></label>
               <?php echo form_input($email); ?>
            </div> 
                

            <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-primary btn-block"'); ?></p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>


