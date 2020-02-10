<?php

/**
 *@file
 * Implementation of question 4 using oops.
 */

// This class gets value of people with their names and gender
class details {
  public $name;
  public $gender;
}
$user_details = array();

// Creating objects of class.
$user_details[0] = new details();
$user_details[1] = new details();
$user_details[2] = new details();
$user_details[3] = new details();
$user_details[4] = new details();
$user_details[5] = new details();
$user_details[6] = new details();
$user_details[7] = new details();
$user_details[8] = new details();
$user_details[9] = new details();

// Assigning values to the objects of class starts here.
$user_details[0]->name = 'kunal';
$user_details[0]->gender = 'M';
$user_details[1]->name = 'yogita';
$user_details[1]->gender = 'F';
$user_details[2]->name = 'neha';
$user_details[2]->gender = 'F';
$user_details[3]->name = 'ankit';
$user_details[3]->gender = 'M';
$user_details[4]->name = 'yash';
$user_details[4]->gender = 'M';
$user_details[5]->name = 'arpit';
$user_details[5]->gender = 'M';
$user_details[6]->name = 'prateek';
$user_details[6]->gender = 'M';
$user_details[7]->name = 'ankita';
$user_details[7]->gender = 'F';
$user_details[8]->name = 'hemant';
$user_details[8]->gender = 'M';
$user_details[9]->name = 'ram';
$user_details[9]->gender = 'M';

// Creating arrays to push to 
$seating = $male = $female = array();
foreach ($user_details as $key => $value) {
  if ($value->gender == 'F') {
// Group arrays of female into different array.
      array_push($female, $value->name);
  }
  elseif ($value->gender == 'M') {
// Group arrays of male into different array.
      array_push($male, $value->name);
  }
}

// The loop inserts values until there are no females left forst then will enter all the males in the array
for($i = 0, $j = 0, $k = 0; $i < 7, $i < 3, $k < 10; $i++, $j++, $k++) {
  if ($j < 3){
// Push females to final array.
    array_push($seating, $female[$j]);
  }
  if ($i < 7) {
// Push males to final array.
    array_push($seating, $male[$i]);
  }
}
// Output final display.
echo '<table border = "1px">';
foreach ($seating as $value) {
  echo "<tr><td>" . $value . " </td></tr>";
}
echo '</table>';

?>