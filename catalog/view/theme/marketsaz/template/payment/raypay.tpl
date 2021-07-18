<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location = '<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
    </tr>
  </table>
</div>
<?php } else { ?>
<div class="buttons">
  <form action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="TerminalID" value="<?php echo $terminal_id; ?>" />
    <div class="buttons">
      <div class="pull-right">
        <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
      </div>
    </div>
  </form>
</div>
<?php } ?>
