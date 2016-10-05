<?php

include'db_connection.php';

//Try to pass parameter from JavaScript & it works bitchezzz
if(isset($_POST['company']) && !empty($_POST['company'])) {

 $company = $_POST['company'];
 echo $company;

}

//Sql query soon going to add in prepared statment 
$sql = "SELECT attribute_1, attribute_2, attribute_3, attribute_4, attribute_5, attribute_6, 
attribute_7, attribute_8, attribute_9 FROM myDB.screener WHERE company_id=?";


$stmt = $conn->prepare($sql);

if($stmt === false) {
  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
}

$stmt->bind_param('s', $company);
$stmt->execute();

$result = $stmt->get_result();
$company1_assoc = $result->fetch_assoc();



$company1 = array();
// Think about eventually creating this into a for loop with $compnay1_assoc.length 
array_push($company1, $company1_assoc["attribute_1"], $company1_assoc["attribute_2"], 
	$company1_assoc["attribute_3"], $company1_assoc["attribute_4"], $company1_assoc["attribute_5"]
	, $company1_assoc["attribute_6"], $company1_assoc["attribute_7"], $company1_assoc["attribute_8"]
	, $company1_assoc["attribute_9"]);




//$company1 = array(0.383,	0.663,	0.044,	0.887,	0.103,	0.233,	0.412,	0.625,	0.506);
$company2 = array(0.674,	0.251,	0.234,	0.943,	0.250,	0.178,	0.894,	0.134,	0.479);


$results = array();

for ($x = 0; $x <= count($company1) - 1; $x++) {
	$diff = $company1[$x] - $company2[$x];
    $squared = pow(2, $diff);
    $results[$x] = sqrt($squared);
    echo 'Pos '. $x .' difference sqrt: ' . $results[$x] . '<br>';
} 

echo '<br>Sum of Diff/Elias Distance?: '.array_sum($results);
//mysql_close($connection); // Connection Closed

?>