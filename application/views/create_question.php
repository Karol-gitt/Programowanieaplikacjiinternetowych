<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Utwórz pytanie</h1>
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
                <label for="question_category">Kategoria:</label>
                <?php echo form_dropdown("question_category", $question_category, null, 'id="question_category" class="form-control chosen"'); ?>
            </div> 

            <div class="form-group">
                <label for="<?php echo $question_name['id'] ?>">Nazwa pytania:</label>
                <?php echo form_input($question_name); ?>
            </div>     

            <div class="form-group">
                <label for="<?php echo $question_content['id'] ?>">Treść pytania:</label>
                <?php echo form_textarea($question_content); ?>
            </div>   

            <div class="form-group">
                <label for="<?php echo $question_rate['id'] ?>">Punktacja:</label>
                <?php echo form_input($question_rate); ?>
            </div> 

            <?php
            $answer = 1;
            foreach ($question_answers as $question_answer) {
                ?>

                <div class="form-group">
                    <label for="<?php echo $question_answer['id'] ?>">Odpowiedź <?php echo $answer; ?>:</label>
                    <?php echo form_input($question_answer); ?>
                </div>

                <?php
                $answer++;
            }
            ?>

            <?php
            if ($question_type == 'test-multi') {
                ?>

                <div class="form-group">
                    <label for="question_rightanswer">Prawidłowe odpowiedzi:</label>
                    <?php echo form_multiselect("question_rightanswer[]", $question_rightanswer, null, 'id="question_rightanswer" class="form-control chosen"'); ?>
                </div> 
                
                <div class="form-group">
                    <label for="<?php echo $random_answers['id'] ?>">Odpowiedzi w kolejności losowej:</label>&nbsp;&nbsp;
                    <?php echo form_checkbox($random_answers); ?>
                </div>

                <?php
            } else if ($question_type == 'test-one') {
                ?>
                <div class="form-group">
                    <label for="question_rightanswer">Prawidłowa odpowiedź:</label>
                    <?php echo form_dropdown("question_rightanswer", $question_rightanswer, null, 'id="question_rightanswer" class="form-control chosen"'); ?>
                </div> 
                
                <div class="form-group">
                    <label for="<?php echo $random_answers['id'] ?>">Odpowiedzi w kolejności losowej:</label>&nbsp;&nbsp;
                    <?php echo form_checkbox($random_answers); ?>
                </div>
                <?php
            }
            ?>

            <p class="pull-left">
                <input type="submit" name="add_answer" class="btn btn-default" value="Dodaj pole odpowiedzi" onclick="skipClientValidation = true" />
            </p>

            <p class="pull-right">
                <a href="questions_base/questions" class="btn btn-default">Powrót</a>
                <?php echo form_submit('submit', 'Utwórz pytanie', 'class="btn btn-primary"'); ?>                
            </p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('question_content');
    });
</script>