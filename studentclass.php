<?php

/**
 * @file 
 *  IMPLEMENTATION OF QUESTION 1
 */

class details {
  public $sid;
  public $name;
  public $dob;
  public $class;
  public $marks;
  public $result;
}

$student[1] = new details();
$student[2] = new details();
$student[3] = new details();
$student[4] = new details();

$student[1]->sid = '101';
$student[1]->name = 'Kunal';
$student[1]->dob = strtotime('02-01-1999');
$student[1]->class = '12';
$student[1]->marks = array(
  'M12' => '56',
  'P12' => '12',
  'C12' => '50',);
$student[1]->result = '0';

$student[2]->sid = '102';
$student[2]->name = 'Zubin';
$student[2]->dob = strtotime('05-03-1998');
$student[2]->class = '12';
$student[2]->marks = array(
  'M12' => '30',
  'P12' => '14',
  'C12' => '03',);
$student[2]->result = '0';

$student[3]->sid = '103';
$student[3]->name = 'Yash';
$student[3]->dob = strtotime('27-11-1998');
$student[3]->class = '11';
$student[3]->marks = array(
  'M11' => '30',
  'P11' => '29',
  'C11' => '35',);
$student[3]->result = '0';

$student[4]->sid = '104';
$student[4]->name = 'Yogita ';
$student[4]->dob = strtotime('15-06-1998');
$student[4]->class = '10  ';
$student[4]->marks = array(
  'E10' => '90',
  'S10' => '10',
  'H10' => '39',
  'M10' => '30',);
$student[4]->result = '0';

// Array of subjects.
$subject =  array(
  12 => array(
    array(
      'name' => 'MATH-12',
      'code' => M12,
      'mm' => 20,),
    array(
      'name' => 'PHYSICS-12',
      'code' => P12,
      'mm' => 20,),
    array('name' => 'CHEMISTRY-12',
      'code' => C12,
      'mm' => 20,),),
  11 => array(
    array(
      'name' => 'MATH-11',
      'code' => M11,
      'mm' => 30,),
    array(
      'name' => 'PHYSICS-11',
      'code' => P11,
      'mm' => 30,),
    array(
      'name' => 'CHEMISTRY-11',
      'code' => C11,
      'mm' => 30,),),
  10 => array(
    array(
      'name' => 'ENGLISH-10',
      'code' => E10,
      'mm' => 40,),
    array(
      'name' => 'SCIENCE-10',
      'code' => S10,
      'mm' => 40,),
    array(
      'name' => 'HINDI-10',
      'code' => H10,
      'mm' => 40,),
    array(
      'name' => 'MATH-10',
      'code' => M10,
      'mm' => 40,),),);

/**
 * This funtion prints table rows with subject name, subject code and the min. marks for each subject.
 * @param  int class
 *  Takes class from the funtion calling.
 * @param  array subject
 *  Have details of subjects with min marks to pass.
 * @return mixed
 *  Prints table rows for each class inputed through.
 */

function subject_display($class, $subject) { 
	$class_subject = $subject[$class];
	foreach ($class_subject as $class => $subject_array) {
			echo "<tr><td>" . $subject_array['name'] . "</td><td>" . $subject_array['code'] . "</td><td>" . $subject_array['mm'] . "</td></tr>";
		}
}

// Call function subject_display as per class and print table.
echo '<br><table border = 1><tr><th>SUBJECT NAME</th><th>SUBJECT CODE</th><th>MIN. MARKS</th></tr>';
subject_display(12,$subject);
subject_display(11,$subject);
subject_display(10,$subject);
echo '</table><br>';

/**
 * This funtion prints table rows with student id, subject code and the obtained marks for each subject.
 * @param  array details
 *  Have all details of student with obtained marks.
 * @return mixed
 *  Prints table with subject name and obtained marksby student.
 */

function mark_as_subject($student) {
	echo '<table border = "1px"><tr><th>Student id</th><th>Subject Code</th><th>Marks Obtained</th></tr>';
  foreach ($student as $object_value) {
    $mark = $object_value->marks;
    foreach ($mark as $id => $value) {
       echo "<tr><td>" . $object_value->sid . "</td><td>" . $id . " </td><td> " . $value . "</td></tr>";
    }
  }
  echo '</table>';
}

// Call function mark_as_subject by passing details array.

mark_as_subject($student);
echo "<br>";

/**
 * This funtion prints table rows with final output with subject wise marks and pass/fail result.
 * @param  array student
 *  Array of all data stored by objects of class 'details'.
 * @param  array subject
 *  Have details of subjects with min marks to pass.
 * @return mixed
 *  Prints table with subject wise marks and final result.
 */

function final_result($student, $subject){
  echo '<table border = "1px"><tr><th>ID</th><th>NAME</th><th>DOB</th><th>GRADE</th><th>SUBJECTS</th><th>RESULT</th></tr>';
  foreach ($student as $object_no => $object_value) {
    $count = 0;
    foreach ($object_value as $key => $value) {
      if ($key == 'sid') {
        echo "<tr><td>" . $value . "</td>";
      }
      elseif ($key == 'name') {
        echo "<td>" . $value . "</td>";
      }
      elseif ($key == 'dob') {
        echo "<td>" . date('m/d/Y', $value) . "</td>";
      }
      elseif ($key == 'class') {
        echo "<td>" . $value . "</td>";
      }
      elseif ($key == 'marks') {
        echo "<td>";
        foreach ($value as $k => $v) {
          foreach ($subject as $ke => $va) {
            if ($object_value->class == $ke)
              echo $k . "(" . $v . ", " . $va[0]['mm'] . ")<br>";
            if ($v >= $va[0]['mm']) {
                $count = $count + 1;
          }
        }
      }
      }
      elseif ($key == 'result') {
        if ($count > 2) {
          echo "<td>PASS</td>";
        }
        else {
          echo "<td>FAIL</td>";
        }
      }
    }
  }
}

final_result($student, $subject);
?>