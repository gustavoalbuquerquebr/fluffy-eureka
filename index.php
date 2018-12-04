<?php

$init_file = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["PROJECT_ROOT"] . "init.php";

if (!file_exists($init_file)) {

  $project_root = str_replace("index.php", "", $_SERVER["PHP_SELF"]);

  $htaccess_path = ".htaccess";
  $htaccess_size = filesize($htaccess_path);
  $htaccess_file = file_get_contents($htaccess_path, $htaccess_size);

  $regex = "[\w\d\s\R\/\\\-]*";
  $pattern = '/SetEnv PROJECT_ROOT "' . $regex . '"/';
  $replacement = 'SetEnv PROJECT_ROOT "' . $project_root . '"';
  $replaced = preg_replace($pattern, $replacement, $htaccess_file);

  file_put_contents($htaccess_path, $replaced);
  exit(header("Refresh:0; url=" . $_SERVER["PHP_SELF"]));
}

require_once $init_file;

if (!empty($_POST)) {

  $person = Person::fetch_by_id($_POST["id"]);
  $person->delete();
  exit;
}

$pdo = db_connect();
$people = Person::fetch_all();


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="style.css">
  <title>PHP</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

  <main class="container">

  <h1 class="my-4">Database</h1>

    <table class="table table-striped table-hover text-center">
      <thead class="thead-dark">
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>City</th>
        <th>(delete)</th>
      </thead>
      <tbody>
        <?php foreach ($people as $person): ?>
          <tr>
            <td><?= $person->id; ?></td>
            <td><?= $person->name; ?></td>
            <td><?= $person->age; ?></td>
            <td><?= $person->city; ?></td>
            <td class="text-danger font-weight-bold">
              <span class="delete_btn" data-id="<?= $person->id; ?>">&times;</span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </main>

  <script src="script.js"></script>
</body>
</html>
