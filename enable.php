<?php
// Included?
if (!defined('IN_FROG')) { exit(); }

AutoLoader::addFolder(dirname(__FILE__) . '/models');

$table_name = TABLE_PREFIX.PageMetadata::TABLE_NAME;

// Connection
$pdo = Record::getConnection();
$driver = strtolower($pdo->getAttribute(Record::ATTR_DRIVER_NAME));

// Clean metadata
$pdo->exec("DROP TABLE $table_name");

if ($driver == 'mysql') {

  // Create table
  $pdo->exec("CREATE TABLE $table_name (
    id          int(11) unsigned NOT NULL AUTO_INCREMENT,
    visible     tinyint(1) unsigned NOT NULL DEFAULT '1',
    page_id     int(11) unsigned NOT NULL,
    keyword     varchar(255) NOT NULL,
    value       varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
}
else if ($driver == 'sqlite') {
  // Create table
  $pdo->exec("CREATE TABLE $table_name (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    visible     CHAR(1) NOT NULL DEFAULT '1',
    page_id     INTEGER NOT NULL,
    keyword     VARCHAR(255) NOT NULL,
    value       VARCHAR(255) DEFAULT NULL
  )");
}
?>