<?php
/**
 * @file 
 * This file is sorting the given array as cutomer id, then by 
 */

// This array stores all the given details.
$products = array(
  array('pd' => 'pd1', 'sp' => '5', 'sd' => '4thFeb', 'ct' => 'C1',),
  array('pd' => 'pd1', 'sp' => '15', 'sd' => '5thFeb', 'ct' => 'C1'),
  array('pd' => 'pd2', 'sp' => '50', 'sd' => '4thFeb', 'ct' => 'C1'),
  array('pd' => 'pd3', 'sp' => '40', 'sd' => '6thFeb', 'ct' => 'C2'),
  array('pd' => 'pd2', 'sp' => '75', 'sd' => '3rdFeb', 'ct' => 'C1'),
  array('pd' => 'pd2', 'sp' => '65', 'sd' => '7thFeb', 'ct' => 'C1'),
  array('pd' => 'pd4', 'sp' => '160', 'sd' => '8thFeb', 'ct' => 'C2'),
  array('pd' => 'pd2', 'sp' => '90', 'sd' => '6thFeb', 'ct' => 'C2'),);

echo "<br>";
echo "<br>";

// Store date in timestamp format to sort by them. 
foreach ($products as $key => $value) {
  $value['sd']=strtotime($value['sd']);
}

// Get values of main array columns to variables.
$column_pd = array_column($products, 'pd');
$column_sd = array_column($products, 'sd');
$column_sp = array_column($products, 'sp');
$column_ct = array_column($products, 'ct');

// Sort on the base of priority.
array_multisort($column_ct, SORT_ASC, $column_pd, SORT_ASC, $column_sd, SORT_ASC, $column_sp, SORT_ASC, $products);

// Print the values in table format.
echo '<table border = "1px" align = "center">';
echo '<tr><th>CATEGORY</th><th>PRICE</th><th>DATE</th><th>PRODUCT ID</th><th>ID</th>';

// Loop for repetitive traversal and print in table from main array
foreach ($products as $key => $value) {
  echo "<tr><td>" . $value['ct'] . "</td>";
  if($value['ct'] == 'C1')
  {
    foreach ($value as $key1 => $value1) {
    if ($key1 == 'pd' && $value1 == 'pd1') {
      $sum_p1 = $sum_p1 + $value['sp'];
      $value['sp'] = $sum_p1;
      echo "<td>" .  $value['sp'] . "</td>";
    }
    elseif ($key1 == 'pd' && $value1 == 'pd2') {
      $sum_p2 = $sum_p2 + $value['sp'];
      $value['sp'] = $sum_p2;
      echo "<td>" .  $value['sp'] . "</td>";
    }
    elseif ($key1 == 'pd' && $value1 == 'pd3') {
      $sum_p3 = $sum_p3 + $value['sp'];
      $value['sp'] = $sum_p3;
      echo "<td>" .  $value['sp'] . "</td>";
    }
    elseif ($key1 == 'pd' && $value1 == 'pd4') {
      $sum_p4 = $sum_p4 + $value['sp'];
      $value['sp'] = $sum_p4;
      echo "<td>" .  $value['sp'] . "</td>";
    }
  }
  }
  elseif ($value['ct'] == 'C2')
  {
    $sum_p1 = 0;
    $sum_p2 = 0;
    $sum_p3 = 0;
    $sum_p4= 0;
    foreach ($value as $key1 => $value1) {
      if ($key1 == 'pd' && $value1 == 'pd1') {
        $sum_p1 = $sum_p1 + $value['sp'];
        $value['sp'] = $sum_p1;
        echo "<td>" .  $value['sp'] . "</td>";
      }
      elseif ($key1 == 'pd' && $value1 == 'pd2') {
        $sum_p2 = $sum_p2 + $value['sp'];
        $value['sp'] = $sum_p2;
        echo "<td>" .  $value['sp'] . "</td>";
      }
      elseif ($key1 == 'pd' && $value1 == 'pd3') {
        $sum_p3 = $sum_p3 + $value['sp'];
        $value['sp'] = $sum_p3;
        echo "<td>" .  $value['sp'] . "</td>";
      }
      elseif ($key1 == 'pd' && $value1 == 'pd4') {
        $sum_p4 = $sum_p4 + $value['sp'];
        $value['sp'] = $sum_p4;
        echo "<td>" .  $value['sp'] . "</td>";
      }
    }
  }
  echo "<td>" . $value['sd'] . "</td>";
  echo "<td>" . $value['pd'] . "</td>";
  echo "<td>" . $value['ct'] . "-" . $value['pd'] . "</td></tr>";
}

// To print the output in the desired array view format.
foreach ($products as $key => $value) {
  echo "[" . $key . "] => Array" . "<br> ( <br>";
  foreach ($value as $key1 => $value1) {
    echo " " . $key1 . " --> " . $value1 . "<br>";
  }
  echo ")<br>";
}
?>