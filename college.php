<?php
/**
 * @file 
 *  IMPLEMENTATION OF QUESTION 2
 */

// Name of colleges with key as college id.
$college = array(
	1 => 'poornima',
	2 => 'skit',
	3 => 'vit',
);

//class for variables that are made as per new college name is added
class details {
	public $name;
	public $type;
	public $college;
	public $sent;
	public $status	;
}

$doc = array();
// Create object as each college.
$doc[0] = new details();
$doc[1] = new details();
$doc[2] = new details();
//College 1 details.
$doc[0]->name = 'abc.txt';
$doc[0]->type = 'A';
$doc[0]->college = 1;
$doc[0]->sent = 1;
// College 2 details.
$doc[1]->name = 'xyz.txt';
$doc[1]->type = 'B';
$doc[1]->college = 2;
$doc[1]->sent = 0;
// College 3 details.
$doc[2]->name = 'efg.txt';
$doc[2]->type = 'C';
$doc[2]->college = 3;
$doc[2]->sent = 1;

/**
 * The fucntion displays the output of the above data according to their set values with sent status.
 * @param  array clg_details
 * This array holds the college names 
 * @param  array clg_value
 * This array holds the college data that is already hard coded above.
 * @return mixed
 * The function prints out the output directly on the page
 */
function work($clg_details, $clg_value) {
// if sent ==1 then sent , or failed
	for ($i = 0; $i < count($clg_value); $i++) { 
		if ($clg_value[$i]->sent == 1) {
			$clg_value[$i]->status = "SENT";
		}
		else {
			$clg_value[$i]->status = "FAILED";
		}
	}

	// college by college detail display
	for ($i = 0; $i < count($clg_details); $i++) { 
		echo "<br>";
		echo "\$coll[college_id]->college_name = '" . $clg_details[$i + 1] . "';";
		echo "<br>";
		echo "\$coll[college_id]->college_id = '" . ($i + 1) . "';";
		foreach ($clg_value as $key => $value) {
			if ($clg_value[$key]->college == ($i + 1)) {
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->name = '" . $clg_value[$key]->name . "';";
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->type = '" . $clg_value[$key]->type . "';";
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->status = '" . $clg_value[$key]->status . "';";
			}
			// If college is null then it will displayed for every college.
			elseif ($clg_value[$key]->college == null) {
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->name = '" . $clg_value[$key]->name . "';";
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->type = '" . $clg_value[$key]->type . "';";
				echo "<br>";
				echo "\$coll[college_id]->docs[" . $key . "]->status ='" . $clg_value[$key]->status . "';";
			}
		}

	}
}

// Calling the function with college names (college) and data array (doc).
work($college, $doc);
?>