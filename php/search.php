<?php
echo "<!DOCTYPE html>";
echo "<html>";
echo "<link rel=\"stylesheet\" href=\"https://www.w3schools.com/w3css/4/w3.css\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/styles.css\">";
echo "<body class=\"w3-black w3-text-light-grey\">";
echo "<table class=\"w3-table w3-border w3-bordered\";'>";
echo "<tr class=\"w3-light-grey\">
<th>id</th>
<th>firstName</th>
<th>middleName</th>
<th>lastName</th>
<th>dateOfBirth</th>
<th>dateOfDeath</th>
<th>placeOfBirth</th>
<th>gender</th>
<th>profession</th>
<th>misc</th>
<th>fatherId</th>
<th>motherId</th>
<th>created</th>
<th>updated</th>
</tr>";

class TableRows extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() {
    return "<td>" . parent::current(). "</td>";
  }

  function beginChildren() {
    echo "<tr>";
  }

  function endChildren() {
    echo "</tr>" . "\n";
  }
}

$servername = "localhost";
$username = "guest";
$password = "guest";
$dbname = "danDB";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $search = $_GET["search"];
  echo "<h2>Search Results: $search</h2>";

  if (empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM person");
  } else {
    $stmt = $conn->prepare("SELECT * FROM person WHERE $search");
  }

  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

} catch(PDOException $e) {
  echo "Fetch info failed:\n" . $e->getMessage();
  echo "\n";
}
$conn = null;
echo "</table>";
echo "</body>";
echo "</html>";
?>
