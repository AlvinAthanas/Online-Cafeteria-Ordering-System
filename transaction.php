<?php
require 'connection.php';
session_start();

// Get the desired date range (today, this week, this month, this year)
$dateCondition = "";


// SQL query with the date condition
$query1 = "SELECT
    `order`.order_id,
    `order`.`meals`,
    payment.total,
    payment.payment_id,
    payment.status,
    payment.receipt,
    payment.date
FROM
    `order`
JOIN
    payment ON `order`.order_id = payment.order_id
WHERE
DATE(payment.date) = CURDATE()";

$query2 = "SELECT
`order`.order_id,
`order`.`meals`,
payment.total,
payment.payment_id,
payment.status,
payment.receipt,
payment.date
FROM
`order`
JOIN
payment ON `order`.order_id = payment.order_id
WHERE
YEARWEEK(payment.date) = YEARWEEK(CURDATE())";

$query3 = "SELECT
`order`.order_id,
`order`.`meals`,
payment.total,
payment.payment_id,
payment.status,
payment.receipt,
payment.date
FROM
`order`
JOIN
payment ON `order`.order_id = payment.order_id
WHERE
YEAR(payment.date) = YEAR(CURDATE()) AND MONTH(payment.date) = MONTH(CURDATE())";

$query4 = "SELECT
`order`.order_id,
`order`.`meals`,
payment.total,
payment.payment_id,
payment.status,
payment.receipt,
payment.date
FROM
`order`
JOIN
payment ON `order`.order_id = payment.order_id
WHERE
YEAR(payment.date) = YEAR(CURDATE())";

$result1 = mysqli_query($conn, $query1);
$result2 = mysqli_query($conn, $query2);
$result3 = mysqli_query($conn, $query3);
$result4 = mysqli_query($conn, $query4);

$result = $conn->query($query4);

// TODAY

if(isset($_POST['today'])){
  if ($result1) {
    $rowCount = $result1->num_rows;
  } else {
    echo "Error: " . $conn->error;
  }

$queryA = "SELECT SUM(payment.total) AS total_sum
          FROM `order`
          JOIN payment ON `order`.order_id = payment.order_id
          WHERE DATE(payment.date) = CURDATE()";

$resultA = $conn->query($queryA);

if ($resultA) {
  $rowA = $resultA->fetch_assoc();
  if($rowCount == 0){
    $totalSum = 0;
  } else{
    $totalSum = $rowA['total_sum'];
  }
} else {
  echo "Error: " . $conn->error;
}

$result = $conn->query($query1);

if ($result) {
  $mealsArray = [];
  
  while ($row = $result->fetch_assoc()) {
    // Split the comma-separated values into an array
    $meals = explode(",", $row['meals']);
    
    // Trim and clean each meal name
    $meals = array_map('trim', $meals);
    
    // Add the values to the mealsArray
    $mealsArray = array_merge($mealsArray, $meals);
  }
  
  // Count the occurrences of each cleaned meal
  $mealsCount = array_count_values($mealsArray);
  
  // Sort the meals by count in descending order
  arsort($mealsCount);
  
  // Get the most common meals
  $mostCommonMeals = [];
  $mostCommonCount = reset($mealsCount);
  
  foreach ($mealsCount as $meal => $count) {
    if ($count === $mostCommonCount) {
      $mostCommonMeals[] = $meal;
    } else {
      break; // Exit loop when count changes
    }
  }
  
  // Get the least common meals
  $leastCommonMeals = [];
  $leastCommonCount = end($mealsCount);
  
  foreach ($mealsCount as $meal => $count) {
    if ($count === $leastCommonCount) {
      $leastCommonMeals[] = $meal;
    }
  }
  
  $leastCommonMeals = array_filter($leastCommonMeals);
  if($rowCount == 0){
    $mostCommonMeal = "No orders made today";
    $leastCommonMeal = "No orders made today";
  } else{
    $mostCommonMeal = implode(", ", $mostCommonMeals);
    $leastCommonMeal = implode(", ", $leastCommonMeals);
  }
} else {
  echo "Error: " . $conn->error;
}

}

// THIS WEEK

