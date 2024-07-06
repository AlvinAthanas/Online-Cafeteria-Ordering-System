<?php
require 'connection.php';
$menuItems = [
    'WALI' => [
        ['label' => 'Wali maharage', 'name' => 'W_maharage', 'price' => 1100],
        ['label' => 'Wali Kuku', 'name' => 'W_kuku', 'price' => 2000],
        ['label' => 'Wali Nyama', 'name' => 'W_Nyama', 'price' => 1700],
        ['label' => 'Wali makange', 'name' => 'W_makange', 'price' => 2000],
        ['label' => 'Wali njegere', 'name' => 'W_njegere', 'price' => 1500],
    ],
    'UGALI' => [
        ['label' => 'Ugali maharage', 'name' => 'U_maharage', 'price' => 1100],
        ['label' => 'Ugali Kuku', 'name' => 'U_kuku', 'price' => 2000],
        ['label' => 'Ugali Nyama', 'name' => 'U_Nyama', 'price' => 1700],
        ['label' => 'Ugali makange', 'name' => 'U_makange', 'price' => 2000],
        ['label' => 'Ugali njegere', 'name' => 'U_njegere', 'price' => 1500],
    ],
    // Add more categories and menu items as needed
];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($menuItems as $category => $items) {
        foreach ($items as $item) {
            $label = $item['label'];
            $price = $item['price'];
            $insertQuery = "INSERT INTO meals (name, price) VALUES (:label, :price)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':label', $label);
            $stmt->bindParam(':price', $price);
            $stmt->execute();
        }
    }

  echo "Menu items inserted successfully.";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>