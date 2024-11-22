<?php 
session_start();
$pageTitle = "Assign Grade to Student";

include '../partials/header.php';
include '../../functions.php';
$student_id = $_GET['student_id'] ?? null;
$subject_code = $_GET['subject_code'] ?? null;
$subject = null;


if ($student_id && $subject_code) {
    $student = getSelectedStudentById($student_id);
    $subject = getSubjectByCode($subject_code);
    
    if (!$student || !$subject) {
        $_SESSION['error_message'] = "Invalid student or subject.";
        header("Location: register.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Student or subject not specified.";
    header("Location: register.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grade'])) {
    $grade = $_POST['grade'];

    if ($grade >= 0 && $grade <= 100) {
        if (assignGradeToStudent($student_id, $subject['id'], $grade)) {
            header("Location: attach-subject.php?student_id=" . urlencode($student_id));
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to assign grade.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid grade. Enter a number between 0 and 100.";
    }
}
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Attach Subject to Student</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="register">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
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
                                    <h1 class="h4 mb-2 fw-normal">Selected Student Information</h1>
                                    <div class="container">
                                        <div class="row mb-2">
                                            <div class="col-md-12">
                                                <ul>
                                                    <li><strong>Student ID: </strong><?php echo $student['student_id']; ?></li>
                                                    <li><strong>Name: </strong><?php echo $student['first_name']; ?> <?php echo $student['last_name']; ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php 
                                        $attachedSubjectIds = getAttachedSubjectIds($student['id']);
                                        $fetchedSubjects = fetchSubjects();
                                        $availableSubjects = getAvailableSubjects($fetchedSubjects, $attachedSubjectIds);
                                        
                                        // Render the form
                                        renderAttachSubjectsForm($availableSubjects);
                                    ?>
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
                                    <h1 class="h3 mb-2 fw-normal">Subject List</h1>
                                    <table class="table table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>Subject Code</th>
                                                <th>Subject Name</th>
                                                <th>Subject Grade</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php displayAttachedSubjects($student['id']); ?>
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