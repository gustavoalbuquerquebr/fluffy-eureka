<?php

class MainDatabase extends Database {

  public function populate_people_table() {

    $query = "INSERT INTO people (name, age, city)
              VALUES ('Harry Potter', 17, 'London'),
              ('Hermione Granger', 17, 'London'),
              ('Severus Snape', 40, 'Hogwarts')";
    
    $this->pdo->exec($query);
  }

  public function create_people_table() {
    $query = "CREATE TABLE people(
      id int PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(50) NOT NULL,
      age INT NOT NULL,
      city VARCHAR(50) NOT NULL
    )";

    $stmt = $this->pdo->exec($query);

    $this->populate_people_table();
  }
}
