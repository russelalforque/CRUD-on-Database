<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cc225_finals';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Student {
    public $student_id;
    public $lname;
    public $fname;
    public $sex;
    public $course;
    public $preferred_modality;

    public function __construct($student_id, $lname, $fname, $sex, $course, $preferred_modality) {
        $this->student_id = $student_id;
        $this->lname = $lname;
        $this->fname = $fname;
        $this->sex = $sex;
        $this->course = $course;
        $this->preferred_modality = $preferred_modality;
    }
}

function createStudent($conn, $lname, $fname, $sex, $course, $preferred_modality) {
    $sql = "INSERT INTO student (lname, fname, sex, course, preferred_modality) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $lname, $fname, $sex, $course, $preferred_modality);
    return $stmt->execute();
}

function updateStudent($conn, $student_id, $lname, $fname, $sex, $course, $preferred_modality) {
    $sql = "UPDATE student SET lname = ?, fname = ?, sex = ?, course = ?, preferred_modality = ? WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $lname, $fname, $sex, $course, $preferred_modality, $student_id);
    return $stmt->execute();
}

function deleteStudent($conn, $student_id) {
    $sql = "DELETE FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    return $stmt->execute();
}

function createStudentCard(Student $student){
    $modality = htmlspecialchars($student->preferred_modality);
    $fname = htmlspecialchars($student->fname);
    $lname = htmlspecialchars($student->lname);
    $sex = htmlspecialchars($student->sex);
    $course = htmlspecialchars($student->course);
    $student_id = htmlspecialchars($student->student_id);

    return "<button type='button' class='student-card'
        data-student-id='{$student_id}'
        data-fname='{$fname}'
        data-lname='{$lname}'
        data-sex='{$sex}'
        data-course='{$course}'
        data-modality='{$modality}'>
          <h1 id='student-name'>{$student->fname} {$student->lname}</h1>
          <p id='student-id'>Student ID: {$student->student_id}<br>Sex: {$student->sex}<br>Course: {$student->course}<br>Preferred Mode of Classes: <br>{$student->preferred_modality}</p>
        </button>";
}

function getStudents($conn, $filter, $value){
    $students = [];
    if($filter !== ""){
        $sql = "SELECT * FROM student WHERE $filter = ? ORDER BY student_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM student ORDER BY student_id DESC";
        $result = $conn->query($sql);
    }

    if ($result !== false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = new Student(
                $row['student_id'], 
                $row['lname'],
                $row['fname'],
                $row['sex'],
                $row['course'],
                $row['preferred_modality']
            );
        }
    }
    return $students;
}

function displayStudents($filter, $value){
    global $conn;
    $students = getStudents($conn, $filter, $value);
    if(count($students) === 0){
        echo "No Students Found";
        return;
    }
    foreach ($students as $student) {
        echo createStudentCard($student);
    }
}
?>
