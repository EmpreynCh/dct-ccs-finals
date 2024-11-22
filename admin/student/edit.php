<?php
    session_start();
    $pageTitle = "Edit Student";
    include('../../functions.php');
    include('../partials/header.php'); 

 $errors = [];
    $studentToEdit = null;

    if (isset($_GET['student_id'])) {
        $student_id = sanitize_input($_GET['student_id']);
        $studentToEdit = getSelectedStudentById($student_id); 

        if (!$studentToEdit) {
            $errors[] = "Student not found.";
        }
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
        $updatedData = [
            'student_id' => sanitize_input($_POST['student_id']),
            'first_name' => sanitize_input($_POST['first_name']),
            'last_name' => sanitize_input($_POST['last_name'])
        ];


        $validationErrors = validateStudentData($updatedData);
        $errors = array_merge($errors, $validationErrors);

      
        if (empty($errors)) {
            $updateStatus = updateStudentData($updatedData);

            if ($updateStatus) {
                header("Location: register.php");
                exit;
            } else {
                $errors[] = "Failed to update the student record.";
            }
        }
    }
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Edit Student</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="register">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
                </ol>
            </nav>
            <div class="container ">
                <?php 
                    if (!empty($errors)) {
                        echo renderErrorsToView($errors);
                    }
                ?>
            </div>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <form method="post" action="">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Student ID" value="<?php echo !empty($student) ? $student['student_id'] : ''; ?>">
                                            <label for="student_id" class="opacity-75">Student ID</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo !empty($student) ? $student['first_name'] : ''; ?>">
                                            <label for="first_name">First Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo !empty($student) ? $student['last_name'] : ''; ?>">
                                            <label for="last_name">Last Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <button type="submit" name="edit_student" class="btn btn-primary w-100">Update Student</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </main>
<?php
    require_once('../partials/footer.php');
?>