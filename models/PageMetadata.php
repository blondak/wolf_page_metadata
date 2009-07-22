<?php
class PageMetadata extends Record {
  const TABLE_NAME = 'page_metadata';
 
  // Fields from database as properties
  public $id;
  public $visible;
  public $page_id;
  public $keyword;
  public $value;
  
  /**
   * Helper function that returns the page_id from a Page object or a page_id as number.
   *
   * @param page page object or page_id as number
   * @return page_id or null
   */
  private static function Get_page_id($page) {
    $page_id = null;
    
    if (is_numeric($page)) {
      $page_id = $page;
    }
    else if ($page instanceof Page) {
      $page_id = $page->id;
    }

    return $page_id;
  }
  
  /**
   * Returns all metadata for a page.
   *
   * @param page page object or page_id as number
   * @return list of metadata object
   */
  public static function FindAllByPage($page) {
    if (!$page_id = self::Get_page_id($page)) {
      return array();
    }
    
    return self::findAllFrom(__CLASS__, 'page_id = ? ORDER BY keyword', array($page_id));
  }
  
  /**
   * Returns all metadata as native PHP associative array
   * 
   * @param page page object or page_id as number
   * @return associative array with key/value pairs
   */
  public static function FindAllByPageAsArray($page) {
    // Returns an empty array if nothing was found
    $metadata = array();

    foreach (self::FindAllByPage($page) as $meta) {
      // Map to native PHP associative array
      $metadata[$meta->keyword] = $meta->value;
    }
    
    return $metadata;
  }
  
  /**
   * Returns one metadata ojbect for page by keyword
   *
   * @param page page object or page_id as number
   * @return a metadata object or null
   */
  public static function FindOneByPageAndKeyword($page, $keyword) {
    if (!$page_id = self::Get_page_id($page)) {
      return null;
    }

    return self::findOneFrom(__CLASS__, 'page_id = ? AND keyword = ?', array($page_id, $keyword));
  }
  
  /**
   * Deletes all metadata for a given page
   *
   * @param page page object or page_id as number
   */
  public static function DeleteAllByPage($page) {
    if (!$page_id = self::Get_page_id($page)) {
      return;
    }
    
    self::deleteWhere(__CLASS__, 'page_id = ?', array($page_id));
  }
  
  /**
   * Delets a keyword for all pages. Intention is for plugins that gets deactivated.
   * 
   * @param keyword name of the keyword
   */
  public static function DeleteAllByKeyword($keyword) {
    if (empty($keyword)) {
      return;
    }
    
    self::deleteWhere(__CLASS__, 'keyword = ?', array($keyword));
  }
}
?>