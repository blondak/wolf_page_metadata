<?php
require_once('PageMetadataController.php');

PageMetadataController::Init();

function page_metadata_key_to_id($key) {
  // XXX: There may be more chars to replace
  return PageMetadataController::CSS_ID_PREFIX."keyword-".strtr($key, " _", "--");
}
?>