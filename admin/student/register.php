<?php
    session_start();
    $pageTitle = "Register Student";
    include('../../functions.php');
    include('../partials/header.php'); 

require_once '../../functions.php'; // Ensure the correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = sanitize_input($_POST['student_id']);
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);

    if (empty($student_id) || empty($first_name) || empty($last_name)) {
        echo '<div class="alert alert-danger">All fields are required.</div>';
    } else {
        if (addStudentData($student_id, $first_name, $last_name)) {
            echo '<div class="alert alert-success">Student registered successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error registering student. Please try again.</div>';
        }
    }
}
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Register a New Student</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Register Student</li>
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
                            <div class="row mt-3 mb-3">
                                <div class="col-md-12">
                                    <form method="post" action="">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Student ID" value="<?php echo isset($_POST['student_id']) ? $_POST['student_id'] : ''; ?>">
                                            <label for="student_id" class="opacity-75">Student ID</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>">
                                            <label for="first_name">First Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>">
                                            <label for="last_name">Last Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <button type="submit" name="add_student" class="btn btn-primary w-100">Add Student</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mt-3 mb-3">
                                <div class="col-md-12">
                                    <h1 class="h3 mb-2 fw-normal">Student List</h1>
                                    <table class="table table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php displayStudents(); ?>
                                        </tbody>
                                    </table>
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