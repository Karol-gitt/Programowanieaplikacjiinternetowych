<div class="container">   
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php echo $test->title; ?></h1>
            <p>Pytań w teście: <?php echo $questions_in_test; ?></p>
            <?php if(isset($test_end_time)) { ?>
            <p style="font-size: 22px; height: 35px;" id="countdown"></p>
            <script>
                
                window.onload = function(e){

                    var eventTime = moment('<?php echo $test_end_time ?>', 'YYYY-MM-DD HH:mm:ss').unix(),
                        currentTime = moment().unix(),
                        diffTime = eventTime - currentTime,
                        duration = moment.duration(diffTime * 1000, 'milliseconds'),
                        interval = 1000;
                    // if time to countdown
                    if(diffTime > 0) {

                        setInterval(function(){

                            duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
                            
                            if(duration<1){
                                location.href = 'test/end_test/<?php echo $attempt_id; ?>';
                            }
                            var d = moment.duration(duration).days(),
                                h = moment.duration(duration).hours(),
                                m = moment.duration(duration).minutes(),
                                s = moment.duration(duration).seconds();

                            d = $.trim(d).length === 1 ? '0' + d : d;
                            h = $.trim(h).length === 1 ? '0' + h : h;
                            m = $.trim(m).length === 1 ? '0' + m : m;
                            s = $.trim(s).length === 1 ? '0' + s : s;

                            // show how many hours, minutes and seconds are left
                             $('#countdown').text(h + ':' + m + ':' + s + '');

                        }, interval);
                    }
                };

                
            </script>
            <?php } ?>
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

            <form action="<?php echo current_url(); ?>" id="answer_form" method="POST" >

                <div class="answers" style="margin-top: 50px;">
                    <?php
                    if ($question->type == 'short') {
                        ?>
                        <div class="form-group">
                            <label for="answer">Twoja odpowiedź:</label>
                            <input type="text" name="answer" id="answer" class="form-control" <?php if (isset($your_answer)) echo 'value="' . $your_answer . '"'; ?>/>
                        </div>

                        <?php
                    } else if ($question->type == 'test-one') {
                        $id = 0;
                        foreach ($answers as $answer) {
                            ?>
                            <div class="radio <?php if (isset($your_answer) && $your_answer == $answer) echo 'checked'; ?>" style="margin-top: 10px;">
                                <label class="radio-custom" data-initialize="radio">
                                    <input type="radio" name="answer" id="answer<?php echo $id; ?>" value="<?php echo $answer; ?>" <?php if (isset($your_answer) && $your_answer == $answer) echo 'checked="checked"'; ?>>
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
                            <div class="checkbox" id="answer<?php echo $id; ?>" style="margin-top: 10px;">
                                <label class="checkbox-custom <?php if (isset($your_answer) && in_array($answer, $your_answer)) echo "checked"; ?>" data-initialize="checkbox">                                
                                    <input type="checkbox" name="answer[]" value="<?php echo $answer; ?>" <?php if (isset($your_answer) && in_array($answer, $your_answer)) echo 'checked="checked"'; ?>>
        <?php echo $answer; ?>
                                </label>
                            </div>
        <?php
        $id++;
    }
}
?>
                </div>        

            </form>

        </div>
    </div>
    
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-10 col-md-offset-1">
            <div class="alert alert-info">
            <b>Ważne!</b> Pamiętaj aby zawsze klikać przycisk ZAPISZ ODPOWIEDŹ po zaznaczeniu odpowiedzi. Przycisk ZAKOŃCZ TEST kończy podejście bez zapisywania ostatniej odpowiedzi.
            </div>
        </div>
    </div>
    
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-6 col-md-offset-1">
<?php echo $pagination; ?>
        </div>
        <div class="col-md-4">
            <button class="btn btn-success" style="margin-top: 20px;" onclick="$('#answer_form').submit()">Zapisz odpowiedź</button>
            <button class="btn btn-primary" style="margin-top: 20px;" onclick="confirmEndTest()">Zakończ test</button>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmEnd">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Zakończyć test?</h4>
            </div>
            <div class="modal-body">
                <p>Jesteś pewien, że chcesz zakończyć test?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <a href="test/end_test/<?php echo $attempt_id; ?>" class="btn btn-danger" >Tak</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->