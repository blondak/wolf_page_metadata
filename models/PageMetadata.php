<?php
class PageMetadata extends Record {
  const TABLE_NAME = 'page_metadata';
    
  public $id;
  public $visible;
  public $page_id;
  public $keyword;
  public $value;
}
?>