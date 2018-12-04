<?php

class Database {

  public $name;
  public $pdo;

  public function __construct($db_name) {
    $this->name = $db_name;

    try {
      $this->pdo = full_pdo($this->name);
      !$this->has_table("people") && $this->create_people_table();

    } catch (Exception $e) {
      $this->pdo = simple_pdo();
      $this->save();
      $this->pdo = full_pdo($this->name);
      $this->create_people_table();

    } finally {
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
  }
  
  public function save() {
    $query = "CREATE DATABASE " . $this->name;
    $this->pdo->query($query);
  }

  public function has_table($table) {
    $tables_in_db = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    return in_array($table, $tables_in_db);
  }

}
