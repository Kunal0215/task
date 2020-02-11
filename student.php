<?php

/**
 * @file 
 *  IMPLEMENTATION OF QUESTION 1
 */

// Array of student details.
$details =  array(
  array(
    'sid' => '101',
    'name' => 'Kunal',
    'dob' => strtotime('02-01-1999'),
    'class' =>'12',
    'passed' => '0',
    'marks' => array(
      'M12' => '56',
      'P12' => '12',
      'C12' => '50',),),
  array(
    'sid' => '102',
    'name' => 'Zubin',
    'dob' => strtotime('05-03-1998'),
    'class' =>'12',
    'passed' => '0',
    'marks' => array(
      'M12' => '30',
      'P12' => '14',
      'C12' => '03',),),
  array(
    'sid' => '103',
    'name' => 'Yash',
    'dob' => strtotime('27-11-1998'),
    'class' =>'11',
    'passed' => '0',
    'marks' => array(
      'M11' => '30',
      'P11' => '29',
      'C11' => '35',),),
  array(
    'sid' => '104',
    'name' => 'Yogita',
    'dob' => strtotime('15-06-1998'),
    'class' =>'10',
    'passed' => '0',
    'marks' => array(
      'E10' => '90',
      'S10' => '10',
      'H10' => '39',
      'M10' => '30',),),
  );

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
      'mm' => 20,)),
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

// Subject wise student pass fail check.
foreach ($details as $key => $value) {
  $class = $value['class'];
  $marks = $value['marks'];
  $count = 0;
  foreach ($marks as $detail_markarray => $val) {
    // Here 4 is the number of students.
    for($x = 0; $x < 4; $x++) {
      if($subject[$class][$x]['code'] == $detail_markarray) { 
          $details[$key]['mm'][$detail_markarray] = $subject[$class][$x]['mm'];
           if($subject[$class][$x]['mm'] <= $marks[$detail_markarray]) {
             $count = $count + 1;
           }
        }
    }
  }

// If 2 subjects passed then pass otherwise fail.
  if($count >= 2) {
    $details[$key]['res'] = "PASS";
  }
  else {
    $details[$key]['res'] = "FAIL";
  }
}

/**
 * This funtion prints table rows with student id, subject code and the obtained marks for each subject.
 * @param  array details
 *  Have all details of student with obtained marks.
 * @return mixed
 *  Prints table with subject name and obtained marksby student.
 */

function mark_as_subject($details) {
	echo '<table border = "1px"><tr><th>Student id</th><th>Subject Code</th><th>Marks Obtained</th></tr>';
	foreach ($details as $key) {
    $mark = $key['marks'];
    foreach ($mark as $id => $value) {
       echo "<tr><td>" . $key['sid'] . "</td><td>" . $id . " </td><td> " . $value . "</td>";
    }		
	}
	echo '</table>';
}

// Call function mark_as_subject by passing details array.

mark_as_subject($details);
echo "<br>";

/**
 * This funtion prints table rows with final output with subject wise marks and pass/fail result.
 * @param  array details
 *  Have all details of student with obtained marks.
 * @param  array subjects
 *  Have details of subjects with min marks to pass.
 * @return mixed
 *  Prints table with subject wise marks and final result.
 */

function student($details, $subject) {   
	echo '<table border = "1px"><th>ID</th><th>NAME</th><th>DOB</th><th>GRADE</th><th>SUBJECTS</th><th>RESULT</th>';
  foreach ($details as $key => $value) {
    $mark = $value['marks'];
    $details[$key]['dob'] = date('m/d/Y', $details[$key]['dob']);
    echo "<tr><td>" . $details[$key]['sid'] . "</td><td>". $details[$key]['name'] . "</td><td>" . $details[$key]['dob'] . "</td><td>" . $details[$key]['class'] . "</td><td>";
    foreach ($mark as $id => $val) {
        echo $id . "(" . $details[$key]['marks'][$id] . "," . $details[$key]['mm'][$id] . ")" . "<br>";
      }
    echo "</td><td>" . $details[$key]['res'] . "</td></tr>";
  }
}

// Call for final function for main output with marks and pass fail result.

student ($details, $subject);
?>