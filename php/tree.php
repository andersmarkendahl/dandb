<?php
echo "<!DOCTYPE html>";
echo "<html>";
echo "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">";
echo "<body class=\"\">";
echo "<h1 class=\"w3-xxlarge w3-wide w3-center\">Family Tree</h1>";
echo "<div class=\"w3-row w3-padding\">";

$servername = "localhost";
$username = "guest";
$password = "guest";
$dbname = "danDB";

function printTree($db, $startid) {

  $key = array_search($startid, array_column($db, 'id'));
  $startperson = $db[$key];
  printPerson($startperson);

  $key = array_search($startperson['fatherId'], array_column($db, 'id'));
  $father = $db[$key];
  printPerson($father);

  $key = array_search($startperson['motherId'], array_column($db, 'id'));
  $mother = $db[$key];
  printPerson($mother);

}

function printPerson($person) {
  $id = $person['id'];
  $firstName = $person['firstName'];
  $lastName = $person['lastName'];
  $gender = $person['gender'];

  echo "<div class=\"w3-center w3-display-container w3-col w3-card s3 m2 l1 w3-blue w3-padding\">";
  echo "<b>";
  echo "<p style=\"font-style: italic;\" class=\"w3-display-topright w3-padding\">$id</p>";
  echo "<p>$firstName<br>$lastName<br>$gender</p>";
  echo "</b>";
  echo "</div>";
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
echo "</div>";
echo "</body>";
echo "</html>";
?>
