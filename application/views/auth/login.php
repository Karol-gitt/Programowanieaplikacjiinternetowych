<h1><?php echo lang('login_heading');?></h1>
<p><?php echo lang('login_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<form action="login" method="post">

  <p>
    <?php echo lang('login_identity_label', 'identity');?>
      <input type="text" name="identity">
  </p>

  <p>
    <?php echo lang('login_password_label', 'password');?>
      <input type="password" name="password">
  </p>

  <p>
    <?php echo lang('login_remember_label', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><input type="submit" value="Zaloguj"></p>

</form>

<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>