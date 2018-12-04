<?php

class Person extends DatabaseObject {

  public static $table = "people";

  public $id;
  public $name;
  public $age;
  public $city;

  public function __construct($name = "", $age = "", $city = "", $id = "") {
    $this->id = $id;
    $this->name = $name;
    $this->age = $age;
    $this->city = $city;
  }
}