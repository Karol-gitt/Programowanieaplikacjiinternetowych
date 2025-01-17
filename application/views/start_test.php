<div class="container">   
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo $test->title; ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <?php echo $test->description; ?>
        </div>
    </div>
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4 col-md-offset-4">
            
            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessageStatic" class="alert alert-info text-center"><?php echo $message;?></div>
            <?php } ?>
                
            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>
            
            <?php echo form_open(current_url()); ?>

            <div class="form-group">
                <label for="<?php echo $student['id'] ?>">Podaj numer z dziennika:</label>
                 <?php echo form_input($student); ?>
            </div> 
                
            <p>
                <?php echo form_submit('submit', 'Rozpocznij test', 'class="btn btn-primary btn-block"'); ?>
                <a href="welcome" class="btn btn-default btn-block">Anuluj</a>
            </p>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>