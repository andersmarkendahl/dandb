<?php
$servername = "localhost";
$dbname = "danDB";
$changes = array();

try {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $id = $_POST["id"];
  unset($_POST["username"]);
  unset($_POST["password"]);
  unset($_POST["id"]);

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  foreach( array_keys($_POST) as $key ) {
    if (empty($_POST[$key])) {
      continue;
    }
    $changes[] = "`$key`" . " = " . $conn->quote($_POST[$key]);
  }

  $changes = implode(",", $changes);

  $sql = "UPDATE person SET $changes WHERE id = $id";
  $stmt = $conn->prepare($sql);
  $rows = $stmt->execute();
  echo "<p style=\"color:white;\">PASSED: $rows person modified</p>";

} catch(PDOException $e) {
  echo "<p style=\"color:white;\">FAILED: " . $e->getMessage() . "</p>";
}
?>
