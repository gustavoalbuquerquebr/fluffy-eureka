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

spl_autoload_register("class_autoloader");

function unknown_db($e) {
  return $e->getCode() === UNKNOWN_DB_ERRCODE;
}

function unknown_table($e) {
  return $e->getCode() === UNKNOWN_TABLE_ERRCODE;
}

function populate_tables($pdo) {

  $query = "INSERT INTO people (name, age, city)
            VALUES ('Harry Potter', 17, 'London'),
            ('Hermione Granger', 17, 'London'),
            ('Severus Snape', 40, 'Hogwarts')";
  
  $pdo->exec($query);
}

function create_tables($pdo) {
  $query = "CREATE TABLE people(
    id int PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    city VARCHAR(50) NOT NULL
  )";

  $stmt = $pdo->exec($query);

  populate_tables($pdo);
}

function db_has_tables($pdo) {
    $tables_in_db = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    return in_array("people", $tables_in_db);
}

function create_database() {
  $dns = "mysql:host=" . DB_HOST;
  $pdo = new PDO($dns, DB_USER, DB_PASS);
  
  $query = "CREATE DATABASE revision";
  $pdo->query($query);
}

function create_pdo() {
  $dns = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
  $pdo = new PDO($dns, DB_USER, DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $pdo;
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
