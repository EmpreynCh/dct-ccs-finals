<?php
    require_once('../../functions.php');

    // Apply guard to prevent unauthenticated access
    guard();

    $_SESSION['currentPage'] = 'Students';

    require_once('../partials/header.php');
    require_once('../partials/side-bar.php');

    $students_subjects = getStudentsSubjectsById($_GET['assignGradeID']);

    if (!isset($_GET['assignGradeID']) || empty($students_subjects)) {
        header("Location: attach-subject?studentID=" . $_GET['studentID']);
        exit;
    }
    
    $errors = [];
    if (isset($_POST['assign_grade'])) {
        $grade = $_POST['grade'] ?? '';

        $subject_grade_data = ['grade' => $grade];
        $errors = validateStudentsSubjectGrade($subject_grade_data);
        
        if (empty($errors)) {
            if (assignStudentGrade($students_subjects['student_subject_id'], $subject_grade_data)) {
                header("Location: attach-subject?studentID=" . $students_subjects['student_id']);
                exit;
            } 
        }
    }
?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Assign Grade to Subject</h1>
        <div class="row mt-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="../dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="register">Register Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="attach-subject?studentID=<?php echo $students_subjects['student_id']; ?>">Attach Subject to Student</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Grade to Subject</li>
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
                                    <h1 class="h4 mb-2 fw-normal">Select Student and Subject Information</h1>
                                    <div class="container">
                                        <ul>
                                            <li><strong>Student ID: </strong><?php echo $students_subjects['student_code']; ?></li>
                                            <li><strong>Name: </strong><?php echo $students_subjects['first_name']; ?> <?php echo $students_subjects['last_name']; ?></li>
                                            <li><strong>Subject Code: </strong><?php echo $students_subjects['last_name']; ?></li>
                                            <li><strong>Subject Name: </strong><?php echo $students_subjects['last_name']; ?></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <form method="post" action="" class="mb-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="grade" name="grade" placeholder="Student ID" value="<?php echo !empty($students_subjects) ? $students_subjects['grade'] : ''; ?>">
                                            <label for="grade" class="opacity-75">Student ID</label>
                                        </div>
                                        <a href="attach-subject?studentID=<?php echo $students_subjects['student_id']; ?>" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" name="assign_grade" class="btn btn-primary">Assign Grade to Subject</button>
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