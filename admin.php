
<?php
require_once 'helpers.php';
render('header',array('title'=>'Admin','main'=>'main.css','link'=>'admin.css','heading'=>'ADMIN','log'=>'logout','page'=>'indexx','page2'=>''));
?>

<div class="outer">
    <div class="inner">
    <a class="btn" href="transaction.php">
    <div class="w3-container w3-card-4 c1">
    <div class="c2 card">
    <span style="font-size: 2rem;">TRANS. LOG</span>
    </div>
    </div>
    </a>
    <a class="btn" href="menu_updates.php">
    <div class="w3-container w3-card-4 c3">
    <div class="c4 card">
    <span>COOK</span>
    </div>
    </div>
    </a>
    </div>
</div>




















<?php 
render('footer');
?>