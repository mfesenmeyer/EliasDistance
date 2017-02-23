<?php

include'db_connection.php';

$sql = "SELECT company_name FROM myDB.screener";
$result = $conn->query($sql);

$company_names = array();

if($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($company_names, $row["company_name"]);
    }
} else {
    echo "0 results";
}

echo json_encode($company_names);


?>