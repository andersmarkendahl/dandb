<?php
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta charset=\"UTF-8\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/tree.css\">\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>Genealogy Tree</h1>\n";
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
    $icon = "9794";
  } elseif ($gender == 'F') {
    $icon = "9792";
  }

  echo "<div class=\"leaf\">\n";
  echo "$firstName <span class=\"icon\">&#$icon</span><br>$lastName";
  echo "<div class=\"dropdown\">";
  echo "<b>Name:</b> $firstName $middleName $lastName<br>";
  echo "<b>Date of birth:</b> $dob<br>";
  echo "<b>Date of death:</b> $dod<br>";
  echo "<b>Birthplace:</b> $pob<br>";
  echo "<b>Gender:</b> $gender<br>";
  echo "<b>Profession:</b> $profession<br>";
  echo "<b>Additional Info:</b><br> $misc<br>";
  echo "</div>\n";
  echo "</div>";
}

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = !empty($_GET['generateid']) && is_string($_GET['generateid']) ?
    $_GET['generateid'] : '52';

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
