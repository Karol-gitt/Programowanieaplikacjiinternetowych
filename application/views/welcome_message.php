<div class="container">    
    <div class="row">
        <?php
        if (isset($test_attempt) && !empty($test_attempt)) {
            ?>
            <div class="col-md-12">
                <h1 class="text-center">Masz otwarte podejście do testu</h1>

                <table class="table" style="margin-top: 50px;">
                    <tr>
                        <th>Test</th>
                        <th>Student</th>
                        <th>Czas rozpoczęcia</th>
                        <th>Akcje</th>
                    </tr>
                    <tr>
                        <td><?php echo $test->title; ?></td>
                        <td><?php echo $test_attempt->student; ?></td>
                        <td><?php echo $test_attempt->start_time; ?></td>
                        <td>
                            <?php echo anchor("test/cancel_attempt", 'Anuluj', ["class" => "btn btn-default btn-xs"]); ?> 
                            <?php echo anchor('test/test_attempt/' . $test->id, 'Kontynuuj', ["class" => "btn btn-primary btn-xs"]); ?> 
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        } else {
            ?>

            <div class="col-md-4 col-md-offset-4">

                <?php if (isset($message) && $message != '') { ?>       
                    <div id="infoMessageStatic" class="alert alert-info text-center"><?php echo $message; ?></div>
                <?php } ?>

                <?php if (isset($error_message) && $error_message != '') { ?>  
                    <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                <?php } ?>

                <form method="post" action="<?php echo current_url(); ?>" id="testForm">
                    <div class="form-group">
                        <div class="control-group">
                            <label for="test_code">Podaj kod testu</label>
                            <div class="input-group">
                                <input type="text" name="test_code" id="test_code" class="form-control" placeholder="Kod testu">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">Rozwiąż</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
        ?>

    </div>
</div>