else if(isset($_POST['week'])){
  if ($result2) {
    $rowCount = $result2->num_rows;
  } else {
    echo "Error: " . $conn->error;
  }
  
  $queryB = "SELECT SUM(payment.total) AS total_sum
          FROM `order`
          JOIN payment ON `order`.order_id = payment.order_id
          WHERE YEARWEEK(payment.date) = YEARWEEK(CURDATE())";
          
          $resultB = $conn->query($queryB);
          
          if ($resultB) {
            $rowB = $resultB->fetch_assoc();
            if($rowCount == 0){
              $totalSum = 0;
            } else{
              $totalSum = $rowB['total_sum'];
            }
          } else {
            echo "Error: " . $conn->error;
          }

          $result = $conn->query($query2);
          
          if ($result) {
            $mealsArray = [];
            
            while ($row = $result->fetch_assoc()) {
              
              // Split the comma-separated values into an array
              $meals = explode(",", $row['meals']);
              
              // Trim and clean each meal name
              $meals = array_map('trim', $meals);
              
              // Add the values to the mealsArray
              $mealsArray = array_merge($mealsArray, $meals);
            }
            
            // Count the occurrences of each cleaned meal
            $mealsCount = array_count_values($mealsArray);
            
            // Sort the meals by count in descending order
            arsort($mealsCount);
            
            // Get the most common meals
            $mostCommonMeals = [];
            $mostCommonCount = reset($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {
              
              if ($count === $mostCommonCount) {
                $mostCommonMeals[] = $meal;
              
              } else {
                break; // Exit loop when count changes
              }
            }
            
            // Get the least common meals
            $leastCommonMeals = [];
            $leastCommonCount = end($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {
              
              if ($count === $leastCommonCount) {
                $leastCommonMeals[] = $meal;
              }
            }
            
            $leastCommonMeals = array_filter($leastCommonMeals);
            if($rowCount == 0){

              $mostCommonMeal = "No orders made this week";
              $leastCommonMeal = "No orders made this week";
            
            } else{
              
              $mostCommonMeal = implode(", ", $mostCommonMeals);
              $leastCommonMeal = implode(", ", $leastCommonMeals);
            
            }
          
          } else {
            echo "Error: " . $conn->error;
          }
        }
        
        // THIS MONTH
        
        else if(isset($_POST['month'])){

          if ($result3) {

            $rowCount = $result3->num_rows;
          } else {
            echo "Error: " . $conn->error;
          }
          
          $queryC = "SELECT SUM(payment.total) AS total_sum
          FROM `order`
          JOIN payment ON `order`.order_id = payment.order_id
          WHERE YEAR(payment.date) = YEAR(CURDATE()) AND MONTH(payment.date) = MONTH(CURDATE())";
          
          $resultC = $conn->query($queryC);
          
          if ($resultC) {
            $rowC = $resultC->fetch_assoc();
            
            if($rowCount == 0){
              $totalSum = 0;
            } else{
              $totalSum = $rowC['total_sum'];
            }
          } else {
            echo "Error: " . $conn->error;
          }
          
          $result = $conn->query($query3);
          
          if ($result) {
            $mealsArray = [];
            
            while ($row = $result->fetch_assoc()) {
              // Split the comma-separated values into an array
              $meals = explode(",", $row['meals']);
              
              // Trim and clean each meal name
              $meals = array_map('trim', $meals);
              
              // Add the values to the mealsArray
              $mealsArray = array_merge($mealsArray, $meals);
            }
            
            // Count the occurrences of each cleaned meal
            $mealsCount = array_count_values($mealsArray);
            
            // Sort the meals by count in descending order
            arsort($mealsCount);
            
            // Get the most common meals
            $mostCommonMeals = [];
            $mostCommonCount = reset($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {
              if ($count === $mostCommonCount) {
                $mostCommonMeals[] = $meal;
              } else {
                break; // Exit loop when count changes
              }
            }
            
            // Get the least common meals
            $leastCommonMeals = [];
            $leastCommonCount = end($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {

              if ($count === $leastCommonCount) {
                $leastCommonMeals[] = $meal;
              }
            }
            
            $leastCommonMeals = array_filter($leastCommonMeals);
            
            if($rowCount == 0){
              $mostCommonMeal = "No orders made this month";
              $leastCommonMeal = "No orders made this month";
            
            } else{
              $mostCommonMeal = implode(", ", $mostCommonMeals);
              $leastCommonMeal = implode(", ", $leastCommonMeals);
            }
          } else {
            echo "Error: " . $conn->error;
          }
        }
        
        // THIS YEAR
        
        else if(isset($_POST['year'])){
          
          if ($result4) {
            $rowCount = $result4->num_rows;
          
          } else {
            echo "Error: " . $conn->error;
          }
          
          $queryD = "SELECT SUM(payment.total) AS total_sum
          FROM `order`
          JOIN payment ON `order`.order_id = payment.order_id
          WHERE YEAR(payment.date) = YEAR(CURDATE())";
          
          $resultD = $conn->query($queryD);
          
          if ($resultD) {
            $rowD = $resultD->fetch_assoc();
            
            if($rowCount == 0){
              $totalSum = 0;
            
            } else{
              $totalSum = $rowD['total_sum'];
            
            }
          } else {
            echo "Error: " . $conn->error;
          }
          
          $result = $conn->query($query4);
          
          if ($result) {
            $mealsArray = [];
            
            while ($row = $result->fetch_assoc()) {
              // Split the comma-separated values into an array
              $meals = explode(",", $row['meals']);
              
              // Trim and clean each meal name
              $meals = array_map('trim', $meals);
              
              // Add the values to the mealsArray
              $mealsArray = array_merge($mealsArray, $meals);
            
            }
            
            // Count the occurrences of each cleaned meal
            $mealsCount = array_count_values($mealsArray);
            
            // Sort the meals by count in descending order
            arsort($mealsCount);
            
            // Get the most common meals
            $mostCommonMeals = [];
            $mostCommonCount = reset($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {
              
              if ($count === $mostCommonCount) {
                $mostCommonMeals[] = $meal;
              
              } else {
                break; // Exit loop when count changes
              }
            }
            
            // Get the least common meals
            $leastCommonMeals = [];
            $leastCommonCount = end($mealsCount);
            
            foreach ($mealsCount as $meal => $count) {
              
              if ($count === $leastCommonCount) {
                $leastCommonMeals[] = $meal;
              
              }
            }
            
            $leastCommonMeals = array_filter($leastCommonMeals);
            
            if($rowCount == 0){
              $mostCommonMeal = "No orders made this year";
              $leastCommonMeal = "No orders made this year";
            
            } else{
              $mostCommonMeal = implode(", ", $mostCommonMeals);
              $leastCommonMeal = implode(", ", $leastCommonMeals);
            
            }
          } else {
            echo "Error: " . $conn->error;
          }
        } else {
          $rowCount = 0;
          $totalSum = 0;
          $mostCommonMeal = "N/A";
          $leastCommonMeal = "N/A";
        }


require_once 'helpers.php';
render('header',array('title'=>'TRANSACTION LOG','main'=>'main.css','link'=>'Cashier.css','heading'=>'TRANSACTION LOG','log'=>'logout','page'=>'admin','page2'=>''));
?>

<form action="transaction.php" method="post">

  <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">TODAY</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <button type="submit" name="today">Today</button>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6"> 
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">THIS WEEK</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <button type="submit" name="week">Week</button>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">THIS MONTH</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <button type="submit" name="month">Month</button>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Year</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <button type="submit" name="year">This year</button>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
</div>
</form>

<div class="row px-4">
                           <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">TOTAL ORDERS</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <P><?= $rowCount ?></P>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">TOTAL PAYMENTS</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <P><?=$totalSum ?></P>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6"> 
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">MOST ORDERED MEAL</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                      <P><?=$mostCommonMeal ?></P>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">LEAST ORDERED MEAL </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                      <P><?=$leastCommonMeal ?></P>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                        <table class="main-table  ">

<tr>
  <th>Order ID</th>
  <th>Meals</th>
  <th>Price</th>
  <th>Payment ID</th>
  <th>Status</th>
  <th>Receipt</th>
  <th>Date</th>
</tr>
    
  <?php if(isset($_POST['today'])){
    if ($result1->num_rows > 0) {
      while ($row1 = $result1->fetch_assoc()) {
        echo "<tr><td>" . $row1["order_id"] . "</td><td>" . $row1["meals"] . "</td><td>" . $row1["total"] . "</td><td>" . $row1["payment_id"] . "</td><td>" . $row1["status"] . "</td><td>" . $row1["receipt"]. "</td><td>" . $row1["date"] . "</td></tr>";
      }
    } else {
      echo "<tr><td colspan='7'>No transactions done today</td></tr>";
    }
    }
    if(isset($_POST['week'])){
      if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
          echo "<tr><td>" . $row2["order_id"] . "</td><td>" . $row2["meals"] . "</td><td>" . $row2["total"] . "</td><td>" . $row2["payment_id"] . "</td><td>" . $row2["status"] . "</td><td>" . $row2["receipt"]. "</td><td>" . $row2["date"] . "</td></tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No transactions done this week</td></tr>";
      }
    } 
    if(isset($_POST['month'])){
      if ($result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
          echo "<tr><td>" . $row3["order_id"] . "</td><td>" . $row3["meals"] . "</td><td>" . $row3["total"] . "</td><td>" . $row3["payment_id"] . "</td><td>" . $row3["status"] . "</td><td>" . $row3["receipt"]. "</td><td>" . $row3["date"] . "</td></tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No transactions done this month</td></tr>";
      }
    } 
    if(isset($_POST['year'])){
      if ($result4->num_rows > 0) {
        while ($row4 = $result4->fetch_assoc()) {
          echo "<tr><td>" . $row4["order_id"] . "</td><td>" . $row4["meals"] . "</td><td>" . $row4["total"] . "</td><td>" . $row4["payment_id"] . "</td><td>" . $row4["status"] . "</td><td>" . $row4["receipt"]. "</td><td>" . $row4["date"] . "</td></tr>";
        }
      } else {
        echo "<tr><td colspan='7'>No transactions done this year</td></tr>";
      }
    } 
    ?>
    </table>
<?php 
render('footer');
?>