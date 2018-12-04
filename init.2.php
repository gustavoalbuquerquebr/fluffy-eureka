<?php

define("UNKNOWN_DB_ERRCODE", 1049);
define("UNKNOWN_TABLE_ERRCODE", "42S02");
define("DB_HOST", "localhost");
define("DB_USER", "gustavo");
define("DB_PASS", "123");
define("DB_NAME", "revision");

function make_url($path = "", $is_client_side = false) {
  
  if ($path[0] === "/") $path = substr($path, 1);

  if ($is_client_side) {
    return $_SERVER["PROJETC_ROOT"] . $path;
  } else {
    return $_SERVER["DOCUMENT_ROOT"] . $_SERVER["PROJECT_ROOT"] . $path;
  }

}

function class_autoloader($class) {
  // $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
  require make_url("class/$class.php");
}

function db_connect() {
  
  try {
    $pdo = create_pdo();
    !db_has_tables($pdo) && create_tables($pdo);

  } catch (Exception $e) {
    !unknown_db($e) && exit("Something went wrong");
    create_database();
    $pdo = create_pdo();
    create_tables($pdo);
  }

  return $pdo;
}
