<?php
    require_once('../../functions.php');

    // Apply guard to prevent unauthenticated access
    guard();

    $_SESSION['currentPage'] = 'Students';

    require_once('../partials/header.php');
    require_once('../partials/side-bar.php');

    $students_subjects = getStudentsSubjectsById($_GET['dettachSubjectID']);

    if (!isset($_GET['dettachSubjectID']) || empty($students_subjects)) {
        header("Location: register");
        exit;
    }
    
    if (isset($_GET['dettachSubjectStatus']) && $_GET['dettachSubjectStatus'] == "true") {
        if (dettachStudentSubjects($_GET['dettachSubjectID'])) {
            header("Location: attach-subject?studentID=" . $students_subjects['student_id']);
            exit;
        } else {
            echo "Error detaching subject.";
        }
    }
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Dettach Subject to Student</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="register">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="attach-subject?studentID=<?php echo $students_subjects['student_id']; ?>">Attach Subject to Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dettach Subject to Student</li>
                </ol>
            </nav>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h1 class="h4 mb-2 fw-normal">Are you sure you want to dettach this subject from this student record?</h1>
                                    <div class="container">
                                        <ul>
                                            <li><strong>Student ID: </strong><?php echo $students_subjects['student_code']; ?></li>
                                            <li><strong>First Name: </strong><?php echo $students_subjects['first_name']; ?></li>
                                            <li><strong>Last Name: </strong><?php echo $students_subjects['last_name']; ?></li>
                                            <li><strong>Subject Code: </strong><?php echo $students_subjects['last_name']; ?></li>
                                            <li><strong>Subject Name: </strong><?php echo $students_subjects['last_name']; ?></li>
                                        </ul>
                                    </div>
                                    <a href="attach-subject?studentID=<?php echo $students_subjects['student_id']; ?>" class="btn btn-secondary">Cancel</a>
                                    <a href="dettach-subject?dettachSubjectID=<?php echo $students_subjects['student_subject_id']; ?>&dettachSubjectStatus=true" class="btn btn-primary">Dettach Subject from Student</a>
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