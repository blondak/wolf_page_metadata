<div id="<?php echo $css_id_prefix;?>container" class="page" title="<?php echo __('More Metadata'); ?>">
  <a href="#" id="add-metadata" onclick="toggle_popup('<?php echo $css_id_prefix;?>new-popup', '<?php echo $css_id_prefix; ?>new-keyword'); return false;" title="<?php echo __('Add Metadata'); ?>"><img src="<?php echo URI_PUBLIC;?>wolf/admin/images/plus.png" alt="plus icon" /></a>

<?php 
  // Use a simple counter as key for the forms
  $index = 0;
  foreach($metadata as $m) {
    // Only show the visible (direct editable) ones
    if (!$m->visible) { continue; }

    // Use the template to display the metadata
    echo PageMetadataController::Get_instance()->create_view('template', array("unique" => $index, "keyword" => $m->keyword, "value" => $m->value));

    $index++;
  }

  // Allow extensions
  Observer::notify('view_page_page_metadata', $metadata);
?>
</div>