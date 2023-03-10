<?php
// Linkage for connecting php with the database.
include "connection_check.php";

/**
 * This class is used for fetching the values from the form,
 * checking table existance
 * creating table if not present any.
 * uploading data to the database,
 * and storing table information.
 */
class ConnectDb {

  /**
   * This function is used for fetching the values of the form and storing it in variables.
   * @return details 
   *  It returns array of arrays that contains the table name and the values to be inserted.
   */
  function fetchingValues() {
    $emp_id = $_POST["emp_id"];
    $first_name = $_POST["fname"];
    $last_name = $_POST["lname"];
    $grad_per = $_POST["grad_per"] . "%";
    $emp_sal = $_POST["emp_sal"] . "K";
    $emp_code = $_POST["emp_code"];
    $emp_code_name = $_POST["emp_code_name"];
    $emp_dom = $_POST["emp_dom"];
    $employee_code_table_values = array("employee_code_table",$emp_code,$emp_code_name,$emp_dom);
    $employee_salary_table_values = array("employee_salary_table",$emp_id,$emp_sal,$emp_code);
    $employee_details_table_values = array("employee_details_table",$emp_id,$first_name,$last_name,$grad_per);
    $details = array($employee_code_table_values,$employee_salary_table_values,$employee_details_table_values);  
    return $details;
  }

  /**
   * This contains the information about table.
   * It stores the table name and column name of each table in an array.
   * @return array
   *  It returns an array of array containing the names of all the table and names of its colums.
   */
  function storingTableInfo() {
    $employee_code_table_details = array("employee_code_table","employee_code","employee_code_name","employee_domain");
    $employee_salary_table_details = array("employee_salary_table","employee_id","employee_salary","employee_code");
    $employee_details_table_details = array("employee_details_table","employee_id","employee_first_name","employee_last_name","Graduation_percentile");
    return array($employee_code_table_details,$employee_salary_table_details,$employee_details_table_details);
  } 

  /**
   * It checks if a table has been created beforehand in the database or not.
   * @param con
   *  $con stores the object of mysqli($serverName,$userName,$password,$dbName), which is neededd for database connection.
   * @param table_name
   *  It stores the name of the table whose existance is being checked.
   * @return 0/1
   *  It returns 0 if table exists beforehand and returns 1 if no table with that name exist.
   */
  function checkTableExistance($con,$table_name) {
    $check_existance = 'select 1 from '.$table_name.' LIMIT 1';
    try {
      $con->query($check_existance);
      return 0;
    }
    catch (Exception $e) {
      return 1;
    }
  }

  /**
   * This function is used for creating table.
   * @param table_details
   *  It accepts the table details where name of the table and the name of its columns are stored in an array.
   * @param con
   *  $con stores the object of mysqli($serverName,$userName,$password,$dbName), which is neededd for database connection.
   */
  function createTable($table_details,$con) {
    $table_name =  array_shift($table_details);
    $store = "";
    for ($i=0; $i<count($table_details);$i++){
      if($i == 0){
        $store = $table_details[$i] . " VARCHAR(255) PRIMARY KEY,";
      }
      elseif($i == count($table_details)-1) {
        $store = $store . $table_details[$i] . " VARCHAR(255)";
      }
      else {
        $store = $store . $table_details[$i] . " VARCHAR(255) ," ;
      }
    }
    $sql_table_creation = "CREATE TABLE ".$table_name." (". $store.")";
    $con->query($sql_table_creation);
  }


  /**
   * This function is used for inserting values in a table.
   * @param value_details
   *  It stores the name of the table and the corresponing values to be entered in an array format.
   * @param con
   *  $con stores the object of mysqli($serverName,$userName,$password,$dbName), which is neededd for database connection.
   */
  function insertValues($value_details,$con){
    $table_name = array_shift($value_details);
    $qry ="";
    for ($i=0;$i<count($value_details);$i++){
      if($i == count($value_details)-1) {
        $qry = $qry ."'".$value_details[$i]."'";
      }
      elseif($i == 0) {
        $qry = "'".$value_details[$i]."'" . ",";
      }
      else {
         $qry = $qry ."'". $value_details[$i]."'" . ",";
      }
    }
    $query1 ="INSERT INTO ".$table_name." VALUES (" . $qry .");";
    $con->query($query1);
  }
  
}

/**
 * This class is used for storing the queries,questions and displaying it.
 */
class QueryResults {

  /**
   * This function stores all the question and queries in two different arrays and prints them using loop.
   * @param con
   *  $con stores the object of mysqli($serverName,$userName,$password,$dbName), which is neededd for database connection.
   */
  function printResult($con) {
    // Storing all the queries in an array.
    $qry = array(
    "SELECT employee_first_name FROM employee_details_table WHERE employee_id IN (SELECT employee_id FROM employee_salary_table WHERE employee_salary > '50k');",
    "SELECT employee_last_name FROM employee_details_table WHERE Graduation_percentile >70;  ",
    "SELECT employee_code_name FROM employee_code_table WHERE employee_code IN (SELECT employee_code FROM employee_salary_table WHERE employee_id IN (SELECT employee_id FROM employee_details_table WHERE Graduation_percentile <70));",
    "SELECT employee_first_name,employee_last_name FROM employee_details_table WHERE employee_id = ANY (SELECT employee_id FROM employee_salary_table where employee_code = ANY(SELECT employee_code FROM employee_code_table WHERE employee_domain <> 'JAVA'));",
    "SELECT sum(employee_salary ),employee_domain FROM employee_salary_table CROSS JOIN employee_code_table WHERE employee_salary_table.employee_code = employee_code_table.employee_code GROUP BY employee_code_table.employee_domain;",
    "SELECT sum(employee_salary ),employee_domain FROM employee_salary_table CROSS JOIN employee_code_table WHERE employee_salary_table.employee_code = employee_code_table.employee_code AND employee_salary_table.employee_salary > 30 GROUP BY employee_code_table.employee_domain;",
    "SELECT employee_id FROM employee_salary_table WHERE employee_code IS NULL;"
    );
    // Storing all the questions in an array.
    $questions = array(
    "WAQ to list all employee first name with salary greater than 50k.",
    "WAQ to list all employee last name with graduation percentile greater than 70%.",
    "WAQ to list all employee code name with graduation percentile less than 70%. ",
    "WAQ to list all employee’s full name that are not of domain Java. ",
    "WAQ to list all employee_domain with sum of it's salary. ",
    "Write the above query again but dont include salaries which is less than 30k.",
    "WAQ to list all employee id which has not been assigned employee code. "
    );
    // Printing the questions and query result. 
    for ($i = 0; $i < count($questions); $i++) {
      echo "<br><br>";
      echo $questions[$i];
      echo "<br><br>";
      $res = $con->query($qry[$i]);
      while ($result = $res->fetch_assoc()) {
        foreach ($result as $r)
          echo $r . " ";
        echo "<br>";
      }
    }
  }

}

// Creating an object of the class ConnectDb
$cdb = new ConnectDb;
// Calling the functions of te class.
$value_details = $cdb->fetchingValues();
$table_details = $cdb->storingTableInfo();
foreach($table_details as $td) {
  if($cdb->checkTableExistance($con,$td[0]) == 1) {
    $cdb->createTable($td,$con);
  }
}
foreach($value_details as $vd){
  $cdb->insertValues($vd,$con);
}

// Creating an object of the querryResults class.
$fqr = new QueryResults;
// Calling the function of the class.
$fqr->printResult($con);
