<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$user_email = $user_fname = $user_lname = $password = $confirm_password = "";
$user_email_err = $user_fname_err = $user_lname_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["user_email"]))){
        $user_email_err = "Please enter a email.";
    } 
    else
    {
        // Prepare a select statement
        $sql = "SELECT user_ID FROM user WHERE user_email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_user_email);
            
            // Set parameters
            $param_user_email = trim($_POST["user_email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $user_email_err = "This email is already taken.";
                } 
                else
                {
                    $user_email = trim($_POST["user_email"]);
                }
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter a password.";
    }
    if(empty(trim($_POST["user_fname"])))
    {
        $user_fname_err = "Please enter a first name.";
    }
    if(empty(trim($_POST["user_lname"])))
    {
        $user_lname_err = "Please enter a last name.";
    }        
    elseif(strlen(trim($_POST["password"])) < 6)
    {
        $password_err = "Password must have atleast 6 characters.";
    } 
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($user_email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (user_email, user_fname, user_lname, user_pass, num_purchased_games, is_admin) VALUES (?, ?, ?, ?, 0, 0)";
       // echo "Got here";
       //$conn = mysqli_connect("localhost","root","","game_store");
        if($stmt = mysqli_prepare($link, $sql))
        {
         // echo "Got here";
            $user_fname = trim($_POST["user_fname"]);
            $user_lname = trim($_POST["user_lname"]);
           // $num_purchased_games = 0;
           // $is_moderator = 0;
           // $param_user_email = $user_email;
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, 'ssss', $param_user_email, $user_fname, $user_lname, $password);
            //echo "$param_user_email, $user_fname, $user_lname, $password";
            // Set parameters
           // $user_fname = trim($_POST["user_fname"]);
           // $user_lname = trim($_POST["user_lname"]);
           // $param_user_email = $user_email;
            header("location: home.php");
           // $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Redirect to home page
                header("location: login.php");
            } else
            {
             //header("location: login.php");;
             echo "Fail";
            }
            mysqli_stmt_close($stmt);
        }
        else
        {
          echo("Error description: " . mysqli_error($link));
        }
         
        // Close statement
        //mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
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
    <a href="login.php" class="w3-bar-item w3-button w3-padding-large">Log in</a>
   <a href="newuser.php" class="w3-bar-item w3-button w3-padding-large">New User</a>
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
    <a href="login.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Log in</a>
    <a href="newuser.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">New User</a>
   <!-- <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Link 4</a> -->
  </div>
    <h3 class="w3-wide"><p class="w3-center"><b>One Space</b></h3></p>
    
   
  </header>

  <!-- Image header -->


  <div class="w3-container w3-center" id="jeans">
    <p><b><font size="6">New User</font></b></p>

  </div>

  

<!-- First Grid -->
<div class="w3-row-padding  w3-container">
  <div class="w3-content">
    <div class="w3-center">
      
    <center>
		<form action="<?php ($_SERVER['PHP_SELF']); ?>" method="post">
		<table border = "0" width="300" height = "150">
		<tr>  <td>
                <label>First Name</label>
</td>
                <td>
                <input type="text" name="user_fname" value="<?php echo $user_fname; ?>">
                <?php echo $user_fname_err; ?>
</td>
            
     		</tr>      
		<tr>    <td>
                <label>Last Name</label>
</td>
               <td> <input type="text" name="user_lname" value="<?php echo $user_lname; ?>">
               <?php echo $user_lname_err; ?>
</td>
               
               
            
 
        </tr>
        <tr>    <td>
                <label>Email</label>
</td>
<td>
                <input type="text" name="user_email" value="<?php echo $user_email; ?>">
                 <?php echo $user_email_err; ?>
</td>
                
               
            </td>
    <td>
		</td>
        </tr>
        <tr>    <td>
                <label>Password</label>
</td>
                <td><input type="password" name="password" value="<?php echo $password; ?>">
                <?php echo $password_err; ?>
</td>
                
            </td>
    <td>
		</td>
        </tr>
        <tr>    <td>
                <label>Confirm Password</label>
</td>
                <td><input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                <?php echo $confirm_password_err; ?>
</td>
 </td>
    <td>
		</td>
		</tr>
		<tr>    <td colspan="5" align = "center"> <input class="w3-button w3-black w3-margin-top" type = "submit" name = "Register" value = "Submit">
		</td>
		</tr>
		</table>
        </form>
</div>
		</center>
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