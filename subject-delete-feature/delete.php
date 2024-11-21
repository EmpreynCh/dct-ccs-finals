<?php
include("../../functions.php");
guard();
$Pagetitle = "Delete Subject";


if (isset($_GET['code'])) {
    $subjectCode = $_GET['code'];
    $subject = fetchSubjectDetails($subjectCode);  
    if (!$subject) {
        
        $subjectNotFound = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectCodeToDelete = $_POST['code'] ?? '';
    $subjectNameToDelete = $_POST['subjectName'] ?? '';

   
    error_log("Deleting subject with code: " . $subjectCodeToDelete);
    error_log("Deleting subject with name: " . $subjectNameToDelete);

    if (!empty($subjectCodeToDelete) && !empty($subjectNameToDelete)) {
       
        $deleteResult = deleteSubject($subjectCodeToDelete, $subjectNameToDelete);

        if ($deleteResult) {
            header("Location: add.php?deleted=1");
            exit();
        } else {
            echo "Error deleting subject.";
        }
    } else {
        echo "Missing subject details.";
    }
}

include("../partials/header.php");
include("../partials/side-bar.php");
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">

<div class="container">
        <h1 class="mt-1">Delete Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
            </ol>
        </nav>
        
        
        <div class="card">
            <div class="card-body">
                    
                    <form method="POST">
                    <p>Are you sure you want to delete the following subject record?</p>
                    <ul>
                        <li><strong>Subject Code: <?php echo htmlspecialchars($subject['subject_code'] ?? '');?></strong></li>
                        <li><strong>Subject Name: <?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?></strong></li>
                    </ul>
                    <input type="hidden" name="code" value="<?php echo htmlspecialchars($subject['subject_code'] ?? ''); ?>">
                    <input type="hidden" name="subjectName" value="<?php echo htmlspecialchars($subject['subject_name'] ?? ''); ?>">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php';">Cancel</button>

                        <button type="submit" class="btn btn-primary">Delete Subject Record</button>
                    </form>
            </div>
        </div>
    </div>
</main>
<?php
include('../partials/footer.php');
?>