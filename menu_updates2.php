<?php
$host = 'localhost';
$dbname = 'cafeteria';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handling form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_menu'])) {
            $mealName = $_POST['name'];
            $mealPrice = $_POST['price'];

            $insertQuery = "INSERT INTO meals (name, price) VALUES (:name, :price)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':name', $mealName);
            $stmt->bindParam(':price', $mealPrice);
            $stmt->execute();
        } elseif (isset($_POST['disable_menu'])) {
            $mealId = $_POST['disable_menu'];

            $updateQuery = "UPDATE meals SET available = 0 WHERE meal_id = :meal_id";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':meal_id', $mealId);
            $stmt->execute();
        } elseif (isset($_POST['enable_menu'])) {
            $mealId = $_POST['enable_menu'];

            $updateQuery = "UPDATE meals SET available = 1 WHERE meal_id = :meal_id";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':meal_id', $mealId);
            $stmt->execute();
        }
    }

    // Fetch menu items from the database
    $query = "SELECT * FROM meals";
    $stmt = $pdo->query($query);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$menuCategories = [];
foreach ($menuItems as $item) {
    $mealName = $item['name'];
    $firstWord = strtok($mealName, " "); // Get the first word of the meal name
    $category = strtoupper($firstWord);   // Convert it to uppercase for consistency

    if (!isset($menuCategories[$category])) {
        $menuCategories[$category] = [];
    }

    $menuCategories[$category][] = $item;
}


require_once 'helpers.php';
render('header',array('title'=>'Menu Management','link'=>'menu_updates.css','main'=>'main.css','heading'=>'Menu Management','log'=>'logout','page'=>'Dashboard'));
?>



        <div class="split">
            <div class="add">
        
                <form action="" method="post">
                    <label for="name">Meal Name:</label>
                    <input type="text" name="name" required><br>
                    <label for="price">Price:</label>
                    <input type="number" name="price" required><br>
                    <input type="submit" name="add_menu" value="Add Menu Item">
                </form>
            </div>
        
            <div class="availables">
        
                <ul>
        <!-- Outside the foreach loop, display the categorized menu items -->
        <?php foreach ($menuCategories as $category => $items): ?>
        <div class="category">
            <h2><?php echo $category; ?></h2>
        
                <?php foreach ($items as $item) { ?>
                    <li>
                        <?php echo $item['name']; ?> - <?php echo $item['price']; ?>
                        <?php if ($item['available']) { ?>
                            <form action="" method="post">
                                <input type="hidden" name="disable_menu" value="<?php echo $item['meal_id']; ?>">
                                <input type="submit" value="Disable" style="background: grey;">
                            </form>
                        <?php } else { ?>
                            <form action="" method="post">
                                <input type="hidden" name="enable_menu" value="<?php echo $item['meal_id']; ?>">
                                <input type="submit" value="Enable">
                            </form>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
render('footer');
?>