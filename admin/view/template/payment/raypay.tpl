<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
    <a onclick="$('#form').submit();" class="button">
      <span class="button_left button_save"></span>
      <span class="button_middle"><?php echo $button_save; ?></span>
      <span class="button_right"></span>
    </a>
    <a onclick="location='<?php echo $cancel; ?>';" class="button">
      <span class="button_left button_cancel"></span>
      <span class="button_middle"><?php echo $button_cancel; ?></span>
      <span class="button_right"></span>
    </a>
  </div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form id="form" name="form" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_user_id; ?></td>
        <td><input type="text" name="raypay_user_id" value="<?php echo $raypay_user_id; ?>" />
          <br />
          <?php if ($error_user_id) { ?>
          <span class="error"><?php echo $error_user_id; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_acceptor_code; ?></td>
        <td><input type="text" name="raypay_acceptor_code" value="<?php echo $raypay_acceptor_code; ?>" />
          <br />
          <?php if ($error_acceptor_code) { ?>
          <span class="error"><?php echo $error_acceptor_code; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="raypay_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $raypay_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="raypay_status">
            <?php if ($raypay_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="raypay_sort_order" value="<?php echo $raypay_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">
	$.tabs('.tabs a'); 
</script>
