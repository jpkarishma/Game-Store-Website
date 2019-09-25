<?php
  session_start();
  if (isset($_SESSION['fname']) == true && $_SESSION['is_admin'] == 1) 
  {
    $user = '<a href ="logout.php" class = "w3-bar-item w3-button w3-padding-large">Log Out</a>';
  }
  else
  {
    header("location: home.php");
  }


  require_once("dbcontroller.php");
  $db_handle = new DBController();

  if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
      case "add":
        if(!empty($_POST["quantity"])) {
          $productByCode = $db_handle->runQuery("SELECT * FROM games WHERE game_ID = '" . $_GET["game_ID"] . "'");
          $itemArray = array($productByCode[0]["game_ID"]=>array('game_name'=>$productByCode[0]["game_name"], 'game_ID'=>$productByCode[0]["game_ID"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
          
          if(!empty($_SESSION["cart_item"])) {
            if(in_array($productByCode[0]["game_ID"],array_keys($_SESSION["cart_item"]))) {
              foreach($_SESSION["cart_item"] as $k => $v) {
                  if($productByCode[0]["game_ID"] == $k) {
                    if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                      $_SESSION["cart_item"][$k]["quantity"] = 0;
                    }
                    $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                  }
              }
            } else {
              $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
            }
          } else {
            $_SESSION["cart_item"] = $itemArray;
          }
        }
      break;
      case "remove":
        if(!empty($_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
              if($_GET["game_ID"] == $k)
                unset($_SESSION["cart_item"][$k]);				
              if(empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
          }
        }
      break;
      case "empty":
        unset($_SESSION["cart_item"]);
      break;	
    }
  }

?>


<!DOCTYPE html>
<html>
<title>gameshop</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.w3-sidebar a {font-family: "Roboto", sans-serif}
body,h1,h2,h3,h4,h5,h6,.w3-wide {font-family: "Montserrat", sans-serif;}
</style>
<body class="w3-content" style="max-width:1200px">


<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-white w3-collapse" style="z-index:3;width:250px" id="mySidebar">
  <div class="w3-container w3-display-container w3-padding-16">
    <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button "></i>
    <h3 class="w3-wide"><font size="3">Admin Home Page</font></h3>
  </div>
  <div class="w3-padding-64 w3-large w3-text-grey" style="font-weight:bold">
    <p class="w3-bar-item">Shop By Category</p>
    <a href="action.php" class="w3-bar-item w3-button">Action</a>
    <a href="fighting.php" class="w3-bar-item w3-button">Fighting</a>
    <a href="role-playing.php" class="w3-bar-item w3-button">Role-playing</a>
    <a href="shooter.php" class="w3-bar-item w3-button">Shooter</a>
    <a href="simulation.php" class="w3-bar-item w3-button">Simulation</a>
    <a href="sports.php" class="w3-bar-item w3-button">Sports</a>
  </div>
</nav>

<!-- Top menu on small screens -->
<header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
<div class="w3-top">
<!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
  <?php echo $user ?>
  </div>
</div>
  <div class="w3-bar-item w3-padding-24 w3-wide">One Space</div>
  <a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding-24 w3-right" onclick="w3_open()"><i class="fa fa-bars"></i></a>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px">

  <!-- Push down content on small screens -->
  <div class="w3-hide-large" style="margin-top:83px"></div>
  
  <!-- Top header -->
  <header class="w3-container w3-xlarge">

  <div class="w3-bar w3-black w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="manager.php" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a>
    <?php echo $user ?>
  </div>
    <h3 class="w3-wide"><p class="w3-center"><b>One Space</b></h3></p>
	
	
	<header class="w3-container w3-large w3-center">
	<p><font size="5"><b>Edit Database</b></font></p>

  <div class="w3-bar w3-black w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="newgame.php" class="w3-bar-item w3-button w3-padding-large w3-hover-white ">Add New Game</a>
	<a href="changeprice.php" class="w3-bar-item w3-button w3-padding-large w3-hover-white ">Change Price</a>
	<a href="changeqty.php" class="w3-bar-item w3-button w3-padding-large w3-hover-white ">Change Stock Qty</a>
    <a href="deletegame.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Delete Game</a>
    <a href="searchAdmin.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Search</a> 
  </div>
    
	 <p> </p>

  </header>

  <!-- Image header -->
  <div class="w3-display-container w3-container">
    <img src="product-images/apex.jpg" alt="Apex" style="width:100%"> </img>
    <div class="w3-display-topleft w3-text-white" style="padding:24px 48px">
      <h1 class="w3-hide-small"><font size="6">Trending Now!</h1>
    </div>
  </div>

  <div class="w3-container w3-text-grey" id="jeans">
    <p><b>Best-sellers</b></p>
  </div>

  <!-- Product grid -->
  <?php
  $i = 0;
	$product_array = $db_handle->runQuery("SELECT * FROM games ORDER BY num_sold DESC");
  if (!empty($product_array)) 
  { 
    foreach($product_array as $key=>$value)
    {
    }
  }
	?>
<div class="w3-row">
    <div class="w3-col l3 s6">
      <div class="w3-container">
        <div class="w3-display-container">
        <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[0]["game_ID"]; ?>">
        <img src="<?php echo $product_array[0]["image"]; ?>" style="width:100%">
          
          </form>          
          
      <p><font size="3"><?php echo $product_array[0]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[0]["price"]; ?></b></p>
        </div>
      </div>
      


    <div class="w3-container">
      <div class="w3-display-container">
      <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[4]["game_ID"]; ?>">
      <img src="<?php echo $product_array[4]["image"]; ?>" style="width:100%">
        
        
        </form>          
        
        <p><font size="3"><?php echo $product_array[4]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[4]["price"]; ?></b></p>
      </div>
    </div>
  </div>


  <div class="w3-col l3 s6">
    <div class="w3-container">
      <div class="w3-display-container">
      <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[1]["game_ID"]; ?>">
      <img src="<?php echo $product_array[1]["image"]; ?>" style="width:100%">
        
        
        </form>          
        
        <p><font size="3"><?php echo $product_array[1]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[1]["price"]; ?></b></p>
      </div>  
    </div>
    <div class="w3-container">
    <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[5]["game_ID"]; ?>">
    <img src="<?php echo $product_array[5]["image"]; ?>" style="width:100%">
      
      
      </form>          
      
      <p><font size="3"><?php echo $product_array[5]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[5]["price"]; ?></b></p>
    </div>
  </div>

    <div class="w3-col l3 s6">
      <div class="w3-container">
      <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[2]["game_ID"]; ?>">
    <img src="<?php echo $product_array[2]["image"]; ?>" style="width:100%">
      
      
      </form>          
      
      <p><font size="3"><?php echo $product_array[2]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[2]["price"]; ?></b></p>
      </div>
      <div class="w3-container">
        <div class="w3-display-container">
        <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[6]["game_ID"]; ?>">
    <img src="<?php echo $product_array[6]["image"]; ?>" style="width:100%">
      
      
      </form>          
      
      <p><font size="3"><?php echo $product_array[6]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[6]["price"]; ?></b></p>
        </div>
      </div>
    </div>

    <div class="w3-col l3 s6">
      <div class="w3-container">
      <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[3]["game_ID"]; ?>">
    <img src="<?php echo $product_array[3]["image"]; ?>" style="width:100%">
      
      
      </form>          
      
      <p><font size="3"><?php echo $product_array[3]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[3]["price"]; ?></b></p>
      </div>
      <div class="w3-container">
      <form method="post" action="home.php?action=add&game_ID=<?php echo $product_array[7]["game_ID"]; ?>">
    <img src="<?php echo $product_array[7]["image"]; ?>" style="width:100%">
      
      
      </form>          
      
      <p><font size="3"><?php echo $product_array[7]["game_name"]; ?><br><b><font size="3"><?php echo "$".$product_array[7]["price"]; ?></b></p>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <footer class="w3-container w3-black  w3-center w3-opacity">  
  <div class="w3-xlarge w3-padding-32">
  	
        <h3 class="w3-margin w3-xlarge">Contact:</h3>
        <p><i class="fa fa-fw fa-phone"><font size="4"></i> 1234567890</p>
        <p><i class="fa fa-fw fa-envelope"><font size="4"></i> onespace@mail.com</p>
	<h3 class="w3-margin w3-xlarge">Follow us on:</h3>
    <i class="fa fa-facebook-official fa-2x w3-hover-opacity"></i>
    <i class="fa fa-instagram fa-2x w3-hover-opacity"></i>
    <i class="fa fa-snapchat fa-2x w3-hover-opacity"></i>
 <!--   <i class="fa fa-pinterest-p w3-hover-opacity"></i>
    <i class="fa fa-twitter w3-hover-opacity"></i>
    <i class="fa fa-linkedin w3-hover-opacity"></i>   -->
 </div>
</footer>
	
  <div class="w3-black w3-center w3-padding-24">Powered by w3.css</a>
  <p>Created by Davin, Krishna and Karishma</p></div>

  <!-- End page content -->
</div>


<script>
// Accordion 
function myAccFunc() {
  var x = document.getElementById("demoAcc");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}

// Click on the "Jeans" link on page load to open the accordion for demo purposes
document.getElementById("myBtn").click();


// Open and close sidebar
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
</script>

</body>
</html>