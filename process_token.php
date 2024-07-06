<!-- <?php
        require 'connection.php';

        include 'session.php';

        var_dump($_SESSION["id"]);
        $meals = $_POST['allMeals'];
        $totalPrice = $_POST['totalPrice'];
        $tokenNumber = $_POST['tokenNumber'];
        
        if (!empty($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
            $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $user_id");
            $row = mysqli_fetch_assoc($result);
            $sql = "INSERT INTO `order` VALUES ('','$user_id','$totalPrice','$tokenNumber','$meals')";
            if ($conn->query($sql) === TRUE) {
                echo "data inserted";
            } else {
                echo "ERROR: " . $conn->error;
            }
        }

        if (!empty($_SESSION["id"])) {

            if ($conn->query($sql) !== true) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $query = "SELECT * FROM `order` WHERE `user_id` = '$user_id' ORDER BY order_id DESC LIMIT 1";
            $sql2 = mysqli_query($conn, $query);
            $array = mysqli_fetch_assoc($sql2);
            $order_id = $array['order_id'];

            // Insert selected meals into 'ordered_meals' table
            $selectedMeals = explode(', ', $meals);
            foreach ($selectedMeals as $mealName) {
                $mealName = mysqli_real_escape_string($conn, $mealName); // Sanitize the input
                $mealQuery = "SELECT meal_id FROM `meals` WHERE name = '$mealName'";
                $mealResult = mysqli_query($conn, $mealQuery);

                if ($mealResult && mysqli_num_rows($mealResult) > 0) {
                    $mealRow = mysqli_fetch_assoc($mealResult);
                    $meal_id = $mealRow['meal_id'];

                    // Insert into 'ordered_meals' table
                    $insertQuery = "INSERT INTO `ordered_meals` (order_id, meal_id, user_id) 
                            VALUES ('$order_id', '$meal_id', '$user_id')";
                    if ($conn->query($insertQuery) !== true) {
                        echo "Error: " . $insertQuery . "<br>" . $conn->error;
                    }
                }
            }
        }
        header("Location: payment.php");
        ?>
 -->