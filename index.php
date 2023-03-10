<?php include "connection_check.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mysql_B.Task2</title>
</head>
<body>
  <div class="section">
    <div class="containner">
    <h2>Registration Form</h2>
      <form action="database_connection.php" method="post">

        <section>
        <label for="emp_code">Employee Code</label>
        <input type="text" name="emp_code" id="emp_code">
        </section>

        <section>
        <label for="emp_code_name">Employee Code Name</label>
        <input type="text" name="emp_code_name" id="emp_code_name">
        </section>

        <section>
        <label for="emp_domain">Employee Domain</label>
        <input type="text" name="emp_dom" id="emp_dom">
        </section>

        <section>
          <label for="emp_id">Employee Id</label>
          <input type="text" name="emp_id" id="emp_id" >
        </section>
        <section>
        <label for="emp_salary">Employee Salary</label>
        <input type="number" name="emp_sal" id="emp_sal">
        </section>

        <section>
        <label for="emp_fname">First Name</label>
        <input type="text" name="fname" id="fname">
        </section>

        <ection>
        <label for="emp_lname">Last Name</label>
        <input type="text" name="lname" id="lname">
        </section>

        <section>
        <label for="graduation_percentage">Graduation Percentage</label>
        <input type="number" name="grad_per" id="grad_per">
        </section>

        <button type="submit" name="submit" id="submit">Submit</button>
      </form>
    </div>
  </div>
</body>
</html>