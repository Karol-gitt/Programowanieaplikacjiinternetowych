<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div style="margin-bottom: 50px;" class="pull-right">
            <?php echo anchor('auth/create_group', lang('index_create_group_link'), ["class"=>"btn btn-default"])?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">       
            
            
            <div id="infoMessage" class="alert alert-info container row text-center" <?php if($message == '') echo 'style="display: none;"' ?>><?php echo $message;?></div>
                      
            <table class="table table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th>Grupa</th>
                        <th>Opis</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
                
                <tbody>
                <?php foreach($groups as $group){?>
                        <tr>
                    <td><?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($group->description,ENT_QUOTES,'UTF-8');?></td>
                                <td>
                                    <?php echo anchor("auth/edit_group/".$group->id.'/groups', 'Edytuj', ["class"=>"btn btn-default btn-xs"]) ;?> 
                                    <?php echo '<a href="javascript:confirmDelete('.$group->id.')" class="btn btn-danger btn-xs">Usuń</a>' ;?>
                                </td>
                        </tr>
                <?php }?>
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
        <h4 class="modal-title">Usuń grupę</h4>
      </div>
      <div class="modal-body">
        <p>Czy na pewno usunąć wybraną grupę?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
        <button type="button" class="btn btn-danger" onclick="deleteGroup()">Tak</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->