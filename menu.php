<?php 
require 'connection.php';
session_start();

if(empty($_SESSION["id"])) {
  
}

else if(!empty($_SESSION["id"])){
  if(isset($_POST['generate'])){
    header("process_token.php");
  }

}


$menuItems = []; // Initialize an empty array for menu items fetched from the database

// Fetch meals data from the 'meals' table in the database
$mealsQuery = mysqli_query($conn, "SELECT name, price, available FROM `meals`");
while ($mealRow = mysqli_fetch_assoc($mealsQuery)) {

    if ($mealRow['available'] == 1) {

        $mealName = $mealRow['name'];
        $item = [
            'label' => $mealName,
            'price' => $mealRow['price'],

        ];
        
        // Determine the category based on the first word of the meal name
        $firstWord = strtok($mealName, " "); // Get the first word of the meal name
        $category = strtoupper($firstWord);   // Convert it to uppercase for consistency
        
        // Add the item to the corresponding category
      if (!isset($menuItems[$category])) {
            $menuItems[$category] = [];
      }

      $menuItems[$category][] = $item;

    }
}
?>
<?php
require_once 'helpers.php';
render('header',array('title'=>'Menu','main'=>'main.css','link'=>'menu.css','heading'=>'Order Now','log'=>'logout','page'=>'dashboard','page2'=>''));
?>


<div class="body">
  <div class="headings">
    <h2>Today's MENU</h2>
  </div>
  <div class="hero">
  
      <div class="contain">
      <?php foreach ($menuItems as $category => $items): ?>
          <h2 class="main"><?php echo $category; ?></h2>
          <?php foreach ($items as $menuItem): ?>
            <?php
                  $mealLabel = $menuItem['label'];
                  $itemPrice = $menuItem['price'];
                  $isAvailableQuery = mysqli_query($conn, "SELECT available FROM `meals` WHERE name = '$mealLabel'");
                  $isAvailableRow = mysqli_fetch_assoc($isAvailableQuery);
                  $isAvailable = $isAvailableRow['available'];
                  if ($isAvailable == 1):
            ?>
  
              <label for="" class="menu_list"><?php echo $mealLabel; ?></label>
              <button class="price" id="price" onclick="addToSum(<?php echo $itemPrice; ?>, '<?php echo $mealLabel; ?>')" name="<?php echo $mealLabel; ?>"><?php echo $itemPrice; ?></button>
              <br>
  
          <?php endif; ?>
          <?php endforeach; ?>
      <?php endforeach; ?>
      </div>
      <div class="contain-two" id="contain-two">
        <form id="tokenForm" action="process_token.php" method="post">
        <input type="submit" value="Generate Token" name="generate" id="generate" class="token" onclick="details()">
        <input type="button" value="Deduct meal" class="token" id="deduct" onclick="undoLastSelection()">
        <p class="menu_buttons" >Selected Meals: <span id="selectedItems"></span></p>
        <p class="menu_buttons" >TOTAL: <span id="totalPrice">0</span></p>
        <span id="meals" hidden></span><br>
        <span id="amount" hidden></span> <br>
        <span id="token" hidden></span>
  
          <input type="hidden" name="allMeals" id="totalMealsInput" value="">
        <input type="hidden" name="totalPrice" id="totalPriceInput" value="">
        <input type="hidden" name="tokenNumber" id="tokenNumberInput" value="">
        <!-- <input type="submit" value="Submit Token" name="sendToken"> -->
        </form>
      </div>
  
      </div>
  </div>
<script>

function getItemPrice(itemName) {

  <?php foreach ($menuItems as $category => $items): ?>
    <?php foreach ($items as $menuItem): ?>

      if (itemName === '<?php echo $menuItem['label']; ?>') {
        return <?php echo $menuItem['price']; ?>;
      }

    <?php endforeach; ?>
  <?php endforeach; ?>

  return 0; // Default price if not found

}
let currentSum = 0;
let selectedItems = [];

function addToSum(number, itemName) {

  currentSum += number;
  selectedItems.push(itemName);
  document.getElementById('totalPrice').innerText = currentSum;
  document.getElementById('selectedItems').innerText = selectedItems.join(', ');

}

function undoLastSelection() {

  if (selectedItems.length > 0) {
    const lastSelected = selectedItems.pop();
    const itemPrice = getItemPrice(lastSelected);
    currentSum -= itemPrice;
    document.getElementById('totalPrice').innerText = currentSum;
    document.getElementById('selectedItems').innerText = selectedItems.join(', ');
  }

}
function details() {
    const amount = currentSum;
    const meals = selectedItems.join(', ');
    const token = Math.floor(Math.random() * 10000);

    document.getElementById('amount').innerText = amount;
    document.getElementById('meals').innerText = meals;
    document.getElementById('token').innerText = token;

    document.getElementById('totalPriceInput').value = amount;
    document.getElementById('totalMealsInput').value = meals;
    document.getElementById('tokenNumberInput').value = token;


}



</script>


<?php 
render('footer');
?>