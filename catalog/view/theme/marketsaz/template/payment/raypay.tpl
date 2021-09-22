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
    <div class="buttons">
      <div class="pull-right">
        <a href="<?php echo $action; ?>" class="btn btn-primary" ><?php echo $button_confirm; ?></a>
      </div>
    </div>
</div>
<?php } ?>
