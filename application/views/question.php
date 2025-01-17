<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Podgląd pytania</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-8 col-md-offset-2">

            <div class="question">
                <?php echo $question->question; ?>
            </div>

            <div class="answers" style="margin-top: 50px;">
                <?php
                if ($question->type == 'short') {
                    ?>
                    <div class="form-group">
                        <label for="answer">Twoja odpowiedź:</label>
                        <input type="text" id="answer" class="form-control"/>
                    </div>

                    <?php
                } else if ($question->type == 'test-one') {
                    $id = 0;
                    foreach ($answers as $answer) {
                        ?>
                        <div class="radio" style="margin-top: 10px;">
                            <label class="radio-custom" data-initialize="radio">
                                <input type="radio" name="answer" id="answer<?php echo $id; ?>" value="<?php echo $answer; ?>">
                                <?php echo $answer; ?>
                            </label>
                        </div>
                        <?php
                        $id++;
                    }
                } else if ($question->type == 'test-multi') {
                    $id = 0;
                    foreach ($answers as $answer) {
                        ?>
                        <div class="checkbox" id="answer<?php echo $id; ?>" style="margin-top: 10px;">
                            <label class="checkbox-custom" data-initialize="checkbox">                                
                                <input type="checkbox" name="answer[]" value="<?php echo $answer; ?>">
                                <?php echo $answer; ?>
                            </label>
                        </div>
                        <?php
                        $id++;
                    }
                }
                ?>
            </div>

            <div class="text-center" style="margin-top: 100px;">
                <?php
                if ($back != '') {
                    ?>
                    <a href="tests/test/<?php echo $back; ?>" class="btn btn-default">Powrót</a>
                    <?php
                } else {
                    ?>
                    <a href="questions_base/questions" class="btn btn-default">Powrót</a>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>

</div>