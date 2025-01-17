<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Edytuj test</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-8 col-md-offset-2">

            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessageStatic" class="alert alert-info text-center"><?php echo $message; ?></div>
            <?php } ?>

            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>   

            <?php
            echo form_open(current_url());
            //echo form_hidden($question_type);
            ?>

            <div class="form-group">
                <label for="<?php echo $test_title['id'] ?>">Nazwa testu:</label>
                <?php echo form_input($test_title); ?>
            </div> 

            <div class="form-group">
                <label for="<?php echo $test_description['id'] ?>">Opis testu:</label>
                <?php echo form_textarea($test_description); ?>
            </div>     

            <label for="<?php echo $test_access_code['id'] ?>">Kod dostępu do testu:</label>
            <div class="input-group">                
                <?php echo form_input($test_access_code); ?>
                <span class="input-group-btn">
                    <button id="generate_code" class="btn btn-default" type="button">Generuj kod</button>
                </span>
            </div>
            <br />
            
            <div class="form-group">
                <label for="<?php echo $time['id'] ?>">Czas na rozwiązanie (min, 0 - brak limitu):</label>
                <?php echo form_input($time); ?>
            </div>
            
            

<!--            <div class="form-group">
                <label for="<?php echo $test_access_code['id'] ?>">Kod dostępu do testu:</label>
                <?php echo form_input($test_access_code); ?>
            </div>   -->

            <div class="form-group">
                <label for="<?php echo $test_visible_result['id'] ?>">Wynik widoczny dla rozwiązującego?:</label>&nbsp;&nbsp;
                <?php echo form_checkbox($test_visible_result); ?>
            </div> 

            <p class="pull-right">
                <a href="tests" class="btn btn-default">Powrót</a>
                <?php echo form_submit('submit', 'Zapisz zmiany', 'class="btn btn-primary"'); ?>                
            </p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('test_description');
    });
</script>