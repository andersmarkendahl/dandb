<?php
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/tree.css\">\n";
echo "<body>\n";
echo "<h1 class=\"w3-wide w3-center\" style=\"font-size:2vw;\">Genealogy Tree</h1>\n";
echo "<div class=\"tree w3-center\">\n";
echo "<ul>\n";

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

  echo "<li>\n";
  printPerson($root);
  echo "<ul>\n";
  printTree($db, $root['fatherId']);
  printTree($db, $root['motherId']);
  echo "</ul>\n";
  echo "</li>\n";

}

function printPerson($person) {
  $firstName = $person['firstName'];
  $lastName = $person['lastName'];
  $gender = $person['gender'];
  $dob = $person['dateOfBirth'];
  $dod = $person['dateOfDeath'];
  $id = $person['id'];

  if ($gender == 'M') {
    $color = "lightgreen";
  } elseif ($gender == 'F') {
    $color = "powderblue";
  }

  echo "<a href=\"#\" style=\"background-color: $color;\">$firstName<br>$lastName</a>\n";
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

echo "</ul>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";
?>
