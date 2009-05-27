<?php
AutoLoader::addFolder(dirname(__FILE__) . '/models');
AutoLoader::addFolder(dirname(__FILE__) . '/lib');

/**
 * Simple plugin that adds more metadata to the page view.
 */
class PageMetadataController extends PluginController {
  // Plugin information
  const PLUGIN_ID      = "page_metadata";
  const PLUGIN_VERSION = "0.0.2";

  // Location of the view folder
  const VIEW_FOLDER            = "page_metadata/views/";
  const PLUGIN_REL_VIEW_FOLDER = "../../plugins/page_metadata/views/";
  
  // CSS handling for classes and IDs for this plugin
  const CSS_ID_PREFIX    = "Page-metadata-";
  const CSS_CLASS_PREFIX = "page-metadata-";
  
  // Singleton instance for observers
  private static $Instance;

  /**
   * Registers the plugin and controller in the system as well as the observers.
   */
  public static function Init() {
    // Register plugin
    Plugin::setInfos(array(
        'id'          => self::PLUGIN_ID,
        'title'       => __('Page Metadata'),
        'description' => __('Allows to add more metadata to a page.'),
        'version'     => self::PLUGIN_VERSION,
       	'license'     => 'AGPL',
      	'author'      => 'THE M',
        'website'     => 'http://github.com/them/frog_page_metadata/',
        'update_url'  => 'http://github.com/them/frog_page_metadata/raw/master/frog-plugins.xml',
        'require_frog_version' => '0.9.5'
    ));
    
    // Register controller
    Plugin::addController(self::PLUGIN_ID, __('Page Metadata'), null, false);
    
    // The callbacks for the backend
    Observer::observe('page_found', __CLASS__.'::Callback_page_found');
    Observer::observe('page_delete', __CLASS__.'::Callback_page_delete');
    Observer::observe('view_page_edit_tabs', __CLASS__.'::Callback_view_page_edit_tabs');
    Observer::observe('view_page_edit_popup', __CLASS__.'::Callback_view_page_edit_popup');
    Observer::observe('page_add_after_save', __CLASS__.'::Callback_page_page_updated');
    Observer::observe('page_edit_after_save', __CLASS__.'::Callback_page_page_updated');
  }

  /**
   * Returns the instance of the controller.
   *
   * @return the instance of the controller object
   */
  public static function Get_instance() {
    if (!self::$Instance) {
      $class = __CLASS__;
      self::$Instance = new $class();
    }
  
    return self::$Instance;
  }

  /**
   * Create a new controller instance and apply the sidebar to the backend.
   */
  public function __construct() {
    AuthUser::load();
    if (!(AuthUser::isLoggedIn())) {
        redirect(get_url('login'));            
    }
    
    self::$Instance = $this;
  }

  /**
   * Catch non-existing controller requests to home.
   */
  public function __call($name, $args) {
    redirect(get_url(''));  
  }
  
  /**
   * Returns the template for the view fragments of new metadata that is going to be added.
   */
  public function template() {
    if ( !isset($_POST["keyword"]) || !isset($_POST["value"]) ) {
      header("HTTP/1.0 412 Precondition Failed");
      header("Status: 412 Precondition Failed");
      exit();
    }

    $this->display('template', array(
      'keyword' => $_POST["keyword"],
      'value'   => $_POST["value"],
      /* NOTE: Any unique value is ok for the index used in the form.
               The update function only uses the keyword name and ignores
               the index. */
      'unique'  => time(),
    ));
  }

  /**
   * Private function that provide the default values for the view.
   *
   * @return default values
   */
  private function get_default_view_vars() {
    $vars = array();
    
    $vars['plugin_id'] = self::PLUGIN_ID;
    $vars['css_id_prefix'] = self::CSS_ID_PREFIX;
    $vars['css_class_prefix'] = self::CSS_CLASS_PREFIX;
    $vars['plugin_url'] = get_url('plugin/'.self::PLUGIN_ID.'/');
    
    return $vars;
  }

  /**
   * Overwrite the render function to enforce that some variables are
   * available for the whole view artifacts.
   * Simplify the view file handling by prefixing the file with the
   * plugin directory.
   *
   * @param view the view file
   * @param vars parameter for the views
   * @return the view
   */
  /*@overwrite('render')*/
  public function render($view, $vars=array()) {
    $vars = array_merge($this->get_default_view_vars(), $vars);

    /* We only render views for this plugin. So add the prefix of the view folder to every view file. */      
    return parent::render(self::VIEW_FOLDER.$view, $vars);
  }

  /**
   * View factory for the controller and the view.
   *
   * @param view the filename without the postfix
   * @param vars the template vars
   * @return a view object
   */
  public function create_view($view, $vars=array()) {
    $vars = array_merge($this->get_default_view_vars(), $vars);
      
    return new View(self::PLUGIN_REL_VIEW_FOLDER.$view, $vars);
  }

  /**
   * Adds a new tab with the metadata.
   *
   * @param the current page
   */
  public static function Callback_view_page_edit_tabs($page) {
    $metadata = array();
    
    // Only for existing pages there can be metadata
    if (isset($page->id) && !empty($page->id)) {
      $metadata = PageMetadata::FindAllByPage($page);
    }
    
    // Display the metadata form with metadata (if any).
    self::Get_instance()->create_view('metadata', array(
      'metadata' => $metadata,
    ))->display();
  }
  
  /**
   * Displays the popup dialog for adding new metadata.
   */
  public static function Callback_view_page_edit_popup() {
    self::Get_instance()->create_view('new_popup')->display();
  }
  
  /**
   * Apply additional metadata to page object before displayed.
   * NOTE: this function manipulates the page object.
   *
   * @param page the page object
   */
  public static function Callback_page_found($page) {
    // PHP is a dynamic language, create new attribute
    $plugin_id = self::PLUGIN_ID;
    // Apply metadata as simple key value array
    $page->$plugin_id = PageMetadata::FindAllByPageAsArray($page);
  }
  
  /**
   * Delets all metadata from a deleted page.
   *
   * @param page the page object
   */
  public static function Callback_page_delete($page) {
    PageMetadata::DeleteAllByPage($page);
  }
  
  /**
   * Updates the page metadata. In contrast to the core, the metadata is not removed by the controller directly
   * e.g. via an AJAX call. The problem is that newly generated pages do not have an ID and therefore they can not
   * be created nor deleted. So the semantic is that keywords with empty values get removed.
   *
   * @param the current page
   */
  public static function Callback_page_page_updated($page) {
    // Get all posted metadata
    foreach ($_POST[self::PLUGIN_ID] as $metadata) {
      $value   = trim($metadata["value"]);
      $keyword = $metadata["name"];
      // Visible is more like a 'hack' to allow other plugins to style the metadata
      // in their own way.
      $visible = isset($metadata["visible"]) ? $metadata["visible"] : 1;
      
      // If the metadata is not existing, create it.
      $is_in_db = true;
      if (!$obj = PageMetadata::FindOneByPageAndKeyword($page, $keyword)) {
        $is_in_db = false;
        // New value object
        $obj = new PageMetadata(array(
          "page_id" => $page->id,
          "keyword" => $keyword,
          "visible" => $visible,
        ));
      }

      // Skip or delete empty values.
      if (empty($value)) {
          // Delete metadata if value is empty
          if ($is_in_db) {
            $obj->delete();
          }
          else {
            continue;
          }
        }
        else {
          // Update data and save.
          $obj->value = $value;
          $obj->visible = $visible;
          $obj->save();        
        }
    }
  }
}
?>