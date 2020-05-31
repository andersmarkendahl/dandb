<?php
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">\n";
echo "<body class=\"\">\n";
echo "<h1 class=\"w3-wide w3-center\" style=\"font-size:2vw;\">Genealogy Tree</h1>\n";
echo "<div class=\"w3-padding\">\n";

$servername = "localhost";
$username = "guest";
$password = "guest";
$dbname = "danDB";

function printTree($db, $id) {

  if ($id == NULL) {
   return;
  }

  $key = array_search($id, array_column($db, 'id'));
  $root = $db[$key];

  echo "<div class=\"w3-row-padding\">\n";
  echo "<div class=\"w3-cell-middle\">\n";
  printPerson($root);
  echo "</div>\n";
  echo "</div>\n";

  echo "<div class=\"w3-row-padding\">\n";
  echo "<div class=\"w3-cell-middle w3-half\">\n";
  printTree($db, $root['fatherId']);
  echo "</div>\n";
  echo "<div class=\"w3-cell-middle w3-half\">\n";
  printTree($db, $root['motherId']);
  echo "</div>\n";
  echo "</div>\n";


}

function printPerson($person) {
  $firstName = $person['firstName'];
  $lastName = $person['lastName'];
  $gender = $person['gender'];
  $dob = $person['dateOfBirth'];
  $dod = $person['dateOfDeath'];

  if ($gender == 'M') {
    $color = "w3-green";
  } elseif ($gender == 'F') {
    $color = "w3-blue";
  }

  echo "<div class=\"w3-center w3-round-large w3-card $color\" style=\"font-size:1vw;\">\n";
  echo "<b>\n";
  echo "<p>$firstName<br>$lastName<br>$dob - $dod</p>\n";
  echo "</b>\n";
  echo "</div>\n";
}

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = $_GET["generateid"];

  $stmt = $conn->prepare("SELECT * FROM person");
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $fulldb = $stmt->fetchAll();

  printTree($fulldb, $id);

} catch(PDOException $e) {
  echo "Family tree failed:\n" . $e->getMessage();
  echo "\n";
}
$conn = null;

echo "</div>\n";
echo "</body>\n";
echo "</html>\n";
?>
