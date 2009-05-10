<div class="popup" id="<?php echo $css_id_prefix; ?>new-popup" style="display: none;">
  <h3><?php echo __('Add new metadata'); ?></h3>
  <div id="<?php echo $css_id_prefix; ?>busy" class="busy" style="display: none;"><img alt="Spinner" src="images/spinner.gif" /></div>
  
  <div id="<?php echo $css_id_prefix; ?>new-popup-error" style="display: none;" class="error-message">
    <?php echo __('This metadata can not be added.'); ?>
  </div>

  <form action="#" method="post" onsubmit="page_metadata_add('<?php echo $plugin_url?>template', '<?php echo __('Keyword'); ?>', '<?php echo __('Value'); ?>'); return false;">
    <div>
      <input type="text" id="<?php echo $css_id_prefix; ?>new-keyword" name="keyword" value="<?php echo __('Keyword'); ?>" />
      <input type="text" id="<?php echo $css_id_prefix; ?>new-value" name="value" value="<?php echo __('Value'); ?>" />
      <input type="submit" id="<?php echo $css_id_prefix; ?>new-button" name="commit" value="<?php echo __('Add'); ?>" />
    </div>
    <p><a class="close-link" href="#" onclick="Element.hide('<?php echo $css_id_prefix; ?>new-popup'); return false;"><?php echo __('Close'); ?></a></p>
  </form>
</div>