<?php
require_once 'helpers.php';
render('header',array('title'=>'index','link'=>'indexx.css','main'=>'main.css','heading'=>'WELCOME','log'=>'logout','page'=>'','page2'=>''));
?>

<div class="heading">
    <!-- <h1 class="welcome">WELCOME !!</h1> -->
</div>
<div class="outer">
    <div class="inner space">
    
        <a class="btn" href="login.php">
            <div class="w3-container w3-card-4 c1">
                <div class="c2 card">
                    <span>USER</span>
                </div>
            </div>
        </a>
        <a class="btn" href="adminlogin.php" >
        <div class="w3-container w3-card-4 c3">
    
            <div class="c4 card">
                <span>ADMIN</span>
            </div>
    
        </div>
        </a>
    </div>
</div>
<?php
render('footer');
?>