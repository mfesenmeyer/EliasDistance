<?php

//Include DB connection as $conn
include'db_connection.php';

//Pass company parameter from JavaScript 
if(isset($_POST['company']) && !empty($_POST['company'])) {
 $company = $_POST['company'];
}

//Sql Query to select attributes from UI specified company
$sql_comp = "SELECT * FROM myDB.screener WHERE company_name=?";
//SQL Query to select all other rows that aren't the company submitted
$sql_all = "SELECT * FROM myDB.screener WHERE company_name!=?";


//Prepare the statement to prevent SQL injection
$stmt_comp = $conn->prepare($sql_comp);
$stmt_all = $conn->prepare($sql_all);

if($stmt_comp === false || $stmt_all === false) {
  trigger_error('Wrong SQL: ' . $sql_comp . 'or '. $sql_all . ' Error: ' . $conn->errno . ' ' . $conn->error, E_USER_ERROR);
}

//Bind company parameter to SQL statement
$stmt_comp->bind_param('s', $company);
$stmt_comp->execute();
$result_comp = $stmt_comp->get_result();
//Pass the results to an associative array
$company1_assoc = $result_comp->fetch_assoc();

//Bind company parameter to SQL statement
$stmt_all->bind_param('s', $company);
$stmt_all->execute();
$result_all = $stmt_all->get_result();


$company1 = array();
// Think about eventually creating this into a for loop with $compnay1_assoc.length 
array_push($company1, $company1_assoc["attribute_1"], $company1_assoc["attribute_2"], 
  $company1_assoc, $company1_assoc["attribute_4"], $company1_assoc["attribute_5"]
  , $company1_assoc["attribute_6"], $company1_assoc["attribute_7"], $company1_assoc["attribute_8"]
  , $company1_assoc["attribute_9"]);
 

if ($result_all ->num_rows > 0) {
  //Pass the results to an associative array
    while($company_all = $result_all->fetch_assoc()) {
          
        $company_name = $company_all["company_name"];
        $company_ticker = $company_all["company_id"];

        $company_attributes = array();
        array_push($company_attributes, $company_all["attribute_1"], $company_all["attribute_2"], 
        $company_all["attribute_3"], $company_all["attribute_4"], $company_all["attribute_5"]
        , $company_all["attribute_6"], $company_all["attribute_7"], $company_all["attribute_8"]
        , $company_all["attribute_9"]);

      
        eliasDistanceCalculation($company_name, $company_ticker, $company_attributes);

    }
} else {
    echo "Connection Error with second Query";
}

/** Calculation Function **/
function eliasDistanceCalculation($company_name, $company_ticker, $company_attributes) {
    echo $company_name;
    echo $company_ticker;
    
    global $company1;

    $results = array();
    for ($x = 0; $x <= count($company1) - 1; $x++) {
        $diff = (float)$company1[$x] - (float)$company_attributes[$x];
        $results[$x] = pow(2, $diff);
    } 

    $attribute_sum = array_sum($results);
    $eliasDistance = sqrt($attribute_sum);

    echo '<thead>
        <tr>
          <th>#</th>
          <th>Company</th>
          <th>Ticker</th>
        <th>Elias Distance</th>
      </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>' .$company_name .'</td>
          <td>' .$company_ticker . '</td>
          <td>' . $eliasDistance . '</td>
        </tr>
    </tbody>';
//mysql_close($connection); // Connection Closed

}

?>