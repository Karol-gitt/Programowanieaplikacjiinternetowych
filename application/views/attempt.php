<div class="container">   
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo $test->title; ?></h1>
        </div>
    </div>

        <div class="row" style="margin-top: 50px;">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h4 class="text-success"><?php echo $student; ?></h4>
                Wynik: <b><?php echo $attempt_summary ?></b> z możliwych do uzyskania <?php echo $max_attempt_summary; ?> (<b><?php echo $attempt_summary_percentage; ?>%</b>)
            </div>
        </div>

        <div class="row" style="margin-top: 50px;">
            <div class="col-md-10 col-md-offset-1">           

                <div>
                    <?php
                    $liczba = 1;
                    if ($this->uri->rsegment(4) != NULL) {
                        $liczba = $this->uri->rsegment(4) + 1;
                    }
                    ?>

                    <h5><b>Pytanie <?php echo $liczba; ?> (Liczba punktów: <?php echo $question_rate; ?>)</b></h5>
                </div>

                <div class="question" style="margin-top: 50px;">
                    <?php echo $question->question; ?>
                </div>           

                <div class="answers" style="margin-top: 50px;">
                    <?php
                    if ($question->type == 'short') {
                        ?>
                        <div class="form-group <?php echo $success; ?>">
                            <label for="answer">Twoja odpowiedź:</label>
                            <input type="text" disabled="disabled" name="answer" id="answer" class="form-control" <?php if (isset($your_answer)) echo 'value="' . $your_answer . '"'; ?>/>
                        </div>

                        <div>Prawidłowe odpowiedzi: <span class="text-success"><?php
                                $i = 0;
                                foreach ($right_answers as $ranswer) {
                                    if ($i == 0) {
                                        echo $ranswer;
                                    } else {
                                        echo ", " . $ranswer;
                                    }
                                    $i++;
                                }
                                ?></span></div>
                        <?php
                    } else if ($question->type == 'test-one') {
                        $id = 0;
                        foreach ($answers as $answer) {
                            ?>
                            <div class="radio <?php if (isset($your_answer) && $your_answer == $answer){ echo 'checked';} if(isset($your_answer) && $right_answers == $answer){ echo ' text-success';}else if(isset($your_answer) && $your_answer == $answer && $right_answers != $answer){ echo ' text-danger';}else if($right_answers == $answer){ echo ' text-success';}?> " style="margin-top: 10px;">
                                <label class="radio-custom disabled" data-initialize="radio">
                                    <input type="radio" disabled="disabled" name="answer" id="answer<?php echo $id; ?>" value="<?php echo $answer; ?>" <?php if (isset($your_answer) && $your_answer == $answer) echo 'checked="checked"'; ?>>
                                    <?php echo $answer; ?>
                                </label>
                            </div>
                            <?php
                            if (isset($your_answer) && $your_answer == $answer) {
                                ?>

                                <?php
                            }

                            $id++;
                        }
                    } else if ($question->type == 'test-multi') {
                        $id = 0;
                        foreach ($answers as $answer) {
                            ?>
                            <div class="checkbox <?php if (in_array($answer, $right_answers)){ echo 'text-success'; }else if(isset($your_answer) && in_array($answer, $your_answer)){ echo 'text-danger'; }?>" id="answer<?php echo $id; ?>" style="margin-top: 10px;">
                                <label class="checkbox-custom disabled <?php if (isset($your_answer) && in_array($answer, $your_answer)) echo "checked"; ?>" data-initialize="checkbox">                                
                                    <input type="checkbox" disabled="disabled" name="answer[]" value="<?php echo $answer; ?>" <?php if (isset($your_answer) && in_array($answer, $your_answer)) echo 'checked="checked"'; ?>>
                                    <?php echo $answer; ?>
                                </label>
                            </div>
                            <?php
                            $id++;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-10 col-md-offset-1 text-center">
                <?php echo $pagination; ?>
            </div>
        </div>

   

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-10 col-md-offset-1 text-center">
            <a href="tests/test_attempts/<?php echo $test->id; ?>" class="btn btn-primary">Powrót</a>
        </div>
    </div>

</div>

