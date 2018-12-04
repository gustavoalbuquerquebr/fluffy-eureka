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

function redirect_to($path) {
  header("Location: $path");
}

function class_autoloader($class) {
  // $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
  require make_url("class/$class.php");
}

spl_autoload_register("class_autoloader");

function simple_pdo() {
  return new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
}

function full_pdo($dbname) {
  return new PDO("mysql:host=localhost;dbname=" . $dbname, DB_USER, DB_PASS);
}

function db_connect() {
  $db = new MainDatabase(DB_NAME);
  return $db->pdo;
}
