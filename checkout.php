<?php
  session_start();
  if (isset($_SESSION['fname']) == true)
  {
    if($_SESSION['is_admin'] == 1)
    {
      header("location: manager.php");
    }
    else
    {
    $user = '<a href ="logout.php" class = "w3-bar-item w3-button w3-padding-large">Log out</a>';
    $wishlist = "";
    $greeting = "Welcome to the member's area, " . $_SESSION['fname'] . "!";
    }
  }
  else
  {
    $user = '<a href="login.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Log in</a>';
    $wishlist = '<a href ="newuser.php" class = "w3-bar-item w3-button w3-padding-large">New User</a>';
    $greeting = "Your Best Place to Shop Games";
  }
  require_once "config.php";


  require_once("dbcontroller.php");

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
      //echo $_SESSION["cart_item"];
          if(!empty($_SESSION["cart_item"])) {
              foreach($_SESSION["cart_item"] as $k => $v) 
              {
                      if($_GET["game_ID"] == $v["game_ID"])
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
<title>game shop</title>
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
    <h3 class="w3-wide"><font size="3">Your Best Place to Shop Games <i class="fa fa-gamepad fa-2x"></i></font></h3>
	 
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
  <?php echo $wishlist ?>
  <?php echo $user ?>
 <!--    <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 4</a>   -->
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
    <a href="home.php" class="w3-bar-item w3-button w3-padding-large w3-hover-white">Home</a>
    <?php echo $wishlist ?>

    <a href="cart.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Shopping Cart</a>
    <?php echo $user ?>
   <!-- <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Link 4</a> -->
  </div>
    <h3 class="w3-wide"><p class="w3-center"><b>One Space</b></h3></p>
    
    
  </header>
  <!-- Image header -->


  <div class="w3-container" id="jeans">
    <p><b><font size="6">Shopping Cart</font></b></p>

    <HEAD>
<TITLE>Simple PHP Shopping Cart</TITLE>
<link href="style.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
<div id="shopping-cart">
<div class="w3-container">
<a id="btnEmpty" href="cart.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Game</th>

<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /></td>
				
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="checkout.php?action=remove&game_ID=<?php echo $item["game_ID"]; ?>" class="btnRemoveAction"><img src="trash_can2.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>

</tr>

</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>

  </div>
</div>

  
 <!-- insert item -->
 <?php

 $user_fname = $user_lname = $user_address = $credit_card_num = $current_game_qty = $order_confim = $game_name = $game_id = $current_sold = $user_email = $purchase_err = $user_num_games_purch = "";
 $user_fname_err = $user_lname_err = $user_address_err = $credit_card_num_err = $current_game_qty_err = $order_confim_err = $user_email_err ="";
 $user_in_DB = true;
    
   // Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    foreach($_SESSION["cart_item"] as $item)
    {

    
       // Validate username
       if(empty(trim($_POST["user_email"])))
       {
        $user_email_err = "Please enter your email.";
       } 
       if(empty(trim($_POST["user_fname"])))
       {
           $user_fname_err = "Please enter your first name.";
       } 
       if(empty(trim($_POST["user_lname"])))
       {
           $user_lname_err = "Please enter your last name.";
       }
       if(empty(trim($_POST["user_address"])))
       {
           $user_address_err = "Please enter a address.";
       }
       if(empty(trim($_POST["credit_card"])) || $_POST["credit_card"] < 16)
       {
           $credit_card_num_err = "Please enter a credit card with length 16";
       }
       else
       {
           $game_id = $item["game_ID"];
           $game_name = $item["game_name"];
           $user_email = trim($_POST["user_email"]); 
           // Prepare a select statement
           $sql = "SELECT game_name, stock_qty, num_sold FROM games WHERE game_name = '$game_name' && game_ID = '$game_id'";
           $sql5 = "SELECT * FROM user WHERE user_email = '$user_email'";
           
           if($result = mysqli_query($link, $sql))
           {
               $rowcount = mysqli_num_rows($result);

               if($rowcount == 1)
               {
                    $getArray = $result->fetch_array();
                    $current_game_qty = $getArray["stock_qty"];         
                    $current_sold = $getArray["num_sold"];
                    if($result2 = mysqli_query($link, $sql5))
                    {
                      //echo "Got here result 2";
                        $rowcount2 = mysqli_num_rows($result2);
                        if($rowcount2 == 1)
                        {
                         // echo "Got here rowcount";
                          $getArray2 = $result2->fetch_array();
                          $user_num_games_purch = $getArray2["num_purchased_games"];
                         // echo $getArray2["num_purchased_games"];
                          
                        }
                        else
                        {
                          $user_in_DB = false;
                        }

                    }
                                        
                    if($current_game_qty == 0 || $current_game_qty < 0)
                    {
                            //echo "Sorry we are out of that game!";
                            $purchse_err = "Sorry we are out of that game!";
                            break;
                    }
               } 
               else
               {
                   $game_name_err = "Something went wrong, terribly wrong.";
               }
           } 
           else
           {
               echo "Oops! Something went wrong. Please try again later.";
           }
       }  
       
       // Check input errors before inserting in database
       if(empty($user_email_err) && empty($user_fname_err) && empty($user_lname_err) && empty($user_address_err) && empty($user_address_err))
       {
           
           // Prepare an insert statement
           $sql = "UPDATE games SET stock_qty=?, num_sold = ? WHERE (game_ID = ? && game_name = ?)";
           $sql2 = "INSERT INTO orders (user_email, game_ID, order_date, game_name) VALUES (?, ?, ?, ?)";
           $sql4 = "UPDATE user SET num_purchased_games	=? WHERE (user_email = ?)";
         //  $sql3 = "UPDATE orders SET order_confirmation=? WHERE (user_email = ? && order_date = ?)";
           //$stmt = mysqli_prepare($link, $sql)
           if($stmt = mysqli_prepare($link, $sql))
           {
                    $stmt2 = mysqli_prepare($link, $sql2);
                    if($user_in_DB)
                    {
                      $stmt4 = mysqli_prepare($link, $sql4);
                    }
                    //$stmt4 = mysqli_prepare($link, $sql4);
                
                    //echo "got here";
                    $order_date = date("Y-m-d");
                    $user_email = trim($_POST["user_email"]);
                    $num_sold = ($current_sold + $item["quantity"]);
                    $game_name = $item["game_name"];
                    $game_id = $item["game_ID"]; 
                    $game_qty = ($current_game_qty - $item["quantity"]);
                    
                    mysqli_stmt_bind_param($stmt, 'ssss', $game_qty,  $num_sold, $game_id, $game_name);
                    mysqli_stmt_bind_param($stmt2, 'ssss', $user_email,  $game_id, $order_date, $game_name);

                    if($user_in_DB)
                    {
                      $update_user_num_games_purch = ($user_num_games_purch + $item["quantity"]);
                      mysqli_stmt_bind_param($stmt4, 'ss', $update_user_num_games_purch, $user_email);
                    }

                    
                    if($current_game_qty - $item["quantity"] < 0)
                    {
                        echo "Sorry we are out of that game! Your order has been canceled!";
                        $order_confim = "";
                        break;
                    }

                    if(mysqli_stmt_execute($stmt))
                    {
                        mysqli_stmt_execute($stmt2);
                        if($user_in_DB)
                        {
                          mysqli_stmt_execute($stmt4);
                        }   
                        //mysqli_stmt_execute($stmt4);                    
                        $order_confim .= $game_id;
                    }
                    else
                    {
                        printf("Error: %s.\n", mysqli_stmt_error($stmt));
                        printf("Error: %s.\n", mysqli_stmt_error($stmt2));
                        printf("Error: %s.\n", mysqli_stmt_error($stmt4));
                    }   

                }
            
           // Close statement
           mysqli_stmt_close($stmt);
           mysqli_stmt_close($stmt2);
           if($user_in_DB)
           {
            mysqli_stmt_close($stmt4);
           }
           //mysqli_stmt_close($stmt4);

        }

    }
       
}
if(empty($purchse_err))
{
    if(!empty($order_confim))
    {
        $sql3 = "UPDATE orders SET order_confirmation=? WHERE (user_email = ? && order_date = ?)";
        $stmt3 = mysqli_prepare($link, $sql3);
        $order_confim .= date("Ymd");
        $order_confim .= date("h");
        $order_confim .= $user_lname;
        mysqli_stmt_bind_param($stmt3, 'sss', $order_confim, $user_email, $order_date);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_close($stmt3);
        unset($_SESSION["cart_item"]);
        //echo "You Order confirmation is $order_confim";
        
    }
}
else
{
    echo "Looks like something went wrong with your order. Most likly you ordered a game we're out of stock, or orderd too many of a game that we are close to out of stock of. Try again with a lower number or check back later";
    $order_confim = "";
}

  // mysqli_stmt_close($stmt);
   mysqli_close($link);


 ?>
 

    
 <center>
      <div>
      <p><b><font size="6">Order Form</font></b></p>
		</div>
		<form form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"> 
        <table border = "0" width = "300" height = "150">
        <tr> <td>Email</td>
            <td><input type="text" name="user_email" value="<?php echo $user_email; ?>">
            <?php echo $user_email_err; ?> </td> </tr>

        <tr> <td>First Name</td>
            <td><input type="text" name="user_fname" value="<?php echo $user_fname; ?>">
            <?php echo $user_fname_err; ?> </td> </tr>
        
        <tr> <td>Last Name </td>
            <td><input type="text" name="user_lname" value="<?php echo $user_lname; ?>"> 
            <?php echo $user_lname_err; ?> </td> </tr>
        
        <tr> <td>Address</td>
            <td><input type="text" name="user_address" value="<?php echo $user_address; ?>"> 
            <?php echo $user_address_err; ?></td> </tr>

            <tr> <td>Credit Card Number</td>
            <td><input type="text" name="credit_card" value="<?php echo $credit_card_num; ?>"> 
            <?php echo $credit_card_num_err; ?></td> </tr>

        
        </table>
        
        <input class="w3-button w3-black w3-margin-top" type = "submit" value = "Submit">
            <input type="reset" class="w3-button w3-black w3-margin-top" value="Reset">
        <p> </p>
        </form>
	
 
        </center> 
    </div>
    <center>
    <?php
    if(!empty($order_confim))
    {
      ?>
    <p><b><font size="6">Your Order Confirmation<?php echo $order_confim ?></font></b></p>
    <?php
    }
    else
    {
      
    ?>
    <p><b><font size="6"><?php echo $order_confim ?></font></b></p>
    </center> 
  </div>
  <?php
    }
    ?>

  
  
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