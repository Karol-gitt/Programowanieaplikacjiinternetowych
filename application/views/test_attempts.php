<div class="container">
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
                        <th>Student</th>
                        <th>Czas rozpoczęcia</th>
                        <th>Czas zakończenia</th>
                        <th>Wynik</th>
                        <th>Akcja</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($test_attempts as $attempt) { ?>
                        <tr>
                            <td><?php echo $attempt->student; ?></td>
                            <td><?php echo $attempt->start_time; ?></td>
                            <td><?php echo $attempt->end_time; ?></td>
                            <td><?php echo $summary[$attempt->id]; ?></td>
                            <td>
                                <?php echo anchor('tests/test_attempt/' . $attempt->id, 'Zobacz podejście', ["class" => "btn btn-success btn-xs"]); ?> 
                                <?php echo '<a href="javascript:confirmDelete(' . $attempt->id . ')" class="btn btn-danger btn-xs">Usuń</a>'; ?>
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
                <h4 class="modal-title">Usuń podejście</h4>
            </div>
            <div class="modal-body">
                <p>Czy na pewno usunąć wybrane podejście?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" onclick="deleteTestAttempt(<?php echo $test_id ?>)">Tak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

