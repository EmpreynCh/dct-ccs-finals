<?php
session_start();
$pageTitle = "Delete Student Record";
include('../../functions.php');
include('../partials/header.php');
$studentToDelete = null;
$errors = [];

if (isset($_GET['student_id'])) {
    $student_id = sanitize_input($_GET['student_id']);
    $studentToDelete = getSelectedStudentById($student_id); 

    if (!$studentToDelete) {
        $errors[] = "Student not found.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = sanitize_input($_POST['student_id']);

    $deleteStudent = deleteStudentById($student_id);

    if ($deleteStudent) {

        header("Location: register.php");
        exit;
    } else {
        $errors[] = "Failed to delete the student record. Please try again.";
    }
}
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Delete a Student</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="register">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
                </ol>
            </nav>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h1 class="h4 mb-2 fw-normal">Are you sure you want to delete the following student record?</h1>
                                    <div class="container">
                                        <ul>
                                            <li><strong>Student ID: </strong><?php echo $student['student_id']; ?></li>
                                            <li><strong>First Name: </strong><?php echo $student['first_name']; ?></li>
                                            <li><strong>Last Name: </strong><?php echo $student['last_name']; ?></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <a href="register" class="btn btn-secondary">Cancel</a>
                                    <a href="delete?studentID=<?php echo $_GET['studentID']; ?>&deleteStatus=true" class="btn btn-primary">Delete Student Record</a>
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