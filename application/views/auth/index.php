<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div style="margin-bottom: 50px;" class="pull-right">
            <?php echo anchor('auth/create_user', lang('index_create_user_link'), ["class"=>"btn btn-default"])?>  <?php //echo anchor('auth/create_group', lang('index_create_group_link'), ["class"=>"btn btn-default"])?>
            </div>
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
                        <th><?php echo lang('index_fname_th');?></th>
                        <th><?php echo lang('index_lname_th');?></th>
                        <th><?php echo lang('index_email_th');?></th>
                        <th><?php echo lang('index_groups_th');?></th>
                        <th><?php echo lang('index_status_th');?></th>
                        <th><?php echo lang('index_action_th');?></th>
                    </tr>
                </thead>
                
                <tbody>
                <?php foreach($users as $user){?>
                        <tr>
                    <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
                    <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
                                <td>
                                        <?php foreach ($user->groups as $group){?>
                                                <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?>
                                                <?php //echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
                                        <?php }?>
                                </td>
                                <td>
                                    <?php 
                                        if($user->active){
                                            echo '<input id="'.$user->id.'" class="status" type="checkbox" data-size="mini" checked data-toggle="toggle" data-on="Tak" data-off="Nie" data-onstyle="success" data-offstyle="danger">';
                                        }else{
                                            echo '<input id="'.$user->id.'" class="status" type="checkbox" data-size="mini" data-toggle="toggle" data-on="Tak" data-off="Nie" data-onstyle="success" data-offstyle="danger">';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php echo anchor("auth/edit_user/".$user->id.'/index', 'Edytuj', ["class"=>"btn btn-default btn-xs"]) ;?> 
                                    <?php echo '<a href="javascript:confirmDelete('.$user->id.')" class="btn btn-danger btn-xs">Usuń</a>' ;?>
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
        <h4 class="modal-title">Usuń użytkownika</h4>
      </div>
      <div class="modal-body">
        <p>Czy na pewno usunąć wybranego uzytkownika?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
        <button type="button" class="btn btn-danger" onclick="deleteUser()">Tak</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->