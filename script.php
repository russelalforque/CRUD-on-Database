<?php

$host = 'localhost';
$username = 'root'; 
$password = '';
$database = 'test_db';

$conn=new mysqli($host, $username, $password, $database);
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
function createStudentCard(Student $student){
    $card = "<div class='student-card'>

    ";
}

function getStudents(){}

function displayStudents();

//deleteStudent
//updateStudent
//createStudent
//filterStudents

?>