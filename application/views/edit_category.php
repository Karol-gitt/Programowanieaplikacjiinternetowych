<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Edytuj kategorię</h1>
        </div>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4 col-md-offset-4">

            <?php if (isset($message) && $message != '') { ?>       
                <div id="infoMessageStatic" class="alert alert-info text-center"><?php echo $message; ?></div>
            <?php } ?>
                
            <?php if (isset($error_message) && $error_message != '') { ?>  
                <div id="infoMessageStatic" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php } ?>   

            <?php echo form_open(current_url()); ?>

            <div class="form-group">
                <label for="<?php echo $category_name['id'] ?>">Nazwa kategorii:</label>
                 <?php echo form_input($category_name); ?>
            </div> 
                
            <p>
                <?php echo form_submit('submit', 'Zapisz', 'class="btn btn-primary btn-block"'); ?>
                <a href="questions_base/categories" class="btn btn-default btn-block">Powrót</a>
                <?php echo '<a href="javascript:confirmDelete('.$category->id.')" class="btn btn-danger btn-block">Usuń</a>' ;?>
            </p>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<div class="modal fade" id="confirmDelete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Usuń kategorię</h4>
      </div>
      <div class="modal-body">
        <p>Czy na pewno usunąć wybraną kategorię?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
        <button type="button" class="btn btn-danger" onclick="deleteCategory()">Tak</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->