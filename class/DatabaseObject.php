<?php

class DatabaseObject {

  public static $table;

  public static function fetch_all() {
    $pdo = db_connect();
    $query = "SELECT * FROM " . static::$table;

    $stmt = $pdo->query($query);
    $people = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Person");

    return $people;
  }

  public static function fetch_by_id($id) {
    
    $pdo = db_connect();
    $query = "SELECT * FROM " . static::$table;
    $query .= " WHERE id = :id";

    $stmt= $pdo->prepare($query);
    $stmt->execute(["id" => $id]);
    $person = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Person");

    return $person[0];
  }

  public function save() {
    empty($this->id) ? $this->insert() : $this->update();
  }

  public function attributes() {
    $attributes = [];

    foreach ($this as $key => $val) {
      if ($key !== "id") $attributes[$key] = $val;
    }

    return $attributes;
  }

  public function insert() {

    $attributes = $this->attributes();
    $placeholder = implode(", ", array_fill(0, 3, "?"));
    $keys = implode(", ", array_keys($attributes));
    $values = implode("', '", array_values($attributes));


    $pdo = db_connect();
    $query = "INSERT INTO " . static::$table . " (";
    $query .= $keys . ") VALUES (";
    $query .= $placeholder . ")";

    $stmt = $pdo->prepare($query);
    $stmt->execute(array_values($attributes));

    $id = $this->id = $pdo->lastInsertId();

    return $id;
  }
  
  public function update() {

    $attributes = $this->attributes();
    $attributes_string = "";

    foreach ($attributes as $key => $val) {
      $attributes_string .= ", $key = ?";
    }

    $attributes_string = substr($attributes_string, 2);
    
    $pdo = db_connect();
    $query = "UPDATE " . static::$table;
    $query .= " SET $attributes_string ";
    $query .= "WHERE id = $this->id";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $attributes["name"]);
    $stmt->bindValue(2, $attributes["age"]);
    $stmt->bindValue(3, $attributes["city"]);
    $stmt->execute();
  }

  public function delete() {
    $query = "DELETE FROM " . static::$table;
    $query .= " WHERE id = $this->id";

    $pdo = db_connect();
    $pdo->exec($query);
  }
}
