<?php
$servername = "localhost";
$dbname = "danDB";
$fields = $values = array();

try {
  $username = $_POST["username"];
  $password = $_POST["password"];
  unset($_POST["username"]);
  unset($_POST["password"]);

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach( array_keys($_POST) as $key ) {
    if (empty($_POST[$key])) {
      continue;
    }
    $fields[] = "`$key`";
    $values[] = $conn->quote($_POST[$key]);
  }

  $fields = implode(",", $fields);
  $values = implode(",", $values);

  $sql = "INSERT INTO person ($fields) VALUES ($values)";
  $stmt = $conn->prepare($sql);
  $rows = $stmt->execute();
  echo "<p style=\"color:white;\">PASSED: Person added</p>";

} catch(PDOException $e) {
  echo "<p style=\"color:white;\">FAILED: " . $e->getMessage() . "</p>";
}
?>
