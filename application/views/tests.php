<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div style="margin-bottom: 50px;" class="pull-right">
                <?php echo anchor('tests/create_test', "Utwórz test", ["class" => "btn btn-default"]) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">           

            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessage" class="alert alert-info container row text-center"><?php echo $message; ?></div>
            <?php } ?>

            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessage" class="alert alert-danger container row text-center"><?php echo $error_message; ?></div>
            <?php } ?>

            <table class="table table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Kod dostępu</th>
                        <th>Data utworzenia</th>
                        <th>Akcja</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tests as $test) { ?>
                        <tr>
                            <td><?php echo anchor('tests/test_attempts/' . $test->id, htmlspecialchars($test->title, ENT_QUOTES, 'UTF-8')); ?></td>
                            <td><?php echo htmlspecialchars($test->access_code, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($test->create, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php echo anchor("tests/test_attempts/" . $test->id, 'Zobacz podejścia', ["class" => "btn btn-primary btn-xs"]); ?> 
                                <?php echo anchor("tests/edit_test/" . $test->id, 'Edytuj', ["class" => "btn btn-default btn-xs"]); ?> 
                                <?php echo anchor("tests/test/" . $test->id, 'Zawartość testu', ["class" => "btn btn-success btn-xs"]); ?> 
                                <?php echo '<a href="javascript:confirmDelete(' . $test->id . ')" class="btn btn-danger btn-xs">Usuń</a>'; ?>
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
                <h4 class="modal-title">Usuń test</h4>
            </div>
            <div class="modal-body">
                <p>Czy na pewno usunąć wybrany test ?</p>
                <p>Usunięcie testu spowoduje usunięcie wszystkich podejść.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" onclick="deleteTest()">Tak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->