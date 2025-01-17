<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Zawartość testu</h1>
            <h3 class="text-success"><?php echo $test->title; ?></h3>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-12">

            <div class="form-group pull-left" >
                <label for="<?php echo $random_questions['id'] ?>" style="font-size: 18px; margin-top: 5px;">Pytania w kolejności losowej:</label>&nbsp;&nbsp;
                <?php echo form_checkbox($random_questions); ?>
            </div>
            <a href="javascript:changeQuestionOrder()" id="set_order_button" class="btn btn-default pull-left" style="margin-left: 20px;" <?php if ($random_questions['checked']) echo "disabled" ?>>Zmień kolejność pytań</a>

            <a href="javascript:addQuestions()" class="btn btn-default pull-right">Dodaj pytania</a>
            <a href="javascript:confirmRemove()" class="btn btn-danger pull-right"style="margin-right: 10px;">Usuń pytania z testu</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <h4>Maksymalna ilość punktów za ten test: <b><?php
                    if ($rate_summary)
                        echo $rate_summary;
                    else
                        echo "0";
                    ?></b></h4>

        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-12">

            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessage" class="alert alert-info container row text-center"><?php echo $message; ?></div>
            <?php } ?>

            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessage" class="alert alert-danger container row text-center"><?php echo $error_message; ?></div>
            <?php } ?>   

            <table class="table table-striped" id="dataTableNoOrdering">
                <thead>
                    <tr>
                        <th>Pytanie</th>
                        <th>Typ pytania</th>
                        <th>Kategoria</th>
                        <th>Punktacja</th>
                        <th>Akcja</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($questions as $question) { ?>
                        <tr>
                            <td><?php echo anchor('questions_base/question/' . $question->id . '/' . $test->id, htmlspecialchars($question->question_name, ENT_QUOTES, 'UTF-8')); ?></td>
                            <td><?php echo htmlspecialchars($question->type, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($question->category_name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($question->rate, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php echo anchor("questions_base/edit_question/" . $question->id . '/' . $test->id, 'Edytuj', ["class" => "btn btn-default btn-xs"]); ?> 
                                <?php echo '<a href="javascript:confirmDelete(' . $question->id . ')" class="btn btn-danger btn-xs">Usuń</a>'; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="addQuestions">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Dodaj pytania</h4>
            </div>
            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="chosen" data-testid="<?php echo $test->id; ?>" id="questions_category" style="width: 100%">
                                <option value="0" selected>Wszystkie kategorie</option>                    
                                <?php
                                foreach ($categories as $category) {
                                    ?>
                                    <option value="<?php echo $category->id ?>"><?php echo $category->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-12">                            

                            <?php
                            echo form_open(current_url(), array('id' => 'add_questions_form'));
                            ?>

                            <div class="list-group" id="questions_list">

                            </div>

                            <?php
                            echo form_close();
                            ?>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info pull-left" data-toggle="button" aria-pressed="false" autocomplete="off" onclick="toggleChecked($(this).hasClass('active'))">
                    Zaznacz / odznacz wszystkie
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-danger" onclick="$('#add_questions_form').submit()">Dodaj</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="confirmDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Usuń pytanie z testu</h4>
            </div>
            <div class="modal-body">
                <p>Czy na pewno usunąć wybrane pytanie z testu?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" onclick="deleteQuestionFromTest(<?php echo $test->id; ?>)">Tak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="confirmRemove">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Usuń pytania z testu</h4>
            </div>
            <div class="modal-body">
                <p>Czy na pewno usunąć wszystkie pytania z testu?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" onclick="removeQuestionsFromTest(<?php echo $test->id; ?>)">Tak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="questionsOrder">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Zmień kolejność pytań</h4>
            </div>
            <div class="modal-body">

                <div class="container-fluid">                    
                    <div class="row">
                        <div class="col-md-12">                      

                            <ul class="list-group questions_order" id="questions_list_order" data-testid="<?php echo $test->id; ?>">

                            </ul>                            

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-danger" onclick="saveOrder(<?php echo $test->id; ?>)">Zapisz zmiany</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->