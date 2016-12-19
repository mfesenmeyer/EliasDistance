<?php

//Include DB connection as $conn
include'db_connection.php';
include 'company.php';

//Pass company parameter from JavaScript 
 $company = $_POST['company'];
 $amount = $_POST['amount'];
 $attributes = $_POST['attributes'];

//Eventually pass from JavaScript 
$user_attributes = $attributes;

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

$unsorted_companies = array();
$company1 = array();


// Think about eventually creating this into a for loop with $compnay1_assoc.length 


//Pass in the correct attributes to run the Elias distance based on user input (Company entered)
switch ($user_attributes) {
    case 0:
        array_push($company1, $company1_assoc["attribute_1"], $company1_assoc["attribute_2"], 
        $company1_assoc["attribute_3"], $company1_assoc["attribute_4"], $company1_assoc["attribute_5"]
        , $company1_assoc["attribute_6"], $company1_assoc["attribute_7"], $company1_assoc["attribute_8"]
        , $company1_assoc["attribute_9"], $company1_assoc["attribute_10"]);
        break;
    case 1:
        array_push($company1, $company1_assoc["attribute_1"], $company1_assoc["attribute_2"], 
        $company1_assoc["attribute_3"]);
        break;
    case 2:
        array_push($company1, $company1_assoc["attribute_4"], $company1_assoc["attribute_5"]
        , $company1_assoc["attribute_6"]);
        break;
    case 3:
        array_push($company1, $company1_assoc["attribute_7"], $company1_assoc["attribute_8"]
        , $company1_assoc["attribute_9"], $company1_assoc["attribute_10"]);
        break;
}

// Conditional check to make sure company entered is in our DB
if ($company1_assoc["attribute_1"] != null){

if ($result_all ->num_rows > 0) {
  //Pass the results to an associative array
    while($company_all = $result_all->fetch_assoc()) {
          
        $company_name = $company_all["company_name"];
        $company_ticker = $company_all["company_id"];

        $company_attributes = array();

      //Pass in the correct attributes to run the Elias distance based on user input (All other companines)
      switch ($user_attributes) {
          case 0:
              array_push($company_attributes, $company_all["attribute_1"], $company_all["attribute_2"], 
              $company_all["attribute_3"], $company_all["attribute_4"], $company_all["attribute_5"]
              , $company_all["attribute_6"], $company_all["attribute_7"], $company_all["attribute_8"]
              , $company_all["attribute_9"], $company_all["attribute_10"]);
              break;
          case 1:
              array_push($company_attributes, $company_all["attribute_1"], $company_all["attribute_2"], 
              $company_all["attribute_3"]);
              break;
          case 2:
              array_push($company_attributes, $company_all["attribute_4"], $company_all["attribute_5"]
              , $company_all["attribute_6"]);
              break;
          case 3:
              array_push($company_attributes, $company_all["attribute_7"], $company_all["attribute_8"]
              , $company_all["attribute_9"], $company_all["attribute_10"]);
              break;
      }


        //Returns the Company object with name, ticker and elias Distance
        $companyResult = eliasDistanceCalculation($company_name, $company_ticker, $company_attributes);
        //Push the Companies being returned to an array of Unsorted Companies         
        array_push($unsorted_companies, $companyResult);

    }

        //Comparator function to sort based on eliasDistance attribute
        usort($unsorted_companies, function($a, $b)
        {
            return strcmp($a->eliasDistance, $b->eliasDistance);
        });

        $filtered_companies = array_slice($unsorted_companies, 0, $amount);

    //Echo the results 
     echo'

     <thead>
        <tr>
          <th>#</th>
          <th>Company</th>
          <th>Ticker</th>
        <th>Elias Distance</th>
      </tr></thead>
      <tbody>'; 
        
        //For each company that's now sorted, print out the index, comapny name ticker and elias Distance        
        $company_counter = 1;  
        foreach ($filtered_companies as $company) {  
          echo '<tr><th scope="row">' .$company_counter. '</th><td>' . $company->name . '</td><td>' . $company->ticker . "</td><td>" . $company->eliasDistance . "</td></tr>";
          $company_counter++;
        }
      echo '</tbody>';
          
} else {
    echo "Connection Error with second Query";
}

}
else{
  echo 'Sorry the company you entered is unrecognizable. Please try again.';
}


/******* HELPER METHOD **********/
/** Elias Distance Calculation 

Parameters - Name, Ticker, Array of attributes 
Returns - Name, Ticker, Elias Distance Result as a Company Object 

**/
function eliasDistanceCalculation($company_name, $company_ticker, $company_attributes) {    
    global $company1;

    $results = array();
    for ($x = 0; $x <= count($company1) - 1; $x++) {
        $diff = (float)$company1[$x] - (float)$company_attributes[$x];
        $results[$x] = pow($diff, 2);
    } 
    
    $attribute_sum = array_sum($results);
    $eliasDistance = sqrt($attribute_sum);
  
    //Company Constructor and Function return value  
    return $company_obj = new Company($company_name, $company_ticker, $eliasDistance);
  
}

?>