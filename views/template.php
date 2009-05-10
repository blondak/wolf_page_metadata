<?php
  $keyword_css_id = page_metadata_key_to_id($keyword);
?>
<div class="<?php echo $css_class_prefix; ?>row" id="<?php echo $keyword_css_id; ?>">
  <label for="<?php echo $keyword_css_id; ?>-field"><?php echo $keyword; ?></label>
  <div class="<?php echo $css_class_prefix; ?>column">
    <input
     id="<?php echo $keyword_css_id; ?>-field"
     name="<?php echo $plugin_id; ?>[<?php echo $unique; ?>][value]"
     type="text"
     value="<?php echo $value; ?>"
     class="<?php echo $css_class_prefix; ?>value"
    />
    <input class="<?php echo $css_class_prefix; ?>name" type="hidden" name="<?php echo $plugin_id; ?>[<?php echo $unique; ?>][name]" value="<?php echo $keyword; ?>" />
    <a class="<?php echo $css_class_prefix; ?>action" href="#" onclick="if (confirm('<?php echo __('Delete current metadata?'); ?>')) { page_metadata_remove('<?php echo $keyword_css_id; ?>', '<?php echo $keyword; ?>'); }; return false;" title="<?php echo __('Remove Metadata'); ?>"><img src="images/minus.png" alt="minus icon" /></a>
  </div>
</div>