<?php
// Included?
if (!defined('IN_FROG')) { exit(); }
// Allowed for this user?
AuthUser::load(); if (!(AuthUser::hasPermission('administrator'))) { exit(); }

///////////////////////////////////////////////////////////////////////////////

AutoLoader::addFolder(dirname(__FILE__) . '/models');

$table_name = TABLE_PREFIX.PageMetadata::TABLE_NAME;

// Connection
$pdo = Record::getConnection();
$driver = strtolower($pdo->getAttribute(Record::ATTR_DRIVER_NAME));

// Clean metadata
$pdo->exec("DROP TABLE $table_name");

?>