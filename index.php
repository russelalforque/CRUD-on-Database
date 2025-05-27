<?php
// Database connection and functions file
include("script.php");

// Process form submissions (Add, Update, Delete)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    $student_id = isset($_POST['student-id-hidden']) ? intval($_POST['student-id-hidden']) : 0; // use hidden input here
    $lname = trim($_POST['last-name']);
    $fname = trim($_POST['first-name']);
    $sex = trim($_POST['sex']);
    $course = trim($_POST['course']);
    $message = "";

    // Collect preferred modalities from checkboxes
    $modalities = [];
    if(isset($_POST['online'])) $modalities[] = 'online';
    if(isset($_POST['face-to-face'])) $modalities[] = 'face-to-face';
    if(isset($_POST['hybrid'])) $modalities[] = 'hybrid';

    $preferred_modality = implode(", ", $modalities);

    switch ($action) {
        case "Add":
            if ($student_id !== 0) {
                $message = "Cannot add because Student ID must be blank.";
            } else {
                if (createStudent($conn, $lname, $fname, $sex, $course, $preferred_modality)) {
                    $message = "Student added successfully.";
                } else {
                    $message = "Error adding student.";
                }
            }
            break;

        case "Update":
            if ($student_id === 0) {
                $message = "Cannot update because Student ID is blank.";
            } else {
                if (updateStudent($conn, $student_id, $lname, $fname, $sex, $course, $preferred_modality)) {
                    $message = "Student updated successfully.";
                } else {
                    $message = "Error updating student.";
                }
            }
            break;

        case "Delete":
            if ($student_id === 0) {
                $message = "Cannot delete because Student ID is blank.";
            } else {
                if (deleteStudent($conn, $student_id)) {
                    $message = "Student deleted successfully.";
                } else {
                    $message = "Error deleting student.";
                }
            }
            break;
    }
}

// Filter for search
$filter = "";
$value = "";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["search-by"]) && isset($_GET["search-field"])) {
    $filter = $_GET["search-by"];
    $value = $_GET["search-field"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="styles.css" />
  <title>CC225 Final Exam</title>
</head>

<body>
  <div class="wrapper">
    <div class="container" id="student-registration">
      <h1 id="student-registration-title">Student Registration</h1>
      <form id="student-form" method="POST" action="">
        <div class="input-group">
          <label for="student-id">Student's ID:</label>
          <input type="text" class="text-field" name="student-id" id="student-id" placeholder="Auto-Generated" disabled />
          <input type="hidden" name="student-id-hidden" id="student-id-hidden" />
        </div>

        <div class="input-group">
          <label for="last-name">Last Name:</label>
          <input type="text" class="text-field" name="last-name" id="last-name" placeholder="Enter Last Name" required />
        </div>

        <div class="input-group">
          <label for="first-name">First Name:</label>
          <input type="text" class="text-field" name="first-name" id="first-name" placeholder="Enter First Name" required />
        </div>

        <div class="input-group">
          <label for="sex">Sex:</label>
          <select name="sex" class="text-field" id="sex" required>
            <option hidden disabled selected value>
              -- Select An Option --
            </option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>

        <div class="input-group" id="modality">
          <label for="modality">Preferred Mode of Classes</label>
          <div class="modality-choices">
            <div class="modality-group">
              <input type="checkbox" name="online" id="online" value="online" />
              <label for="online">Online</label>
            </div>
            <div class="modality-group">
              <input type="checkbox" name="face-to-face" id="face-to-face" value="face-to-face" />
              <label for="face-to-face">Face-to-Face</label>
            </div>
            <div class="modality-group">
              <input type="checkbox" name="hybrid" id="hybrid" value="hybrid" />
              <label for="hybrid">Hybrid</label>
            </div>
          </div>
        </div>

        <div class="input-group">
          <label for="course">Course:</label>
          <input type="text" class="text-field" id="course" name="course" placeholder="Enter Course" required />
        </div>

        <div class="btn-group">
          <input type="submit" name="action" value="Add" class="btn" />
          <input type="submit" name="action" value="Update" class="btn" />
          <input type="submit" name="action" value="Delete" class="btn" />
          <input type="button" id="clear-btn" value="Clear" class="btn" />
        </div>
      </form>
      <p class="message"><?php echo htmlspecialchars($message ?? ''); ?></p>
    </div>

    <form class="container" method="GET" action="" id="select-student">
      <div class="search-by-grp">
        <label for="search-by">Search by:</label>
        <select name="search-by">
          <option hidden disabled selected value>Filter</option>
          <option value="student_id">Student ID</option>
          <option value="lname">Last Name</option>
          <option value="fname">First Name</option>
          <option value="sex">Sex</option>
          <option value="course">Course</option>
          <option value="preferred_modality">Preferred Modality</option>
        </select>
        <input type="text" name="search-field" />
        <input type="submit" value="Search" class="btn" />
      </div>
      <div class="search-result-grp">
        <?php displayStudents($filter, $value); ?>
      </div>
    </form>
  </div>

  <script src="script.js"></script>
</body>

</html>
