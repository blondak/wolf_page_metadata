<?php
$plugin_id = "page_metadata";
$controller_name = 'PageMetadataController';

// Registers the plug-in and controller as well as the observers.
Plugin::setInfos(array(
  'id'          => $plugin_id,
  'title'       => __('Page Metadata'),
  'description' => __('Allows to add more metadata to a page.'),
  'version'     => '1.0.1',
  'license'     => 'MIT',
  'author'      => 'THE M',
  'website'     => 'http://github.com/them/frog_page_metadata/',
  'update_url'  => 'http://github.com/them/frog_page_metadata/raw/master/frog-plugins.xml',
  'require_frog_version' => '0.9.5'
));

// XXX: because "page found" is a subscribed topix, the controller must
//      be available for the frontend. Wolf does not auto populate in the
//      frontend when registering the controller.
AutoLoader::addFolder(dirname(__FILE__));  

// Register controller
Plugin::addController($plugin_id, __('Page Metadata'), null, false);
  
// The callbacks for the backend
Observer::observe('page_found',           $controller_name.'::Callback_page_found');
Observer::observe('page_delete',          $controller_name.'::Callback_page_delete');
Observer::observe('view_page_edit_tab_links',  $controller_name.'::Callback_view_page_edit_tab_links');
Observer::observe('view_page_edit_tabs',  $controller_name.'::Callback_view_page_edit_tabs');
Observer::observe('view_page_edit_popup', $controller_name.'::Callback_view_page_edit_popup');
Observer::observe('page_add_after_save',  $controller_name.'::Callback_page_page_updated');
Observer::observe('page_edit_after_save', $controller_name.'::Callback_page_page_updated');

function page_metadata_key_to_id($key) {
  // XXX: There may be more chars to replace
  return PageMetadataController::CSS_ID_PREFIX."keyword-".strtr($key, " _", "--");
}
?>