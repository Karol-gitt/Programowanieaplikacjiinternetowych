<div class="container">
    <div class="row">
        <div class="col-md-5">
            <select class="chosen" name="question_category" id="question_category_select" style="width: 100%">
                <option value="0" <?php if ($this->uri->rsegment(3) && $this->uri->rsegment(3) == 0) echo "selected"; ?>>Wszystkie kategorie</option>                    
                <?php
                foreach ($categories as $category) {
                    ?>
                    <option value="<?php echo $category->id ?>" <?php if ($this->uri->rsegment(3) == $category->id) echo "selected"; ?>><?php echo $category->name; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div style="margin-bottom: 50px;" class="col-md-4 col-md-offset-3">
            <a href="javascript:addQuestion()" class="btn btn-default pull-right">Utwórz pytanie</a>
            <?php //echo anchor('javascript:addQuestion()', "Utwórz pytanie", ["class"=>"btn btn-default"])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">          

             <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessage" class="alert alert-info container row text-center"><?php echo $message;?></div>
            <?php } ?>
                
            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessage" class="alert alert-danger container row text-center"><?php echo $error_message; ?></div>
            <?php } ?>
                
            <table class="table table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th>Pytanie</th>
                        <th>Typ pytania</th>
                        <th>Kategoria</th>
                        <th>Akcja</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($questions as $question) { ?>
                        <tr>
                            <td><?php echo anchor('questions_base/question/'.$question->id,htmlspecialchars($question->question_name, ENT_QUOTES, 'UTF-8')); ?></td>
                            <td><?php echo htmlspecialchars($question->type, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($question->category_name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php echo anchor("questions_base/edit_question/" . $question->id, 'Edytuj', ["class" => "btn btn-default btn-xs"]); ?> 
                                <?php echo '<a href="javascript:confirmDelete(' . $question->id . ')" class="btn btn-danger btn-xs">Usuń</a>'; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="confirmDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Usuń pytanie</h4>
            </div>
            <div class="modal-body">
                <p>Czy na pewno usunąć wybrane pytanie?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" onclick="deleteQuestion()">Tak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="addQuestion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Wybierz typ pytania</h4>
            </div>
            <div class="modal-body">

                <form id="addQuestionForm" method="POST" action="questions_base/question_type">
                    <?php
                    $id = 0;
                    foreach ($questions_types as $question_type) {
                        ?>
 
                        <div class="radio">
                            <label class="radio-custom <?php if ($id==0) echo 'checked'; ?>" <?php if($id != 0) echo 'data-initialize="radio"' ?> id="typeRadio<?php echo $id; ?>">
                                <input type="radio" name="questionType" id="questionType<?php echo $id; ?>" value="<?php echo $question_type->alias; ?>" <?php if ($id==0) echo 'checked="checked"'; ?>>
                                <?php echo $question_type->type; ?>
                            </label>
                        </div>

                        <?php
                        $id++;
                    }
                    ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-primary" onclick="$('#addQuestionForm').submit();">Dodaj</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->