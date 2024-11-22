<?php    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function connectDB() {
        $servername = "localhost";
        $username = "root"; // Corrected from $email to $username
        $password = "";
        $dbname = "dct-ccs-finals";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {                    
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
    
    function guard() {  
        if (empty($_SESSION["email"])){
             header("Location:/index.php");
         }
    }
        function returPage(){
            if (!empty($_SESSION["email"])) {
                if (!empty($_SESSION['page'])) { 
                    header("Location:". $_SESSION['page']);
                    exit();
                } else {
                  
                    header("Location: /admin/dashboard.php"); 
                    exit();
                }
            }
        }

        function generateError($message) {
            return '<div class="alert alert-danger alert-dismissible fade show" role="alert"> <strong>Error!</strong> ' . $message . '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> </div>';
        } 
        function generateSuccess($message) {
            return '<div class="alert alert-success alert-dismissible fade show" role="alert">  <strong>Login Success!</strong> ' . $message . '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>  </div>';
        }
        function generateSuccess1($message) {
            return '<div class="alert alert-success alert-dismissible fade show" role="alert">  <strong>Adding Subject Success!</strong> ' . $message . '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>  </div>';
        }
        function loginUser($email, $password) {

            if (empty($email) || empty($password)) {
                return generateError("<li>Email is required </li><li>Password are required.</li>");
            } elseif (!str_ends_with($email, '@gmail.com')) {
                return generateError("<li>Invalid Email and Password format </li>");
            }
            $conn = connectDB();
            $hashedPassword = md5($password); // Hash the password

            $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $_SESSION['email'] = $email;
                return true;
            } else {
                return generateError("<li>Invalid email or password.</li>");
            }
        }
        function logoutUser() {
            session_destroy();
            header("Location:/index.php");
        }

        function insertSubject($subjectCode, $subjectName) {
            $conn = connectDB();
        
            // Check if the table exists, create it if not
            $tableExistsQuery = "SHOW TABLES LIKE 'subjects'";
            $tableExistsResult = $conn->query($tableExistsQuery);
        
            if ($tableExistsResult->num_rows === 0) {
                $createTableQuery = "
                    CREATE TABLE subjects (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        subject_code VARCHAR(50) NOT NULL UNIQUE,
                        subject_name VARCHAR(100) NOT NULL UNIQUE,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )
                ";
                if (!$conn->query($createTableQuery)) {
                    return generateError("<li>Failed to create the 'subjects' table: " . $conn->error . "</li>");
                }
            }
        
            // Validate input
            if (empty($subjectCode) || empty($subjectName)) {
                return generateError("<li>Subject Code is required</li><li>Subject Name is required.</li>");
            }
        
            // Check for duplicates
            $query = "SELECT COUNT(*) as count FROM subjects WHERE subject_code = ? OR subject_name = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $subjectCode, $subjectName);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            if ($row['count'] > 0) {
                return generateError("<li>Duplicate Subject Code or Subject Name</li>");
            } else {
                $insertQuery = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("ss", $subjectCode, $subjectName);
                if ($insertStmt->execute()) {
                    return generateSuccess1("<li>Subject Added Successfully!</li>");
                } else {
                    return generateError("<li>Error adding subject: " . $insertStmt->error . "</li>");
                }
            }
        }
 function displayStudents() {
    $conn = connectDB(); // Use a new local connection

    $query = "SELECT * FROM students";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        echo '<td><a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a></td>';
        echo '</tr>';
    }

    $conn->close(); // Close the connection
}
  
        function fetchSubjectDetails($subjectCode) {
            $conn = connectDB();
            $query = "SELECT * FROM subjects WHERE subject_code = ?";
            $stmt = $conn->prepare($query);
        
            if ($stmt) {
                $stmt->bind_param("s", $subjectCode);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    return $result->fetch_assoc(); // Return the subject details
                }
            }
        
            return null; // Subject not found
        }
        
        function fetchAndDisplaySubjects() {
            $conn = connectDB();
        
            // Check if the table exists
            $checkTableQuery = "SHOW TABLES LIKE 'subjects'";
            $checkResult = $conn->query($checkTableQuery);
        
            if ($checkResult->num_rows === 0) {
                echo '<tr><td colspan="3" class="text-center">Table "subjects" does not exist. Please create the table.</td></tr>';
                return;
            }
        
            // Fetch subjects
            $result = $conn->query("SELECT * FROM subjects");
        
            if ($result->num_rows > 0) {
                while ($subject = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($subject['subject_code']) . '</td>';
                    echo '<td>' . htmlspecialchars($subject['subject_name']) . '</td>';
                    echo '<td>';
                    echo '<a href="edit.php?code=' . urlencode($subject['subject_code']) . '"><button class="btn btn-info">Edit</button></a>';
                    echo ' ';
                    echo '<a href="delete.php?code=' . urlencode($subject['subject_code']) . '"><button class="btn btn-danger">Delete</button></a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3" class="text-center">No subjects found.</td></tr>';
            }
        }
        
        function updateSubject($subjectName, $originalCode) {
            // Validate the input
            if (empty($subjectName)) {
                return generateError1("<li>Subject Name is required.</li>");
            }
            $conn = connectDB();
        
            // Check if the new subject name already exists for another subject code
            $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_name = ? AND subject_code != ?");
            $stmt->bind_param("ss", $subjectName, $originalCode);
            $stmt->execute();
            $result = $stmt->get_result();
            // If a duplicate subject name is found, return an error
            if ($result->num_rows > 0) {
                $stmt->close();
                $conn->close();
                return generateError1("<li>Duplicate entry: Subject Name already exists for another Subject Code.</li>");
            }
        
            // Update the subject name in the database (subject_code remains the same)
            $stmt = $conn->prepare("UPDATE subjects SET subject_name = ? WHERE subject_code = ?");
            $stmt->bind_param("ss", $subjectName, $originalCode);
        
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: /admin/subject/add.php?success=1");
                exit; 
            } else {
                $stmt->close();
                $conn->close();
                return generateError1("<li>Error updating subject name.</li>");
            }
        }
            
            function editSubject($conn, $subjectCode, $subjectName) {
                $sql = "UPDATE subjects SET subject_name = ? WHERE subject_code = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ss", $subjectName, $subjectCode);
                    if ($stmt->execute()) {
                        return true; // Success
                    }
                }
                return false; // Failure
            }

            
            function deleteSubject($subjectCode, $subjectName) {
                // Get the database connection
                $conn = connectDB();
            
                // Check if the connection is valid
                if (!$conn || gettype($conn) !== "object") {
                    return false; // Connection failed
                }
            
                $sql = "DELETE FROM subjects WHERE subject_code = ? AND subject_name = ?";
                $stmt = $conn->prepare($sql);
            
                if ($stmt) {
                    $stmt->bind_param("ss", $subjectCode, $subjectName);
            
                    if ($stmt->execute()) {
                        return true; // Deletion successful
                    } else {
                        error_log("Delete query failed: " . $stmt->error);
                        return false; // Deletion failed
                    }
                } else {
                    error_log("Statement preparation failed: " . $conn->error);
                    return false;
                }
            }
            
            
            function getSubject($conn, $subjectCode) {
                $sql = "SELECT * FROM subjects WHERE subject_code = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("s", $subjectCode);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        return $result->fetch_assoc(); // Return subject data
                    }
                }
                return null; // Subject not found
            }
         
            
              
             