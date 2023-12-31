<?php
require_once "../config.php";
 $name = $salary = $dob =$company="";
$name_err = $salary_err = $dob_err=$company_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
   
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } elseif($input_salary < 10000 || $input_salary > 50000) {
        $salary_err = "Salary must be between 10,000 and 50,000.";
    } else{
        $salary = $input_salary;
    }

    // Validate dob
    $input_dob = trim($_POST["dob"]);
    if(empty($input_dob)){
        $dob_err = "Please enter an date of birth.";     
    } else{
        $dob = $input_dob;
    }

    // Validate company
    $input_company = trim($_POST["company"]);
    if(empty($input_company)){
        $company_err = "Please enter company.";     
    } else{
        $company = $input_company;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($salary_err) && empty($dob_err)&&empty($company_err)){
       $sql = "INSERT INTO employee (name,salary, dob, company) VALUES (?, ?, ?,?)";         
        if($stmt = mysqli_prepare($link, $sql)){
           mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_salary,$param_dob, $param_company);
            $param_name = $name;
            $param_salary = $salary;
            $param_dob = $dob;
            $param_company = $company;            
            if(mysqli_stmt_execute($stmt)){
                header("location: ../employee.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control <?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>">
                        <span class="invalid-feedback"><?php echo $dob_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Company</label>
                            <textarea name="company" class="form-control <?php echo (!empty($company_err)) ? 'is-invalid' : ''; ?>"><?php echo $company; ?></textarea>
                            <span class="invalid-feedback"><?php echo $company_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../employee.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const salaryInput = document.getElementById("salary");
        const salaryError = document.querySelector(".invalid-feedback");
        
        salaryInput.addEventListener("blur", function () {
            const inputValue = parseInt(salaryInput.value);
            
            if (isNaN(inputValue) || inputValue < 10000 || inputValue > 50000) {
                salaryError.textContent = "Salary must be between 10,000 and 50,000.";
                salaryInput.classList.add("is-invalid");
            } else {
                salaryError.textContent = "";
                salaryInput.classList.remove("is-invalid");
            }
        });
    });
</script>
</html>