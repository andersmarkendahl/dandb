<?php
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/tree.css\">\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1 class=\"w3-wide w3-center w3-padding-32\" style=\"font-size:3vw;\">Genealogy Tree</h1>\n";
echo "<div class=\"tree\">\n";

$servername = "localhost";
$username = "guest";
$password = "guest";
$dbname = "danDB";

function printTree($db, $id) {

  if ($id == NULL) {
    return;
  }

  $key = array_search($id, array_column($db, 'id'));
  if ($key === false) {
    return;
  }
  $root = $db[$key];

  echo "<ul>\n";
  echo "<li>\n";
  printPerson($root);
  printTree($db, $root['fatherId']);
  printTree($db, $root['motherId']);
  echo "</li>\n";
  echo "</ul>\n";

}

function printPerson($person) {
  $id = $person['id'];
  $firstName = $person['firstName'];
  $middleName = $person['middleName'];
  $lastName = $person['lastName'];
  $gender = $person['gender'];
  $dob = $person['dateOfBirth'];
  $dod = $person['dateOfDeath'];
  $pob = $person['placeOfBirth'];
  $profession = $person['profession'];
  $misc = $person['misc'];

  if ($gender == 'M') {
    $color = "w3-green";
  } elseif ($gender == 'F') {
    $color = "w3-blue";
  }

  echo "<div class=\"leaf w3-dropdown-hover w3-text-black $color\">\n";
  echo "$firstName $lastName";
  echo "<div class=\"dropdown w3-dropdown-content w3-card w3-padding w3-left-align\">";
  echo "<b>Name:</b> $firstName $middleName $lastName<br>";
  echo "<b>Date of birth:</b> $dob<br>";
  echo "<b>Date of death:</b> $dod<br>";
  echo "<b>Birthplace:</b> $pob<br>";
  echo "<b>Gender:</b> $gender<br>";
  echo "<b>Profession:</b> $profession<br>";
  echo "<b>Additional Info:</b> $misc<br>";
  echo "</div>\n";
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

echo "</ul>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";
?>
